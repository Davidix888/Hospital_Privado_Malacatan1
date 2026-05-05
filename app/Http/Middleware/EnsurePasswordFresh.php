<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordFresh
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuarioId = Session::get('auth_usuario_id');
        $usuario = Usuario::find($usuarioId);

        if (!$usuario) {
            $request->session()->invalidate();
            return redirect()->route('login');
        }

        if (
            !$usuario->password_changed_at ||
            Carbon::parse($usuario->password_changed_at)->addMonths(3)->isPast()
        ) {
            if (!$request->routeIs('password.force') && !$request->routeIs('password.update')) {
                return redirect()->route('password.force');
            }
        }

        return $next($request);
    }
}

