<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveMobilePatient
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || (int) $user->role_id !== 3 || !$user->activo) {
            return response()->json([
                'message' => 'La cuenta no tiene acceso activo a la aplicación.',
            ], 403)->header('Cache-Control', 'no-store');
        }

        return $next($request);
    }
}
