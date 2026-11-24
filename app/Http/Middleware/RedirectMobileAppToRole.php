<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectMobileAppToRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!(bool) $request->session()->get('mobile_app_mode', false)) {
            return $next($request);
        }

        if (!$request->user()) {
            return redirect()->route('login', ['mobile' => 1]);
        }

        $dashboard = match ((int) $request->user()->role_id) {
            1 => 'dashboard.admin',
            2 => 'dashboard.asistente',
            3 => 'dashboard.paciente',
            default => null,
        };

        abort_if($dashboard === null, 403, 'Rol no autorizado.');

        return redirect()->route($dashboard);
    }
}
