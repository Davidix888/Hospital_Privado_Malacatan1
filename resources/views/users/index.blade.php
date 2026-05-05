@extends('layouts.app', ['title' => 'Usuarios'])

@push('styles')
<style>
    .action-group {
        display: grid;
        grid-template-columns: repeat(3, minmax(94px, 1fr));
        gap: 8px;
        max-width: 340px;
        margin: 0 auto;
        align-items: center;
    }
    .action-group form {
        margin: 0;
    }
    .action-group .btn {
        width: 100%;
        min-height: 36px;
        padding: 0 10px;
        border-radius: 9px;
        font-size: 13px;
        letter-spacing: .2px;
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
    @media (max-width: 560px) {
        .action-group {
            grid-template-columns: 1fr;
            max-width: 200px;
        }
    }
</style>
@endpush

@section('content')
<div class="main-nav" style="margin-bottom:16px;justify-content:center;">
    <a href="{{ route('dashboard') }}">Panel</a>
    <a class="active" href="{{ route('users.index') }}">Usuarios</a>
</div>

<div class="card" style="padding:20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
        <h1 style="margin:0;font-size:34px;text-align:center;flex:1;">Módulo de Usuarios</h1>
        <a class="btn" href="{{ route('users.create') }}">Crear usuario</a>
    </div>

    @if (session('status')) <div class="alert ok">{{ session('status') }}</div> @endif
    @if ($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif

    <div style="overflow:auto;margin-top:12px;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#eef3fa;">
                    <th style="text-align:center;padding:10px;vertical-align:middle;">Usuario</th>
                    <th style="text-align:center;padding:10px;vertical-align:middle;">Nombre</th>
                    <th style="text-align:center;padding:10px;vertical-align:middle;">Correo</th>
                    <th style="text-align:center;padding:10px;vertical-align:middle;">Rol</th>
                    <th style="text-align:center;padding:10px;vertical-align:middle;">Estado</th>
                    <th style="text-align:center;padding:10px;vertical-align:middle;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usuarios as $u)
                    <tr style="border-top:1px solid #dbe3ef;">
                        <td style="padding:10px;text-align:center;vertical-align:middle;">{{ $u->nombre_usuario }}</td>
                        <td style="padding:10px;text-align:center;vertical-align:middle;">{{ $u->nombres }} {{ $u->apellidos }}</td>
                        <td style="padding:10px;text-align:center;vertical-align:middle;">{{ $u->correo }}</td>
                        <td style="padding:10px;text-align:center;vertical-align:middle;">{{ $u->rol->nombre_rol ?? 'Sin rol' }}</td>
                        <td style="padding:10px;text-align:center;vertical-align:middle;">{{ $u->activo ? 'Activo' : 'Inactivo' }}</td>
                        <td style="padding:10px;text-align:center;vertical-align:middle;">
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
                        <td colspan="6" style="padding:14px;color:#51627c;text-align:center;">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
