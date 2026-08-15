<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Muestra la pantalla del perfil
    public function edit()
    {
        // Cambiado para que busque directamente en views/edit-profile.blade.php
        return view('profile.edit-profile', ['user' => Auth::user()]);
    }

    // Procesa la actualización de los datos
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validar los campos obligatorios
        $request->validate([
            'name' => 'required|string|max:255', // <-- Limpiado aquí para que solo quede un max:255
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Máximo 2MB
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Si el usuario subió una nueva foto de perfil
        if ($request->hasFile('avatar')) {
            // Eliminar la foto vieja si existe para no acumular basura en el servidor
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Guardar la nueva imagen en la carpeta 'public/avatars'
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->back()->with('success', '¡Perfil actualizado correctamente!');
    }
}