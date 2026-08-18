<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Si el idioma está en la sesión, lo aplicamos
        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        }
        
        return $next($request);
    }
}