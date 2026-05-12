<?php

namespace App\Http\Middleware;

use App\Models\Rol;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RequireModuleAccess
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $role = Rol::normalizeRoleName((string) Session::get('auth_rol', ''));
        $access = Rol::moduleAccessMap();

        if (!in_array($module, $access[$role] ?? [], true)) {
            abort(403, 'No tienes permiso para acceder a este módulo.');
        }

        return $next($request);
    }
}
