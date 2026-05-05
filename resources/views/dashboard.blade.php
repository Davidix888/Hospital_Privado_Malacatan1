@extends('layouts.app', ['title' => 'Dashboard'])

@php
    $labels = [
        'administrador' => 'Administrador',
        'tecnico' => 'Tecnico',
        'farmaceutico' => 'Farmaceutico',
        'licenciado' => 'Licenciado',
    ];
@endphp

@section('content')
<div class="card" style="padding:14px 18px;background:#e7effa;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
        <strong style="color:#1d4f91;letter-spacing:.6px;">VISTA PREVIA DE ROLES (PROTOTIPO)</strong>
        <div class="card" style="padding:4px;display:flex;gap:6px;">
            @foreach (['tecnico', 'licenciado', 'administrador', 'farmaceutico'] as $tabRole)
                <span style="padding:9px 16px;border-radius:9px;{{ $rol === $tabRole ? 'background:#1d4f91;color:#fff;font-weight:700;' : 'color:#1f334f;' }}">
                    {{ $labels[$tabRole] }}
                </span>
            @endforeach
        </div>
    </div>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-top:26px;gap:10px;flex-wrap:wrap;">
    <div>
        <h1 style="font-size:40px;margin:0;">Panel del {{ $labels[$rol] ?? 'Usuario' }}</h1>
        <p style="margin:0;color:#51627c;font-size:20px;">Bienvenido(a), {{ $usuario->nombres }} {{ $usuario->apellidos }}.</p>
        @if (session('status'))
            <div class="alert ok">{{ session('status') }}</div>
        @endif
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-dark">Cerrar sesion</button>
    </form>
</div>

<div style="margin-top:22px;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;">
    @if (in_array('laboratorio', $modulos, true))
        <div class="card" style="padding:20px;">
            <h3 style="font-size:30px;margin:0 0 8px;">Laboratorio</h3>
            <p style="color:#596a83;font-size:19px;">Gestion de pacientes, examenes y seguimiento de estados.</p>
            <a class="btn" href="{{ route('laboratorio') }}" style="display:inline-block;text-decoration:none;">Entrar a Laboratorio</a>
        </div>
    @endif

    @if (in_array('farmacia', $modulos, true))
        <div class="card" style="padding:20px;">
            <h3 style="font-size:30px;margin:0 0 8px;">Farmacia</h3>
            <p style="color:#596a83;font-size:19px;">Inventario, ventas y control de medicamentos.</p>
            <a class="btn btn-dark" href="{{ route('farmacia') }}" style="display:inline-block;text-decoration:none;">Entrar a Farmacia</a>
        </div>
    @endif

    @if (in_array('reportes', $modulos, true))
        <div class="card" style="padding:20px;">
            <h3 style="font-size:30px;margin:0 0 8px;">Reportes</h3>
            <p style="color:#596a83;font-size:19px;">Analisis clinico, consumo y actividad por modulo.</p>
            <a class="btn btn-success" href="{{ route('reportes') }}" style="display:inline-block;text-decoration:none;">Ver Reportes</a>
        </div>
    @endif
</div>
@endsection
