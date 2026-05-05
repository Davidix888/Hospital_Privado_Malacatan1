<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RequireModuleAccess
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $role = Session::get('auth_rol', '');

        $access = [
            'administrador' => ['farmacia', 'laboratorio', 'reportes'],
            'tecnico' => ['laboratorio'],
            'farmaceutico' => ['farmacia'],
            'licenciado' => ['farmacia', 'laboratorio', 'reportes'],
        ];

        if (!in_array($module, $access[$role] ?? [], true)) {
            abort(403, 'No tienes permiso para acceder a este módulo.');
        }

        return $next($request);
    }
}

