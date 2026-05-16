<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Usuario extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre_usuario',
        'correo',
        'contrasena',
        'id_rol',
        'nombres',
        'apellidos',
        'activo',
        'password_changed_at',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'password_changed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $usuario): void {
            if (!empty($usuario->nombre_usuario)) {
                return;
            }

            $usuario->nombre_usuario = self::buildUniqueUsername(
                (string) $usuario->nombres,
                (string) $usuario->apellidos
            );
        });
    }

    public static function buildUniqueUsername(string $nombres, string $apellidos): string
    {
        $nameParts = Str::of(trim($nombres))->explode(' ')->filter()->values();
        $firstName = (string) $nameParts->get(0, '');
        $secondName = (string) $nameParts->get(1, '');
        $firstSurname = (string) (Str::of(trim($apellidos))->explode(' ')->filter()->first() ?? '');

        $firstNameNorm = (string) Str::of($firstName)->ascii()->lower()->replaceMatches('/[^a-z0-9]/', '');
        $secondNameNorm = (string) Str::of($secondName)->ascii()->lower()->replaceMatches('/[^a-z0-9]/', '');
        $surnameNorm = (string) Str::of($firstSurname)->ascii()->lower()->replaceMatches('/[^a-z0-9]/', '');

        $secondInitial = $secondNameNorm !== '' ? Str::substr($secondNameNorm, 0, 1) : '';

        // Regla principal: usar iniciales y, si ya existe, ir agregando
        // letras del primer nombre hasta encontrar un username libre.
        if ($firstNameNorm !== '') {
            $firstNameLen = Str::length($firstNameNorm);
            for ($i = 1; $i <= $firstNameLen; $i++) {
                $prefix = Str::substr($firstNameNorm, 0, $i).$secondInitial;
                $candidate = (string) Str::of($prefix.$surnameNorm)->replaceMatches('/[^a-z0-9]/', '');
                if ($candidate !== '' && !self::where('nombre_usuario', $candidate)->exists()) {
                    return $candidate;
                }
            }
        }

        $base = (string) Str::of($firstNameNorm.$secondNameNorm.$surnameNorm)->replaceMatches('/[^a-z0-9]/', '');
        if ($base === '') {
            $base = 'usuario';
        }

        $candidate = $base;
        $counter = 2;
        while (self::where('nombre_usuario', $candidate)->exists()) {
            $candidate = $base.$counter;
            $counter++;
        }

        return $candidate;
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }
}
