<?php

namespace App\Http\Controllers;

use App\Models\ForumPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    // Máximo de fotos por usuario
    const MAX_PHOTOS_PER_USER = 10;

    /**
     * Página principal del foro (galería + cámara).
     * Cualquier visitante puede ver la galería.
     */
    public function index()
    {
        $photos = ForumPhoto::orderBy('created_at', 'desc')->paginate(20);

        $lumenImages = collect(glob(public_path('img/lumen/*.png')))
            ->map(fn($path) => basename($path))
            ->values();

        return view('forum.index', compact('photos', 'lumenImages'));
    }

    /**
     * Guardar la foto capturada (requiere autenticación).
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
            'caption' => 'nullable|string|max:200',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Verificar límite de fotos por usuario
        $photoCount = ForumPhoto::where('user_id', $user->id)->count();
        if ($photoCount >= self::MAX_PHOTOS_PER_USER) {
            return response()->json([
                'success' => false,
                'message' => 'Has alcanzado el limite de ' . self::MAX_PHOTOS_PER_USER . ' fotos. Elimina alguna para subir más.',
            ], 422);
        }

        // Decodificar la imagen base64 (soporta JPEG y PNG)
        $imageData = $request->input('image');
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $imageBytes = base64_decode($imageData);

        // Generar nombre único
        $filename = 'lumen_' . Str::random(16) . '.jpg';
        $path = 'forum/' . $filename;

        // Guardar en storage/app/public/forum
        Storage::disk('public')->put($path, $imageBytes);

        // Guardar en base de datos
        $photo = ForumPhoto::create([
            'user_id' => $user->id,
            'username' => $user->name,
            'image_path' => $path,
            'caption' => $request->input('caption'),
        ]);

        return response()->json([
            'success' => true,
            'photo' => $photo,
            'url' => Storage::url($path),
        ]);
    }

    /**
     * Eliminar una foto (solo el autor o admin).
     */
    public function destroy(ForumPhoto $photo)
    {
        $user = Auth::user();

        if (!$user || ($user->id !== $photo->user_id && $user->role !== 'admin')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        Storage::disk('public')->delete($photo->image_path);
        $photo->delete();

        return response()->json(['success' => true]);
    }
}
