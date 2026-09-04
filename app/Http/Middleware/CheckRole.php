<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Si la ruta requiere estar autenticado y no hay sesión
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta sección.');
        }

        $user = auth()->user();

        // 2. Si el rol del usuario no está dentro de los permitidos para esta ruta
        if (!$user->hasRole(...$roles)) {
            return redirect()->route('home')
                ->with('error', 'No tienes autorización para acceder a esta sección.');
        }

        return $next($request);
    }
}