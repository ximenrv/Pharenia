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
            'password.min' => __('admin.pass_min_6'),
            'email.unique' => __('admin.email_unique'),
            'birthdate.before' => __('admin.birthdate_before'),
        ]);

        $birthdate = Carbon::parse($validated['birthdate']);
        $age = $birthdate->age;

        if (in_array($validated['role'], ['adult_tea', 'ally_no_tea']) && $age < 18) {
            return back()->withErrors([
                'birthdate' => __('admin.adult_age_error')
            ])->withInput();
        }

        if ($validated['role'] === 'teen' && ($age < 13 || $age > 17)) {
            return back()->withErrors([
                'birthdate' => __('admin.teen_age_error')
            ])->withInput();
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'birthdate' => $validated['birthdate'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.dashboard')->with('success', __('admin.user_registered'));
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
            'parent_pin.digits' => __('admin.pin_digits'),
            'birthdate.before' => __('admin.birthdate_before'),
        ]);

        $birthdate = Carbon::parse($validated['birthdate']);
        $age = $birthdate->age;

        if ($age >= 13) {
            return back()->withErrors([
                'birthdate' => __('admin.minor_age_error')
            ])->withInput();
        }

        $tutor = User::where('email', $validated['tutor_email'])
                     ->where('role', 'ally_no_tea')
                     ->first();

        if (!$tutor) {
            return back()->withErrors([
                'tutor_email' => __('admin.tutor_invalid')
            ])->withInput();
        }

        ChildProfile::create([
            'user_id' => $tutor->id,
            'name' => $validated['name'],
            'birthdate' => $validated['birthdate'],
            'parent_pin' => $validated['parent_pin'],
        ]);

        return redirect()->route('admin.dashboard')->with('success', __('admin.minor_registered', ['name' => $tutor->name]));
    }

   /**
     * Edición para Adultos y Adolescentes
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'teen') {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email,' . $id],
                'supervisor_id' => ['required', 'exists:users,id']
            ]);

            $supervisor = User::where('id', $validated['supervisor_id'])
                              ->where('role', 'ally_no_tea')
                              ->first();

            if (!$supervisor) {
                return back()->withErrors(['supervisor_id' => __('admin.supervisor_invalid')])->withInput();
            }

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'supervisor_id' => $validated['supervisor_id']
            ]);

        } else {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email,' . $id],
            ]);

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', __('admin.user_updated'));
    }

    /**
     * Edición para Menores
     */
    public function updateMinor(Request $request, $id)
    {
        $minor = ChildProfile::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_pin' => ['required', 'digits:4'],
            'supervisor_id' => ['required', 'exists:users,id']
        ]);

        $supervisor = User::where('id', $validated['supervisor_id'])
                          ->where('role', 'ally_no_tea')
                          ->first();

        if (!$supervisor) {
            return back()->withErrors(['supervisor_id' => __('admin.supervisor_invalid')])->withInput();
        }

        $minor->update([
            'name' => $validated['name'],
            'parent_pin' => $validated['parent_pin'],
            'user_id' => $validated['supervisor_id']
        ]);

        return redirect()->route('admin.dashboard')->with('success', __('admin.minor_updated'));
    }
    
    /**
     * Elimina un usuario.
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', __('admin.user_deleted'));
    }

    /**
     * Elimina un perfil de menor.
     */
    public function destroyMinor($id)
    {
        $minor = ChildProfile::findOrFail($id);
        $minor->delete();

        return redirect()->route('admin.dashboard')->with('success', __('admin.minor_deleted'));
    }
}