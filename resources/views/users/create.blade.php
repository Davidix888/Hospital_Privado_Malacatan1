@extends('layouts.app', ['title' => 'Crear usuario'])

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
@endphp

<div class="card form-shell">
    <h1 class="title" style="margin:0 0 10px;font-size:34px;">Crear usuario</h1>

    @if ($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif

    <form method="POST" action="{{ route('users.store') }}" class="form-grid">
        @csrf
        <div class="form-field">
            <label class="form-label">Nombres</label>
            <input class="form-control" name="nombres" value="{{ old('nombres') }}" required>
        </div>
        <div class="form-field">
            <label class="form-label">Apellidos</label>
            <input class="form-control" name="apellidos" value="{{ old('apellidos') }}" required>
        </div>
        <div class="form-field">
            <label class="form-label">Correo electrónico</label>
            <input class="form-control" type="email" name="correo" value="{{ old('correo') }}" required>
        </div>
        <div class="form-field">
            <label class="form-label">Rol</label>
            <select class="form-control" name="id_rol" required>
                <option value="">Selecciona rol</option>
                @foreach ($roles as $rol)
                    @php
                        $rawRole = (string) ($rol->nombre_rol ?? '');
                        $roleKey = mb_strtolower($rawRole);
                    @endphp
                    <option value="{{ $rol->id_rol }}" {{ old('id_rol') == $rol->id_rol ? 'selected' : '' }}>
                        {{ $roleLabels[$roleKey] ?? $rawRole }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-field">
            <label class="form-label">Contraseña temporal</label>
            <input class="form-control" type="password" name="contrasena" required>
        </div>
        <label class="checkbox-row">
            <input type="checkbox" name="activo" value="1" checked>
            <span>Usuario activo</span>
        </label>

        <small class="form-hint">El nombre de usuario se genera automáticamente con inicial del primer nombre + inicial del segundo nombre + apellido. Ej: ldixquiac, ldixquiac2...</small>

        <div class="form-actions">
            <button class="btn" type="submit">Guardar usuario</button>
            <a class="btn btn-dark" href="{{ route('users.index') }}">Cancelar</a>
        </div>
    </form>
</div>
@endsection

