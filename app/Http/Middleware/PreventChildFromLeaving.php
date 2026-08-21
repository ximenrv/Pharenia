<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventChildFromLeaving
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('active_child_id')) {
            if ($request->is('actividades/ninez*') || 
                $request->is('games/*') || 
                $request->is('child/logout-confirm*') ) {        
                
                return $next($request);
            }

            // Si no está en esas, lo rebotamos
            return redirect('/actividades/ninez');
        }

        return $next($request);
    }
}
