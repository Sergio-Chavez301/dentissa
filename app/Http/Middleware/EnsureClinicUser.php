<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClinicUser
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(in_array((int) $request->user()?->role_id, [1, 2, 3], true), 403);

        return $next($request);
    }
}
