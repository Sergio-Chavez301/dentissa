<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Models\SolicitudCita;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 0. Forzar HTTPS en entorno de producción (Railway) para evitar errores de contenido mixto
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // 1. Lógica de redirección para usuarios autenticados
        RedirectIfAuthenticated::redirectUsing(function () {
            $user = Auth::user();
            
            if ($user) {
                switch ($user->role_id) {
                    case 1:
                        return route('dashboard.admin');
                    case 2:
                        return route('dashboard.asistente');
                    case 3:
                        return route('dashboard.paciente');
                }
            }
            
            return '/';
        });

        // 2. Lógica para compartir el conteo de solicitudes en todas las vistas
        View::composer('*', function ($view) {
            // Asegúrate de que el usuario esté logueado para no hacer consultas innecesarias
            if (Auth::check()) {
                $nuevasSolicitudes = SolicitudCita::where('estado', 'esperando_confirmacion')->count();
                $view->with('nuevasSolicitudes', $nuevasSolicitudes);
            } else {
                $view->with('nuevasSolicitudes', 0);
            }
        });
    }
}