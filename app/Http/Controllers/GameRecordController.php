<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecordGamesChild;
use Illuminate\Support\Facades\Auth;

class GameRecordController extends Controller
{
    /**
     * Actualiza el récord de un juego infantil para el usuario autenticado.
     */
    public function updateRecord(Request $request)
    {
        $request->validate([
            'game' => 'required|string|in:record_Eco,record_Guardianes,record_Cazador',
            'score' => 'required|integer|min:0',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Busca o crea el registro del usuario por su email
        $record = RecordGamesChild::firstOrCreate(
            ['email' => $user->email]
        );

        $game = $request->game;
        $newScore = $request->score;

        // Solo se actualiza si el nuevo puntaje es mayor al actual
        if ($newScore > $record->$game) {
            $record->$game = $newScore;
            $record->save();

            return response()->json([
                'success' => true,
                'updated' => true,
                'highScore' => $record->$game,
                'message' => '¡Nuevo récord guardado en la base de datos!'
            ]);
        }

        return response()->json([
            'success' => true,
            'updated' => false,
            'highScore' => $record->$game,
            'message' => 'Puntaje guardado (no superó el récord)'
        ]);
    }

    /**
     * Obtiene los récords del usuario autenticado.
     */
    public function getRecords()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $record = RecordGamesChild::where('email', $user->email)->first();

        return response()->json([
            'record_Eco' => $record ? $record->record_Eco : 0,
            'record_Guardianes' => $record ? $record->record_Guardianes : 0,
            'record_Cazador' => $record ? $record->record_Cazador : 0,
        ]);
    }
}
