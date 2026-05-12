<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $usuario = Usuario::with('rol')->findOrFail(Session::get('auth_usuario_id'));
        $rol = Rol::normalizeRoleName((string) optional($usuario->rol)->nombre_rol);
        $access = Rol::moduleAccessMap();

        return view('dashboard', [
            'usuario' => $usuario,
            'rol' => $rol,
            'modulos' => $access[$rol] ?? [],
        ]);
    }
}
