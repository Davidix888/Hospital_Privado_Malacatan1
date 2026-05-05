<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RequireLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('auth_usuario_id')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}

