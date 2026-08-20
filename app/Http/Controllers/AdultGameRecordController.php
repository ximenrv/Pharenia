<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecordGamesAdult;
use Illuminate\Support\Facades\Auth;

class AdultGameRecordController extends Controller
{
    /**
     * Actualiza las estrellas de un juego de adultez para el usuario autenticado.
     */
    public function updateRecord(Request $request)
    {
        $request->validate([
            'game' => 'required|string|in:stars_OfertaOEngano,stars_SigueLaReceta',
            'score' => 'required|integer|min:0',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $record = RecordGamesAdult::firstOrCreate(
            ['email' => $user->email]
        );

        $game = $request->game;
        $newScore = $request->score;

        // Solo se actualiza si el nuevo puntaje de estrellas es mayor al actual
        if ($newScore > $record->$game) {
            $record->$game = $newScore;
            $record->save();

            return response()->json([
                'success' => true,
                'updated' => true,
                'highScore' => $record->$game,
                'message' => '¡Nuevo récord de estrellas guardado!'
            ]);
        }

        return response()->json([
            'success' => true,
            'updated' => false,
            'highScore' => $record->$game,
            'message' => 'Estrellas guardadas (no superó el récord)'
        ]);
    }

    /**
     * Obtiene las estrellas del usuario autenticado.
     */
    public function getRecords()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $record = RecordGamesAdult::where('email', $user->email)->first();

        return response()->json([
            'stars_OfertaOEngano' => $record ? $record->stars_OfertaOEngano : 0,
            'stars_SigueLaReceta' => $record ? $record->stars_SigueLaReceta : 0,
        ]);
    }
}
