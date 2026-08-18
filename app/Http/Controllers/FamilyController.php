<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChildProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class FamilyController extends Controller
{
    // Muestra el panel familiar con los menores y los jóvenes supervisados
    public function index()
    {
        $adultId = Auth::id();

        // 1. Menores de 12 años
        $children = auth()->user()->children()->get();

        // 2. Jóvenes (13-17 años) que vincularon a este adulto por correo
        $supervisedTeens = User::where('supervisor_id', $adultId)
            ->where('role', 'teen')
            ->get();

        return view('profile.family-panel', compact('children', 'supervisedTeens'));
    }

    // Procesa el registro del menor con validaciones
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date'],
            'parent_pin' => ['required', 'digits:4'],
        ], [
            'name.required' => __('family.name_required'),
            'birthdate.required' => __('family.birthdate_required'),
            'birthdate.date' => __('family.birthdate_date'),
            'parent_pin.required' => __('family.pin_required'),
            'parent_pin.digits' => __('family.pin_digits'),
        ]);

        // Calcular la edad exacta
        $age = Carbon::parse($request->birthdate)->age;

        // Validar que sea estrictamente menor de 12 años
        if ($age >= 12) {
            return back()
                ->withErrors(['birthdate' => __('family.under_12_error')])
                ->withInput();
        }

        // Guardar en la base de datos vinculado al usuario actual
        ChildProfile::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'birthdate' => $request->birthdate,
            'parent_pin' => $request->parent_pin,
        ]);

        return redirect()->route('family-panel')->with('success', __('family.success_created'));
    }
}