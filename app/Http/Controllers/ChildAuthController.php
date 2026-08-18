<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChildProfile;
use Illuminate\Support\Facades\Hash;

class ChildAuthController extends Controller
{
    // Muestra la vista para introducir el PIN de ingreso
    public function showPinForm($id)
    {
        $child = ChildProfile::findOrFail($id);
        return view('auth.child-pin', compact('child'));
    }

    // Verifica el PIN de ingreso
    public function verifyPin(Request $request, $id)
    {
        $request->validate([
            'pin' => ['required', 'digits:4']
        ], [
            'pin.required' => __('child_auth.pin_required'),
            'pin.digits' => __('child_auth.pin_digits'),
        ]);

        $child = ChildProfile::findOrFail($id);

        if ($request->pin === $child->parent_pin) {
            // Registramos la sesión del menor
            session(['active_child_id' => $child->id]);
            session(['active_child_name' => $child->name]);

            // Redirección directa a la ruta de niñez
            return redirect('/actividades/ninez');
        }

        return back()->withErrors(['pin' => __('child_auth.pin_incorrect')]);
    }

    // Muestra el panel exclusivo de actividades
    public function activities()
    {
        // Textos y datos traducidos para el módulo de niñez
        $data = [
            'title' => __('child_auth.module_title'),
            'subtitle' => __('child_auth.module_subtitle'),
            'bg_color' => '#f0fdf4',
            'accent_color' => '#2f4f4f',
            'games' => [
                [
                    'title' => __('child_auth.game_1_title'),
                    'desc' => __('child_auth.game_1_desc'),
                    'img' => 'juego1.jpg',
                    'url' => '#'
                ],
                [
                    'title' => __('child_auth.game_2_title'),
                    'desc' => __('child_auth.game_2_desc'),
                    'img' => 'juego1.jpg',
                    'url' => '#'
                ],
                [
                    'title' => __('child_auth.game_3_title'),
                    'desc' => __('child_auth.game_3_desc'),
                    'img' => 'juego1.jpg',
                    'url' => '#'
                ],
            ]
        ];

        return view('activities/stage', compact('data'));
    }

    // Muestra el formulario para pedir el PIN antes de cerrar sesión
    public function showLogoutPinForm()
    {
        if (!session()->has('active_child_id')) {
            return redirect()->route('family-panel');
        }

        return view('auth.child-logout-pin');
    }

    // Verifica el PIN para autorizar el cierre de sesión
    public function verifyLogoutPin(Request $request)
    {
        $request->validate([
            'pin' => ['required', 'digits:4']
        ], [
            'pin.required' => __('child_auth.pin_required'),
            'pin.digits' => __('child_auth.pin_digits'),
        ]);

        $childId = session('active_child_id');
        $child = ChildProfile::findOrFail($childId);

        if ($request->pin === $child->parent_pin) {
            $request->session()->forget(['active_child_id', 'active_child_name']);
            return redirect()->route('family-panel');
        }

        return back()->withErrors(['pin' => __('child_auth.logout_pin_incorrect')]);
    }
}