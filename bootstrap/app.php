<?php

use App\Http\Middleware\PreventBackHistoryCache;
use App\Http\Middleware\CheckChildSession;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Mantiene tus middlewares globales para el caché y el idioma
        $middleware->appendToGroup('web', PreventBackHistoryCache::class);
        $middleware->appendToGroup('web', SetLocale::class);

        // Registramos los alias para proteger rutas (menor y administrador)
        $middleware->alias([
            'child.auth' => CheckChildSession::class,
            'admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();