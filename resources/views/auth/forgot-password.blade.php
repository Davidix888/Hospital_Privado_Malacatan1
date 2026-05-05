@extends('layouts.app', ['title' => 'Recuperar contraseña'])

@section('content')
<div class="card" style="max-width:720px;margin:40px auto;padding:26px;">
    <h1 class="title" style="font-size:34px;">Recuperar contraseña</h1>
    <p class="subtitle" style="font-size:18px;">Ingresa tu correo y te enviaremos un enlace para cambiar la contraseña.</p>

    @if (session('status')) <div class="alert ok">{{ session('status') }}</div> @endif
    @if ($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif

    <form method="POST" action="{{ route('password.email') }}" style="margin-top:16px;display:grid;gap:14px;">
        @csrf
        <div>
            <label>Correo electrónico</label>
            <input class="input" style="color:#0f243f;border:1px solid #cbd5e1;border-radius:8px;" type="email" name="correo" value="{{ old('correo') }}" required>
        </div>
        <button class="btn" type="submit">Enviar enlace</button>
    </form>
</div>
@endsection
