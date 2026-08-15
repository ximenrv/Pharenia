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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'birthdate' => ['required', 'date', 'before:today'],
            'role' => ['required', 'string', 'in:adult_tea,ally_no_tea,teen'],
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
        $role = $validated['role']; 

        // 🛡️ BLOQUEO ESTRICTO POR EDAD Y PERFIL INCORRECTO

        // 1. Menores de 13 años (prohibido registro independiente)
        if ($age < 13) {
            return back()->withErrors([
                'birthdate' => 'Los menores de 13 años no pueden registrarse de forma independiente. Deben ser registrados por un adulto responsable desde el panel familiar.'
            ])->withInput();
        }

        // 2. Adolescentes (13 a 17 años): SOLO pueden ser 'teen'
        if ($age >= 13 && $age <= 17) {
            if ($role !== 'teen') {
                return back()->withErrors([
                    'role' => 'Tu fecha de nacimiento indica que eres menor de edad (13-17 años). Debes seleccionar el perfil de Joven / Adolescente.'
                ])->withInput();
            }
        } 
        // 3. Adultos (18 años o más): NO pueden ser 'teen'
        else {
            if ($role === 'teen') {
                return back()->withErrors([
                    'role' => 'No puedes seleccionar el perfil de adolescente si eres mayor de edad (18+).'
                ])->withInput();
            }
        }

        // Si pasa todas las validaciones, creamos el usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'birthdate' => $validated['birthdate'],
            'role' => $role,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

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

        // Obtenemos al usuario autenticado
        $user = Auth::user();

        // Redirección inteligente basada en el rol
        if ($user->role === 'admin') {
            return redirect()->intended('home'); 
        }

        // Para los demás usuarios (adult_tea, ally_no_tea, teen, minor)
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
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}