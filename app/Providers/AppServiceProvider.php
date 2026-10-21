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
    public function register(): void
    {
    }

    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

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

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $nuevasSolicitudes = SolicitudCita::where('estado', 'esperando_confirmacion')->count();
                $view->with('nuevasSolicitudes', $nuevasSolicitudes);
            } else {
                $view->with('nuevasSolicitudes', 0);
            }
        });
    }
}