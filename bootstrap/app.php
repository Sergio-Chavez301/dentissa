<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\UserOnlineStatus;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // Alias para middlewares que invocas manualmente en rutas
        $middleware->alias([
            'prevent-back-history' => PreventBackHistory::class,
        ]);

        // Middleware que se ejecuta automáticamente en todas las rutas web
        $middleware->web(append: [
            UserOnlineStatus::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();