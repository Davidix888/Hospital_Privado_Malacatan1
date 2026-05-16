@extends('layouts.app', ['title' => 'Restablecer contraseña'])

@section('content')
<div class="card form-shell form-shell-sm">
    <h1 class="title" style="font-size:34px;">Restablecer contraseña</h1>

    @if ($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif

    <form method="POST" action="{{ route('password.reset.update') }}" class="form-grid">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-field">
            <label class="form-label">Correo electrónico</label>
            <input class="form-control" type="email" name="email" value="{{ old('email', $email) }}" required>
        </div>
        <div class="form-field">
            <label class="form-label">Nueva contraseña</label>
            <input class="form-control" type="password" name="password" minlength="6" required>
        </div>
        <div class="form-field">
            <label class="form-label">Confirmar contraseña</label>
            <input class="form-control" type="password" name="password_confirmation" minlength="6" required>
        </div>
        <div class="form-actions">
            <button class="btn" type="submit">Actualizar contraseña</button>
        </div>
    </form>
</div>
@endsection
