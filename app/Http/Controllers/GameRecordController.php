<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecordGamesChild;
use Illuminate\Support\Facades\Auth;

class GameRecordController extends Controller
{
    /**
     * Actualiza el récord de un juego infantil para el usuario o perfil infantil activo.
     */
    public function updateRecord(Request $request)
    {
        $request->validate([
            'game' => 'required|string|in:record_Eco,record_Guardianes,record_Cazador',
            'score' => 'required|integer|min:0',
            'child_id' => 'nullable',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Prioriza el child_id enviado por AJAX, si llega vacío busca en la sesión de Laravel
        $childId = $request->input('child_id') ?: session('active_child_id');

        // Búsqueda explícita del registro
        if ($childId) {
            // Busca o crea usando child_profile_id
            $record = RecordGamesChild::firstOrCreate(
                ['child_profile_id' => $childId],
                [
                    'user_id' => null,
                    'record_Eco' => 0,
                    'record_Guardianes' => 0,
                    'record_Cazador' => 0,
                ]
            );
        } else {
            // Busca o crea usando user_id del adulto
            $record = RecordGamesChild::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'child_profile_id' => null,
                    'record_Eco' => 0,
                    'record_Guardianes' => 0,
                    'record_Cazador' => 0,
                ]
            );
        }

        $game = $request->game;
        $newScore = (int) $request->score;

        if ($newScore > $record->$game) {
            $record->$game = $newScore;
            $record->save();

            return response()->json([
                'success' => true,
                'updated' => true,
                'highScore' => $record->$game,
                'message' => '¡Nuevo récord guardado!',
                'target' => $childId ? 'child_profile' : 'user' // Para fácil depuración
            ]);
        }

        return response()->json([
            'success' => true,
            'updated' => false,
            'highScore' => $record->$game,
            'message' => 'Puntaje conservado'
        ]);
    }

    /**
     * Obtiene los récords del usuario o del perfil infantil activo.
     */
    public function getRecords(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $childId = $request->query('child_id') ?: session('active_child_id');

        $query = RecordGamesChild::query();

        if ($childId) {
            $query->where('child_profile_id', $childId);
        } else {
            $query->where('user_id', $user->id);
        }

        $record = $query->first();

        return response()->json([
            'record_Eco' => $record ? $record->record_Eco : 0,
            'record_Guardianes' => $record ? $record->record_Guardianes : 0,
            'record_Cazador' => $record ? $record->record_Cazador : 0,
        ]);
    }
}