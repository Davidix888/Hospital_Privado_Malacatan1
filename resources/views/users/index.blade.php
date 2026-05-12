@extends('layouts.app', ['title' => 'Usuarios'])

@push('styles')
<style>
    .users-page-shell {
        width: 100vw;
        margin-left: calc(50% - 50vw);
        padding: 0 16px;
    }
    .users-page {
        width: min(96vw, 1500px);
        margin-inline: auto;
    }
    .users-page .card {
        padding: 24px;
    }
    .users-header {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        gap: 10px;
        margin-bottom: 0;
    }
    .users-header .header-spacer {
        grid-column: 1;
    }
    .users-header .title {
        grid-column: 2;
        margin: 0;
        font-size: 34px;
        text-align: center;
    }
    .users-header .header-actions {
        grid-column: 3;
        justify-self: end;
    }
    .users-page .table-shell {
        margin-top: 16px;
    }
    .users-page .data-table {
        width: 100%;
        table-layout: auto;
    }
    .users-page .data-table th,
    .users-page .data-table td {
        padding: 12px 10px;
        font-size: 13px;
    }
    .users-page .data-table td {
        color: #1f446f;
    }
    .users-page .data-table th,
    .users-page .data-table td { white-space: normal; word-break: break-word; }
    .action-group {
        display: grid;
        grid-template-columns: repeat(3, minmax(82px, 1fr));
        align-items: center;
        gap: 6px;
        max-width: 300px;
        margin: 0 auto;
    }
    .action-group form {
        margin: 0;
    }
    .action-group .btn {
        width: 100%;
        min-height: 32px;
        padding: 0 6px;
        border-radius: 9px;
        font-size: 11.5px;
        letter-spacing: .2px;
        box-shadow: none;
    }
    .btn-edit {
        background: linear-gradient(90deg, #1c3f6c, #27548c);
    }
    .btn-toggle {
        background: linear-gradient(90deg, #16375d, #214a7a);
    }
    .btn-delete {
        background: linear-gradient(90deg, #7d1f1f, #982828);
    }

    @media (max-width: 900px) {
        .users-header {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        .users-header .title {
            grid-column: 1;
            text-align: left;
            font-size: 30px;
        }
        .users-header .header-actions {
            grid-column: 1;
            justify-self: start;
        }
        .users-header .header-spacer {
            display: none;
        }
    }

    @media (max-width: 560px) {
        .users-page-shell {
            padding: 0 12px;
        }
        .users-page .card {
            padding: 16px;
        }
        .action-group {
            grid-template-columns: 1fr;
            max-width: 180px;
        }
    }
</style>
@endpush

@section('content')
@php
    $roleLabels = [
        'administracion' => 'Administración',
        'administrador' => 'Administrador',
        'farmacia' => 'Farmacia',
        'laboratorio' => 'Laboratorio',
        'reportes' => 'Reportes',
        'tecnico' => 'Laboratorio',
        'farmaceutico' => 'Farmacia',
        'licenciado' => 'Reportes',
    ];

    $safeText = static function ($value): string {
        $text = (string) $value;
        if ($text === '') {
            return '';
        }

        return mb_check_encoding($text, 'UTF-8')
            ? $text
            : mb_convert_encoding($text, 'UTF-8', 'Windows-1252,ISO-8859-1,UTF-8');
    };
@endphp

<div class="users-page-shell">
    <div class="users-page">
        <div class="main-nav" style="margin-bottom:16px;justify-content:center;">
            <a href="{{ route('dashboard') }}">Panel</a>
            <a class="active" href="{{ route('users.index') }}">Usuarios</a>
        </div>

        <div class="card">
            <div class="page-header users-header">
                <div class="header-spacer" aria-hidden="true"></div>
                <h1 class="title">Módulo de Usuarios</h1>
                <div class="header-actions">
                    <a class="btn" href="{{ route('users.create') }}">Crear usuario</a>
                </div>
            </div>

            @if (session('status')) <div class="alert ok">{{ session('status') }}</div> @endif
            @if ($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif

            <div class="table-shell">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $u)
                            <tr>
                                <td>{{ $safeText($u->nombre_usuario) }}</td>
                                <td>{{ $safeText($u->nombres) }} {{ $safeText($u->apellidos) }}</td>
                                <td>{{ $safeText($u->correo) }}</td>
                                <td>{{ $roleLabels[mb_strtolower((string) ($u->rol->nombre_rol ?? ''))] ?? $safeText($u->rol->nombre_rol ?? 'Sin rol') }}</td>
                                <td>{{ $u->activo ? 'Activo' : 'Inactivo' }}</td>
                                <td>
                                    <div class="action-group">
                                        <a class="btn btn-sm btn-edit" href="{{ route('users.edit', $u->id_usuario) }}">Editar</a>
                                        <form method="POST" action="{{ route('users.toggle', $u->id_usuario) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-toggle" type="submit">{{ $u->activo ? 'Desactivar' : 'Activar' }}</button>
                                        </form>
                                        <form method="POST" action="{{ route('users.destroy', $u->id_usuario) }}" onsubmit="return confirm('¿Deseas eliminar este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-delete" type="submit">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding:14px;color:#51627c;text-align:center;border-top:1px solid #dbe3ef;">No hay usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

