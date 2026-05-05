@extends('layouts.app', ['title' => 'Login'])

@push('styles')
<style>
    .login-wrap { min-height: calc(100dvh - 320px); display: grid; place-items: center; padding: 86px 0 18px; }
    .login-card { width: min(430px, 100%); background: linear-gradient(160deg, #1a3558, #11233d); border-radius: 0; border: 1px solid rgba(209, 225, 245, .45); padding: 76px 28px 28px; position: relative; box-shadow: 0 20px 42px rgba(10, 24, 43, .28); }
    .avatar-badge { width: 134px; height: 134px; border-radius: 999px; background: #0f2b4d; position: absolute; left: 50%; transform: translateX(-50%); top: -67px; display: grid; place-items: center; box-shadow: 0 10px 30px rgba(0, 0, 0, .25); }
    .avatar-badge img { width: 66px; height: 66px; object-fit: cover; border-radius: 50%; border: 2px solid rgba(255,255,255,.8); }
    .field { margin-top: 18px; }
    .field label { color: #dce9f8; font-size: 20px; display: block; margin-bottom: 3px; }
    .login-card .input { border-bottom-color: rgba(220, 233, 248, .65); color: #f3f8ff; }
    .login-card .input::placeholder { color: rgba(220, 233, 248, .72); }
    .login-card .input:focus { border-bottom-color: #ffffff; }
    .login-meta { margin-top: 18px; color: #c9daee; font-size: 14px; display: flex; justify-content: space-between; gap: 8px; align-items: center; }
    .remember-wrap { display: inline-flex; align-items: center; gap: 8px; }
    .remember-wrap input[type="checkbox"] { accent-color: #dce9f8; width: 14px; height: 14px; }
    .link-reset { color: #d5e9ff; text-decoration: underline; }
    .login-btn { width: 100%; margin-top: 18px; border-radius: 0; letter-spacing: 1px; font-size: 18px; padding-block: 13px; background: linear-gradient(90deg, #0f2b4d, #1a3558); border: 1px solid rgba(220, 233, 248, .35); }
</style>
@endpush

@section('content')
<div class="login-wrap">
    <div class="login-card">
        <div class="avatar-badge">
            <img src="{{ asset('Hospital_logo.jpeg') }}" alt="Avatar">
        </div>

        @if (session('status')) <div class="alert ok">{{ session('status') }}</div> @endif
        @if ($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="field">
                <label>Usuario</label>
                <input class="input" name="usuario" value="{{ old('usuario', request()->cookie('remembered_usuario')) }}" placeholder="Ej: ldixquiac" required>
            </div>
            <div class="field">
                <label>Contraseña</label>
                <input class="input" type="password" name="password" placeholder="********" required>
            </div>

            <div class="login-meta">
                <label class="remember-wrap">
                    <input type="checkbox" name="remember" value="1" {{ request()->cookie('remembered_usuario') ? 'checked' : '' }}>
                    <span>Recordarme</span>
                </label>
                <a class="link-reset" href="{{ route('password.request') }}">¿Olvidó su contraseña?</a>
            </div>

            <button class="btn login-btn" type="submit">LOGIN</button>
        </form>
    </div>
</div>
@endsection
