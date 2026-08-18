<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeenController extends Controller
{
    public function dashboard()
    {
        $teen = Auth::user();
        $supervisor = $teen->supervisor_id ? User::find($teen->supervisor_id) : null;

        return view('profile.adult-supervisor', compact('teen', 'supervisor'));
    }

    public function updateSupervisor(Request $request)
    {
        $request->validate([
            'supervisor_email' => ['required', 'email', 'exists:users,email'],
        ], [
            'supervisor_email.exists' => __('teen.supervisor_not_found'),
        ]);

        $supervisor = User::where('email', $request->supervisor_email)->first();

        if (in_array($supervisor->role, ['minor', 'teen'])) {
            return back()->withErrors(['supervisor_email' => __('teen.supervisor_must_be_adult')]);
        }

        Auth::user()->update([
            'supervisor_id' => $supervisor->id
        ]);

        return back()->with('success', __('teen.supervisor_linked_success'));
    }
}