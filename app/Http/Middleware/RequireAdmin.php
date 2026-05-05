<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RequireAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array(Session::get('auth_rol'), ['administrador', 'administracion'], true)) {
            abort(403, 'Solo el administrador puede realizar esta acción.');
        }

        return $next($request);
    }
}
