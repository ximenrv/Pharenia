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
        $adults = User::whereIn('role', [User::ROLE_ADULT_TEA, User::ROLE_ALLY])->get();
        $teens = User::where('role', User::ROLE_TEEN)->get();
        $visitors = User::where('role', User::ROLE_VISITOR)->get();
        $minors = ChildProfile::all();

        $totalUsers = User::count() + ChildProfile::count();
        $adultTeaCount = User::where('role', User::ROLE_ADULT_TEA)->count();
        $allyCount = User::where('role', User::ROLE_ALLY)->count();
        $teenCount = User::where('role', User::ROLE_TEEN)->count();
        $visitorCount = User::where('role', User::ROLE_VISITOR)->count();
        $minorCount = ChildProfile::count();

        return view('profile.administration', compact(
            'adults', 'teens', 'visitors', 'minors', 'totalUsers', 'adultTeaCount', 'allyCount', 'teenCount', 'visitorCount', 'minorCount'
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
            'role' => ['required', 'string', 'in:' . implode(',', [User::ROLE_ADULT_TEA, User::ROLE_ALLY, User::ROLE_TEEN, User::ROLE_VISITOR])],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'password.min' => __('admin.pass_min_6'),
            'email.unique' => __('admin.email_unique'),
            'birthdate.before' => __('admin.birthdate_before'),
        ]);

        $birthdate = Carbon::parse($validated['birthdate']);
        $age = $birthdate->age;

        if (in_array($validated['role'], [User::ROLE_ADULT_TEA, User::ROLE_ALLY], true) && $age < 18) {
            return back()->withErrors([
                'birthdate' => __('admin.adult_age_error')
            ])->withInput();
        }

        if ($validated['role'] === User::ROLE_TEEN && ($age < 13 || $age > 17)) {
            return back()->withErrors([
                'birthdate' => __('admin.teen_age_error')
            ])->withInput();
        }

        if ($validated['role'] === User::ROLE_VISITOR && $age <= 12) {
            return back()->withErrors([
                'birthdate' => __('admin.visitor_age_error')
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
                     ->where('role', User::ROLE_ALLY)
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
     * Lista los usuarios con rol Visitante General.
     */
    public function visitors()
    {
        $visitors = User::where('role', User::ROLE_VISITOR)->orderByDesc('created_at')->get();
        $visitorCount = $visitors->count();

        return view('profile.visitors', compact('visitors', 'visitorCount'));
    }

    /**
     * Muestra el detalle de un Visitante General.
     */
    public function showVisitor($id)
    {
        $visitor = User::where('role', User::ROLE_VISITOR)->findOrFail($id);

        return view('profile.visitor-show', compact('visitor'));
    }

    /**
     * Muestra el formulario de edición de un Visitante General.
     */
    public function editVisitor($id)
    {
        $visitor = User::where('role', User::ROLE_VISITOR)->findOrFail($id);

        return view('profile.visitor-edit', compact('visitor'));
    }

    /**
     * Registra un nuevo Visitante General validando que sea mayor de 12 años.
     */
    public function storeVisitor(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'birthdate' => ['required', 'date', 'before:today'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'password.min' => __('admin.pass_min_6'),
            'email.unique' => __('admin.email_unique'),
            'birthdate.before' => __('admin.birthdate_before'),
        ]);

        $age = Carbon::parse($validated['birthdate'])->age;

        if ($age <= 12) {
            return back()->withErrors([
                'birthdate' => __('admin.visitor_age_error')
            ])->withInput();
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'birthdate' => $validated['birthdate'],
            'role' => User::ROLE_VISITOR,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.visitors')->with('success', __('admin.visitor_registered'));
    }

    /**
     * Actualiza los datos de un Visitante General.
     */
    public function updateVisitor(Request $request, $id)
    {
        $visitor = User::where('role', User::ROLE_VISITOR)->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $id],
            'birthdate' => ['required', 'date', 'before:today'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'password.min' => __('admin.pass_min_6'),
            'email.unique' => __('admin.email_unique'),
            'birthdate.before' => __('admin.birthdate_before'),
        ]);

        $age = Carbon::parse($validated['birthdate'])->age;

        if ($age <= 12) {
            return back()->withErrors([
                'birthdate' => __('admin.visitor_age_error')
            ])->withInput();
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'birthdate' => $validated['birthdate'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $visitor->update($data);

        return redirect()->route('admin.visitors')->with('success', __('admin.visitor_updated'));
    }

    /**
     * Elimina un Visitante General.
     */
    public function destroyVisitor($id)
    {
        $visitor = User::where('role', User::ROLE_VISITOR)->findOrFail($id);
        $visitor->delete();

        return redirect()->route('admin.visitors')->with('success', __('admin.visitor_deleted'));
    }

    /**
     * Lista los usuarios con rol Adulto (adult_tea) y Aliado (ally_no_tea).
     */
    public function adults()
    {
        $adults = User::whereIn('role', [User::ROLE_ADULT_TEA, User::ROLE_ALLY])
                      ->orderByDesc('created_at')
                      ->get();

        return view('profile.adults', compact('adults'));
    }

    /**
     * Muestra el detalle de un Adulto / Aliado.
     */
    public function showAdult($id)
    {
        $adult = User::whereIn('role', [User::ROLE_ADULT_TEA, User::ROLE_ALLY])
                     ->findOrFail($id);

        return view('profile.adult-show', compact('adult'));
    }

    /**
     * Muestra el formulario de edición de un Adulto / Aliado.
     */
    public function editAdult($id)
    {
        $adult = User::whereIn('role', [User::ROLE_ADULT_TEA, User::ROLE_ALLY])
                     ->findOrFail($id);

        return view('profile.adult-edit', compact('adult'));
    }

    /**
     * Lista los usuarios con rol Joven / Adolescente (teen).
     */
    public function teens()
    {
        $teens = User::where('role', User::ROLE_TEEN)
                     ->orderByDesc('created_at')
                     ->get();

        return view('profile.teens', compact('teens'));
    }

    /**
     * Muestra el detalle de un Joven / Adolescente.
     */
    public function showTeen($id)
    {
        $teen = User::where('role', User::ROLE_TEEN)->findOrFail($id);

        return view('profile.teen-show', compact('teen'));
    }

    /**
     * Muestra el formulario de edición de un Joven / Adolescente.
     */
    public function editTeen($id)
    {
        $teen = User::where('role', User::ROLE_TEEN)->findOrFail($id);
        $allies = User::where('role', User::ROLE_ALLY)->orderBy('name')->get(['id', 'name']);

        return view('profile.teen-edit', compact('teen', 'allies'));
    }

    /**
     * Lista los perfiles de Niños / Menores (ChildProfile).
     */
    public function minors()
    {
        $minors = ChildProfile::with('user')->orderByDesc('created_at')->get();

        return view('profile.minors', compact('minors'));
    }

    /**
     * Muestra el detalle de un Niño / Menor.
     */
    public function showMinor($id)
    {
        $minor = ChildProfile::with('user')->findOrFail($id);

        return view('profile.minor-show', compact('minor'));
    }

    /**
     * Muestra el formulario de edición de un Niño / Menor.
     */
    public function editMinor($id)
    {
        $minor = ChildProfile::findOrFail($id);
        $allies = User::where('role', User::ROLE_ALLY)->orderBy('name')->get(['id', 'name']);

        return view('profile.minor-edit', compact('minor', 'allies'));
    }

    /**
     * Edición para Adultos y Adolescentes
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->isTeen()) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email,' . $id],
                'supervisor_id' => ['required', 'exists:users,id']
            ]);

            $supervisor = User::where('id', $validated['supervisor_id'])
                              ->where('role', User::ROLE_ALLY)
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

        $route = $user->isTeen() ? 'admin.teens' : 'admin.adults';
        $message = $user->isTeen() ? __('admin.teen_updated') : __('admin.adult_updated');

        return redirect()->route($route)->with('success', $message);
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
                          ->where('role', User::ROLE_ALLY)
                          ->first();

        if (!$supervisor) {
            return back()->withErrors(['supervisor_id' => __('admin.supervisor_invalid')])->withInput();
        }

        $minor->update([
            'name' => $validated['name'],
            'parent_pin' => $validated['parent_pin'],
            'user_id' => $validated['supervisor_id']
        ]);

        return redirect()->route('admin.minors')->with('success', __('admin.minor_updated'));
    }
    
    /**
     * Elimina un usuario.
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        $route = match ($user->role) {
            User::ROLE_TEEN => 'admin.teens',
            User::ROLE_ADULT_TEA, User::ROLE_ALLY => 'admin.adults',
            User::ROLE_VISITOR => 'admin.visitors',
            default => 'admin.dashboard',
        };

        $user->delete();

        return redirect()->route($route)->with('success', __('admin.user_deleted'));
    }

    /**
     * Elimina un perfil de menor.
     */
    public function destroyMinor($id)
    {
        $minor = ChildProfile::findOrFail($id);
        $minor->delete();

        return redirect()->route('admin.minors')->with('success', __('admin.minor_deleted'));
    }
}