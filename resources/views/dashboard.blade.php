@extends('layouts.app', ['title' => 'Dashboard'])

@php
    $labels = [
        'administrador' => 'Administrador',
        'administracion' => 'Administración',
        'tecnico' => 'Técnico',
        'farmaceutico' => 'Farmacéutico',
        'licenciado' => 'Licenciado',
    ];
@endphp

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:14px;">
    <div style="flex:1;text-align:center;">
        <h1 style="font-size:40px;margin:0;">Panel del {{ $labels[$rol] ?? 'Usuario' }}</h1>
        <p style="margin:0;color:#51627c;font-size:20px;">Bienvenido(a), {{ $usuario->nombres }} {{ $usuario->apellidos }}.</p>
        @if (session('status'))
            <div class="alert ok">{{ session('status') }}</div>
        @endif
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-dark" type="submit">Cerrar sesión</button>
    </form>
</div>

<div class="main-nav" style="margin-bottom:16px;justify-content:center;">
    <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Panel</a>
    @if ($rol === 'administrador' || $rol === 'administracion')
        <a class="{{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">Usuarios</a>
    @endif
    @if (in_array('laboratorio', $modulos, true))
        <a class="{{ request()->routeIs('laboratorio') ? 'active' : '' }}" href="{{ route('laboratorio') }}">Laboratorio</a>
    @endif
    @if (in_array('farmacia', $modulos, true))
        <a class="{{ request()->routeIs('farmacia') ? 'active' : '' }}" href="{{ route('farmacia') }}">Farmacia</a>
    @endif
    @if (in_array('reportes', $modulos, true))
        <a class="{{ request()->routeIs('reportes') ? 'active' : '' }}" href="{{ route('reportes') }}">Reportes</a>
    @endif
</div>

<div class="card" style="padding:14px 18px;background:#e7effa;text-align:center;">
    <strong style="color:#1d4f91;letter-spacing:.6px;">ROL ACTIVO: {{ $labels[$rol] ?? 'Usuario' }}</strong>
</div>

<div class="panel-grid {{ ($rol === 'administrador' || $rol === 'administracion') ? 'admin-grid' : '' }}">
    @if ($rol === 'administrador' || $rol === 'administracion')
        <div class="card module-card">
            <div>
                <h3>Usuarios</h3>
                <p>Alta, edición, activación, desactivación y eliminación de cuentas.</p>
            </div>
            <a class="btn" href="{{ route('users.index') }}">Entrar a Usuarios</a>
        </div>
    @endif

    @if (in_array('laboratorio', $modulos, true))
        <div class="card module-card">
            <div>
                <h3>Laboratorio</h3>
                <p>Gestión de pacientes, exámenes y seguimiento de estados.</p>
            </div>
            <a class="btn" href="{{ route('laboratorio') }}">Entrar a Laboratorio</a>
        </div>
    @endif

    @if (in_array('farmacia', $modulos, true))
        <div class="card module-card">
            <div>
                <h3>Farmacia</h3>
                <p>Inventario, ventas y control de medicamentos.</p>
            </div>
            <a class="btn" href="{{ route('farmacia') }}">Entrar a Farmacia</a>
        </div>
    @endif

    @if (in_array('reportes', $modulos, true))
        <div class="card module-card">
            <div>
                <h3>Reportes</h3>
                <p>Análisis clínico, consumo y actividad por módulo.</p>
            </div>
            <a class="btn" href="{{ route('reportes') }}">Ver Reportes</a>
        </div>
    @endif
</div>

@if ($rol === 'administrador' || $rol === 'administracion')
@push('styles')
<style>
    .admin-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    @media (max-width: 900px) {
        .admin-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
@endif
@endsection
