<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $adultTeaCount = User::where('role', 'adult_tea')->count();
        $allyCount = User::where('role', 'ally_no_tea')->count();
        $teenCount = User::where('role', 'teen')->count();
        $minorCount = User::where('role', 'minor')->count();

        // Apunta a resources/views/profile/administration.blade.php
        return view('profile.administration', compact(
            'totalUsers',
            'adminCount',
            'adultTeaCount',
            'allyCount',
            'teenCount',
            'minorCount'
        ));
    }
}