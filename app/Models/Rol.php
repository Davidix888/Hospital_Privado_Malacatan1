<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    public static function normalizeRoleName(?string $roleName): string
    {
        $base = Str::of((string) $roleName)->lower()->ascii()->replace(' ', '')->value();

        $aliases = [
            'admin' => 'administrador',
            'administrativo' => 'administracion',
            'farmaceutico' => 'farmacia',
            'farmaceutica' => 'farmacia',
            'tecnico' => 'laboratorio',
            'tecnicolaboratorio' => 'laboratorio',
            'licenciado' => 'reportes',
            'reporteria' => 'reportes',
        ];

        return $aliases[$base] ?? $base;
    }

    public static function moduleAccessMap(): array
    {
        return [
            'administrador' => ['farmacia', 'laboratorio', 'reportes'],
            'administracion' => ['farmacia', 'laboratorio', 'reportes'],
            'farmacia' => ['farmacia'],
            'laboratorio' => ['laboratorio'],
            'reportes' => ['farmacia', 'laboratorio', 'reportes'],
        ];
    }
}
