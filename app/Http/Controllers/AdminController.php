<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChildProfile;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Muestra el panel con las colecciones de usuarios y métricas.
     */
    public function dashboard()
    {
        $adults = User::whereIn('role', ['adult_tea', 'ally_no_tea'])->get();
        $teens = User::where('role', 'teen')->get();
        $minors = ChildProfile::all();

        $totalUsers = User::count() + ChildProfile::count();
        $adultTeaCount = User::where('role', 'adult_tea')->count();
        $allyCount = User::where('role', 'ally_no_tea')->count();
        $teenCount = User::where('role', 'teen')->count();
        $minorCount = ChildProfile::count();

        return view('profile.administration', compact(
            'adults', 'teens', 'minors', 'totalUsers', 'adultTeaCount', 'allyCount', 'teenCount', 'minorCount'
        ));
    }

    /**
     * Almacena usuarios (Adultos y Jóvenes) con validación de edad.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'birthdate' => ['required', 'date', 'before:today'],
            'role' => ['required', 'string', 'in:adult_tea,ally_no_tea,teen'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'password.min' => 'La contraseña temporal debe tener al menos 6 caracteres.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado.',
            'birthdate.before' => 'La fecha de nacimiento debe ser anterior al día de hoy.',
        ]);

        $birthdate = Carbon::parse($validated['birthdate']);
        $age = $birthdate->age;

        if (in_array($validated['role'], ['adult_tea', 'ally_no_tea']) && $age < 18) {
            return back()->withErrors([
                'birthdate' => 'El usuario registrado como Adulto/Aliado debe tener al menos 18 años.'
            ])->withInput();
        }

        if ($validated['role'] === 'teen' && ($age < 13 || $age > 17)) {
            return back()->withErrors([
                'birthdate' => 'El usuario registrado como Joven (Teen) debe tener entre 13 y 17 años.'
            ])->withInput();
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'birthdate' => $validated['birthdate'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Usuario registrado correctamente.');
    }

    /**
     * Almacena menores de edad (ChildProfile).
     */
    public function storeMinor(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date', 'before:today'],
            'parent_pin' => ['required', 'digits:4'],
            'tutor_email' => ['required', 'email'],
        ], [
            'parent_pin.digits' => 'El PIN parental debe ser exactamente de 4 dígitos.',
            'birthdate.before' => 'La fecha de nacimiento debe ser anterior al día de hoy.',
        ]);

        $birthdate = Carbon::parse($validated['birthdate']);
        $age = $birthdate->age;

        if ($age >= 13) {
            return back()->withErrors([
                'birthdate' => 'El perfil de menor solo está permitido para niños menores de 13 años.'
            ])->withInput();
        }

        $tutor = User::where('email', $validated['tutor_email'])
                     ->where('role', 'ally_no_tea')
                     ->first();

        if (!$tutor) {
            return back()->withErrors([
                'tutor_email' => 'El correo indicado no pertenece a un usuario con el rol de Aliado (ally_no_tea) válido.'
            ])->withInput();
        }

        ChildProfile::create([
            'user_id' => $tutor->id,
            'name' => $validated['name'],
            'birthdate' => $validated['birthdate'],
            'parent_pin' => $validated['parent_pin'],
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Menor registrado correctamente bajo la tutela de ' . $tutor->name);
    }

   /**
     * Edición para Adultos (Nombre, Correo, Rol) y Adolescentes (Nombre, Correo, Supervisor ID)
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'teen') {
            // ADOLESCENTES: Nombre, Correo y Supervisor ID (validando que sea un ally_no_tea)
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email,' . $id],
                'supervisor_id' => ['required', 'exists:users,id']
            ]);

            $supervisor = User::where('id', $validated['supervisor_id'])
                              ->where('role', 'ally_no_tea')
                              ->first();

            if (!$supervisor) {
                return back()->withErrors(['supervisor_id' => 'El supervisor seleccionado debe ser obligatoriamente un registro con rol Aliado (ally_no_tea).'])->withInput();
            }

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'supervisor_id' => $validated['supervisor_id']
            ]);

        } else {
            // ADULTOS: Nombre, Correo y Rol
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email,' . $id],
                'role' => ['required', 'string', 'in:adult_tea,ally_no_tea']
            ]);

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role']
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Edición para Menores (Nombre, PIN, Supervisor ID)
     */
    public function updateMinor(Request $request, $id)
    {
        $minor = ChildProfile::findOrFail($id);

        // MENORES: Nombre, PIN y Supervisor ID (validando que sea un ally_no_tea)
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_pin' => ['required', 'digits:4'],
            'supervisor_id' => ['required', 'exists:users,id']
        ]);

        $supervisor = User::where('id', $validated['supervisor_id'])
                          ->where('role', 'ally_no_tea')
                          ->first();

        if (!$supervisor) {
            return back()->withErrors(['supervisor_id' => 'El supervisor seleccionado debe ser obligatoriamente un registro con rol Aliado (ally_no_tea).'])->withInput();
        }

        $minor->update([
            'name' => $validated['name'],
            'parent_pin' => $validated['parent_pin'],
            'user_id' => $validated['supervisor_id'] // user_id almacena el ID del tutor en la tabla de menores
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Menor actualizado correctamente.');
    }
    
    /**
     * Elimina un usuario (Adulto o Joven).
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Usuario eliminado correctamente.');
    }

    /**
     * Elimina un perfil de menor (ChildProfile).
     */
    public function destroyMinor($id)
    {
        $minor = ChildProfile::findOrFail($id);
        $minor->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Registro de menor eliminado correctamente.');
    }
}