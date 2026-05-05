@extends('layouts.app', ['title' => 'Editar usuario'])

@section('content')
<div class="card" style="max-width:780px;margin:20px auto;padding:24px;">
    <h1 style="margin:0 0 10px;font-size:34px;">Editar usuario</h1>

    @if ($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif

    <form method="POST" action="{{ route('users.update', $usuario->id_usuario) }}" style="display:grid;gap:12px;">
        @csrf
        @method('PUT')

        <div>
            <label>Username actual</label>
            <input class="input" style="color:#0f243f;border:1px solid #cbd5e1;border-radius:8px;background:#f8fafc;" value="{{ $usuario->nombre_usuario }}" disabled>
            <small>Si cambias nombres/apellidos, se regenera automáticamente.</small>
        </div>

        <div>
            <label>Nombres</label>
            <input class="input" style="color:#0f243f;border:1px solid #cbd5e1;border-radius:8px;" name="nombres" value="{{ old('nombres', $usuario->nombres) }}" required>
        </div>
        <div>
            <label>Apellidos</label>
            <input class="input" style="color:#0f243f;border:1px solid #cbd5e1;border-radius:8px;" name="apellidos" value="{{ old('apellidos', $usuario->apellidos) }}" required>
        </div>
        <div>
            <label>Correo electrónico</label>
            <input class="input" style="color:#0f243f;border:1px solid #cbd5e1;border-radius:8px;" type="email" name="correo" value="{{ old('correo', $usuario->correo) }}" required>
        </div>
        <div>
            <label>Rol</label>
            <select class="input" style="color:#0f243f;border:1px solid #cbd5e1;border-radius:8px;" name="id_rol" required>
                <option value="">Selecciona rol</option>
                @foreach ($roles as $rol)
                    <option value="{{ $rol->id_rol }}" {{ (int) old('id_rol', $usuario->id_rol) === (int) $rol->id_rol ? 'selected' : '' }}>{{ $rol->nombre_rol }}</option>
                @endforeach
            </select>
        </div>

        <label style="display:inline-flex;align-items:center;gap:8px;">
            <input type="checkbox" name="activo" value="1" {{ old('activo', $usuario->activo) ? 'checked' : '' }}>
            <span>Usuario activo</span>
        </label>

        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <button class="btn" type="submit">Guardar cambios</button>
            <a class="btn btn-dark" href="{{ route('users.index') }}" >Cancelar</a>
        </div>
    </form>
</div>
@endsection

