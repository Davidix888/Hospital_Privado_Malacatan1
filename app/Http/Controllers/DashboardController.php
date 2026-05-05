<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $usuario = Usuario::with('rol')->findOrFail(Session::get('auth_usuario_id'));
        $rol = Str::of((string) optional($usuario->rol)->nombre_rol)->lower()->ascii()->replace(' ', '')->value();

        $access = [
            'administrador' => ['farmacia', 'laboratorio', 'reportes'],
            'administracion' => ['farmacia', 'laboratorio', 'reportes'],
            'tecnico' => ['laboratorio'],
            'farmaceutico' => ['farmacia'],
            'licenciado' => ['farmacia', 'laboratorio', 'reportes'],
        ];

        return view('dashboard', [
            'usuario' => $usuario,
            'rol' => $rol,
            'modulos' => $access[$rol] ?? [],
        ]);
    }
}
