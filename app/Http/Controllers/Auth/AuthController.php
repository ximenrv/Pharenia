<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'birthdate' => ['required', 'date', 'before:today'],
            'role' => ['required', 'string', 'in:adult_tea,ally_no_tea'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
                'regex:/^\S*$/u',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'password.regex' => 'La contraseña no debe contener espacios en blanco.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'role.required' => 'Debe seleccionar un objetivo de perfil.',
        ]);

        $age = Carbon::parse($validated['birthdate'])->age;

        // Por defecto toma la elección del adulto (+18)
        $role = $validated['role']; 

        // Validación y desvío automático por rangos de edad
        if ($age >= 13 && $age <= 17) {
            $role = 'teen'; 
        } elseif ($age < 13) {
            return back()->withErrors([
                'birthdate' => 'Los menores de 13 años no pueden registrarse de forma independiente. Deben ser registrados por un adulto responsable desde el panel familiar.'
            ])->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'birthdate' => $validated['birthdate'],
            'role' => $role,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // Redirección inteligente según el rol final asignado
        if ($user->role === 'teen') {
            return redirect()->route('teen.dashboard');
        }

        return redirect()->route('home');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Si es adolescente al iniciar sesión, va directo a su espacio protegido
        if ($user->role === 'teen') {
            return redirect()->route('teen.dashboard');
        }

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function showRegistrationForm()
    {
        return view('register');
    }
}