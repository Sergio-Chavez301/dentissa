<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\EnsureAdministrator;
use App\Http\Middleware\EnsureClinicUser;
use App\Http\Middleware\EnsurePatient;
use App\Http\Middleware\EnsureStaff;
use App\Http\Middleware\UserOnlineStatus;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // Alias para middlewares que invocas manualmente en rutas
        $middleware->alias([
            'prevent-back-history' => PreventBackHistory::class,
            'admin' => EnsureAdministrator::class,
            'clinic-user' => EnsureClinicUser::class,
            'staff' => EnsureStaff::class,
            'patient' => EnsurePatient::class,
        ]);

        // Middleware que se ejecuta automáticamente en todas las rutas web
        $middleware->web(append: [
            UserOnlineStatus::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $exception, Request $request) {
            if ($request->expectsJson()
                || !$exception instanceof HttpExceptionInterface
                || $exception->getStatusCode() !== 403) {
                return null;
            }

            $dashboardRoute = match ((int) $request->user()?->role_id) {
                1 => route('dashboard.admin'),
                2 => route('dashboard.asistente'),
                3 => route('dashboard.paciente'),
                default => route('home'),
            };

            return response()->view('errors.403', compact('dashboardRoute'), 403);
        });
    })->create();