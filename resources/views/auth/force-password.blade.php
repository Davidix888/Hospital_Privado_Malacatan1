@extends('layouts.app', ['title' => 'Cambio de contraseña'])

@section('content')
<div class="card form-shell form-shell-sm">
    <p class="security-kicker">SEGURIDAD DE CUENTA</p>
    <h1 class="title" style="font-size:34px;">Debes cambiar tu contraseña</h1>
    <p class="subtitle" style="font-size:18px;">Por política de seguridad, la contraseña debe renovarse cada 3 meses.</p>

    @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="form-grid">
        @csrf
        <div class="form-field">
            <label class="form-label">Correo electrónico</label>
            <input class="form-control" type="email" name="correo" value="{{ old('correo', $correo ?? '') }}" required>
        </div>
        <div class="form-field">
            <label class="form-label">Nueva contraseña</label>
            <input class="form-control" type="password" name="password" minlength="8" required>
        </div>
        <div class="form-field">
            <label class="form-label">Confirmar contraseña</label>
            <input class="form-control" type="password" name="password_confirmation" minlength="8" required>
        </div>
        <div class="form-actions">
            <button class="btn btn-dark" type="submit">Actualizar contraseña</button>
        </div>
    </form>
</div>
@endsection
