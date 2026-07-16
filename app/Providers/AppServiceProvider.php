<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;


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
        // Si un usuario ya logueado intenta ir a /login, lo mandamos al dashboard
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
    }
} 