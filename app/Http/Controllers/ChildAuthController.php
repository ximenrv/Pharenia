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
        return view('child-pin', compact('child'));
    }

    // Verifica el PIN de ingreso
public function verifyPin(Request $request, $id)
{
    $request->validate(['pin' => 'required|digits:4']);

    $child = ChildProfile::findOrFail($id);

    if (Hash::check($request->pin, $child->parent_pin)) {
        // Registramos la sesión del menor
        session(['active_child_id' => $child->id]);
        session(['active_child_name' => $child->name]);

        // Redirección directa a la ruta de niñez
        return redirect('/actividades/ninez');
    }

    return back()->withErrors(['pin' => 'El PIN de seguridad es incorrecto.']);
}

    // Muestra el panel exclusivo de actividades
public function activities()
{
    // Datos necesarios para que tu blade renderice los módulos y estilos
    $data = [
        'title' => 'Módulo de Niñez',
        'subtitle' => 'Actividades Interactivas',
        'bg_color' => '#f0fdf4',
        'accent_color' => '#2f4f4f',
        'games' => [
            [
                'title' => 'Juego 1',
                'desc' => 'Descripción del juego 1',
                'img' => 'juego1.jpg',
                'url' => '#'
            ],
            [
                'title' => 'Juego 2',
                'desc' => 'Descripción del juego 2',
                'img' => 'juego1.jpg',
                'url' => '#'
            ],
            [
                'title' => 'Juego 3',
                'desc' => 'Descripción del juego 3',
                'img' => 'juego1.jpg',
                'url' => '#'
            ],
        ]
    ];

    // AQUÍ ESTÁ LA CLAVE: Asegúrate de que cargue el nombre real de tu archivo blade (ej. 'stages.show' o como lo hayas nombrado)
    return view('activities/stage', compact('data'));
}

    // Muestra el formulario para pedir el PIN antes de cerrar sesión
    public function showLogoutPinForm()
    {
        if (!session()->has('active_child_id')) {
            return redirect()->route('family-panel');
        }

        return view('child-logout-pin');
    }

    // Verifica el PIN para autorizar el cierre de sesión
    public function verifyLogoutPin(Request $request)
    {
        $request->validate(['pin' => 'required|digits:4']);

        $childId = session('active_child_id');
        $child = ChildProfile::findOrFail($childId);

        if (Hash::check($request->pin, $child->parent_pin)) {
            $request->session()->forget(['active_child_id', 'active_child_name']);
            return redirect()->route('family-panel');
        }

        return back()->withErrors(['pin' => 'El PIN de seguridad es incorrecto. No se puede salir sin autorización.']);
    }
}