@extends('layouts.app', ['title' => 'Recuperar contraseña'])

@section('content')
<div class="card form-shell form-shell-sm">
    <h1 class="title" style="font-size:34px;">Recuperar contraseña</h1>
    <p class="subtitle" style="font-size:18px;">Ingresa tu correo y te enviaremos un enlace para cambiar la contraseña.</p>

    @if (session('status')) <div class="alert ok">{{ session('status') }}</div> @endif
    @if ($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif

    <form method="POST" action="{{ route('password.email') }}" class="form-grid">
        @csrf
        <div class="form-field">
            <label class="form-label">Correo electrónico</label>
            <input class="form-control" type="email" name="correo" value="{{ old('correo') }}" required>
        </div>
        <div class="form-actions">
            <button class="btn" type="submit">Enviar enlace</button>
        </div>
    </form>
</div>
@endsection
