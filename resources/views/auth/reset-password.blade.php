@extends('layouts.app', ['title' => 'Restablecer contraseña'])

@section('content')
<div class="card" style="max-width:720px;margin:40px auto;padding:26px;">
    <h1 class="title" style="font-size:34px;">Restablecer contraseña</h1>

    @if ($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif

    <form method="POST" action="{{ route('password.reset.update') }}" style="margin-top:16px;display:grid;gap:14px;">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label>Correo electrónico</label>
            <input class="input" style="color:#0f243f;border:1px solid #cbd5e1;border-radius:8px;" type="email" name="email" value="{{ old('email', $email) }}" required>
        </div>
        <div>
            <label>Nueva contraseña</label>
            <input class="input" style="color:#0f243f;border:1px solid #cbd5e1;border-radius:8px;" type="password" name="password" minlength="8" required>
        </div>
        <div>
            <label>Confirmar contraseña</label>
            <input class="input" style="color:#0f243f;border:1px solid #cbd5e1;border-radius:8px;" type="password" name="password_confirmation" minlength="8" required>
        </div>
        <button class="btn" type="submit">Actualizar contraseña</button>
    </form>
</div>
@endsection
