@extends('layouts.app', ['title' => 'Cambio de contrasena'])

@section('content')
<div class="card" style="max-width:720px;margin:50px auto;padding:26px;">
    <p style="color:#b45309;font-weight:800;">SEGURIDAD DE CUENTA</p>
    <h1 class="title" style="font-size:34px;">Debes cambiar tu contrasena</h1>
    <p class="subtitle" style="font-size:18px;">Por politica de seguridad, la contrasena debe renovarse cada 3 meses.</p>

    @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" style="margin-top:16px;display:grid;gap:14px;">
        @csrf
        <div>
            <label>Correo electronico</label>
            <input class="input" type="email" name="correo" value="{{ old('correo', $correo ?? '') }}" required>
        </div>
        <div>
            <label>Nueva contrasena</label>
            <input class="input" type="password" name="password" minlength="8" required>
        </div>
        <div>
            <label>Confirmar contrasena</label>
            <input class="input" type="password" name="password_confirmation" minlength="8" required>
        </div>
        <button class="btn btn-dark" type="submit">Actualizar contrasena</button>
    </form>
</div>
@endsection
