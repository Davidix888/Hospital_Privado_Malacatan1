<?php

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:create-initial-user', function () {
    $roles = Rol::orderBy('id_rol')->get();

    if ($roles->isEmpty()) {
        $this->error('No hay roles en la tabla rol. Crea primero los roles.');
        return;
    }

    $nombres = $this->ask('Nombres');
    $apellidos = $this->ask('Apellidos');
    $correo = $this->ask('Correo electronico');
    $password = $this->secret('Contrasena inicial');

    $this->info('Roles disponibles:');
    foreach ($roles as $rol) {
        $this->line("{$rol->id_rol} - {$rol->nombre_rol}");
    }

    $idRol = (int) $this->ask('ID de rol');

    $usuario = new Usuario();
    $usuario->nombres = $nombres;
    $usuario->apellidos = $apellidos;
    $usuario->correo = mb_strtolower(trim((string) $correo));
    $usuario->contrasena = Hash::make((string) $password);
    $usuario->id_rol = $idRol;
    $usuario->activo = true;
    $usuario->password_changed_at = now();
    $usuario->save();

    $this->info('Usuario creado correctamente.');
    $this->line('Username generado: '.$usuario->nombre_usuario);
})->purpose('Crea un usuario inicial con username automatico');
