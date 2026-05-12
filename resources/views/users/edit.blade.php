@extends('layouts.app', ['title' => 'Editar usuario'])

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
    <h1 class="title" style="margin:0 0 10px;font-size:34px;">Editar usuario</h1>

    @if ($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif

    <form method="POST" action="{{ route('users.update', $usuario->id_usuario) }}" class="form-grid">
        @csrf
        @method('PUT')

        <div class="form-field">
            <label class="form-label">Username actual</label>
            <input class="form-control" value="{{ $usuario->nombre_usuario }}" disabled>
            <small class="form-hint">Si cambias nombres/apellidos, se regenera automáticamente.</small>
        </div>

        <div class="form-field">
            <label class="form-label">Nombres</label>
            <input class="form-control" name="nombres" value="{{ old('nombres', $usuario->nombres) }}" required>
        </div>
        <div class="form-field">
            <label class="form-label">Apellidos</label>
            <input class="form-control" name="apellidos" value="{{ old('apellidos', $usuario->apellidos) }}" required>
        </div>
        <div class="form-field">
            <label class="form-label">Correo electrónico</label>
            <input class="form-control" type="email" name="correo" value="{{ old('correo', $usuario->correo) }}" required>
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
                    <option value="{{ $rol->id_rol }}" {{ (int) old('id_rol', $usuario->id_rol) === (int) $rol->id_rol ? 'selected' : '' }}>
                        {{ $roleLabels[$roleKey] ?? $rawRole }}
                    </option>
                @endforeach
            </select>
        </div>

        <label class="checkbox-row">
            <input type="checkbox" name="activo" value="1" {{ old('activo', $usuario->activo) ? 'checked' : '' }}>
            <span>Usuario activo</span>
        </label>

        <div class="form-actions">
            <button class="btn" type="submit">Guardar cambios</button>
            <a class="btn btn-dark" href="{{ route('users.index') }}">Cancelar</a>
        </div>
    </form>
</div>
@endsection
