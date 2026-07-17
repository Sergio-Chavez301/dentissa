<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UserOnlineStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // El usuario estará "en línea" por 5 minutos después de su última actividad
            $expiresAt = now()->addMinutes(5);
            Cache::put('user-is-online-' . Auth::user()->id, true, $expiresAt);
        }

        return $next($request);
    }
}