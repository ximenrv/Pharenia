<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecordGamesChild;
use Illuminate\Support\Facades\Auth;

class ChildGamesController extends Controller
{
    private function getUserRecord(string $column)
    {
        if (Auth::check()) {
            return RecordGamesChild::where('email', Auth::user()->email)
                ->value($column) ?? 0;
        }
        return 0;
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