<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecordGamesChild;
use Illuminate\Support\Facades\Auth;

class ChildGamesController extends Controller
{
    /**
     * Obtiene el récord del usuario o del perfil infantil activo.
     */
    private function getUserRecord(string $column)
    {
        if (!Auth::check()) {
            return 0;
        }

        $activeChildId = session('active_child_id');
        $userId = Auth::id();

        $query = RecordGamesChild::query();

        if ($activeChildId) {
            $query->where('child_profile_id', $activeChildId);
        } else {
            $query->where('user_id', $userId);
        }

        return $query->value($column) ?? 0;
    }

    public function guardianes()
    {
        $userRecord = $this->getUserRecord('record_Guardianes');
        return view('games.childs.guardianes', compact('userRecord'));
    }

    public function eco()
    {
        $userRecord = $this->getUserRecord('record_Eco');
        return view('games.childs.eco', compact('userRecord'));
    }

    public function cazador()
    {
        $userRecord = $this->getUserRecord('record_Cazador');
        return view('games.childs.cazador', compact('userRecord'));
    }
}