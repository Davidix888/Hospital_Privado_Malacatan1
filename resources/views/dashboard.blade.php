@extends('layouts.app', ['title' => 'Dashboard'])

@php
    $labels = [
        'administrador' => 'Administrador',
        'administracion' => 'Administracion',
        'farmacia' => 'Farmacia',
        'laboratorio' => 'Laboratorio',
        'reportes' => 'Reportes',
    ];
@endphp

@section('topbar_actions')
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="btn btn-dark" type="submit">Cerrar sesion</button>
</form>
@endsection

@section('content')
<div class="page-header dashboard-header">
    <div class="page-header-center">
        <h1 class="title" style="font-size:40px;margin:0;">
            {{ in_array($rol, ['administrador', 'administracion'], true) ? 'Panel Administrativo' : 'Panel del ' . ($labels[$rol] ?? 'Usuario') }}
        </h1>
        <p class="subtitle" style="margin:0;color:#51627c;font-size:20px;">Bienvenido(a), {{ $usuario->nombres }} {{ $usuario->apellidos }}.</p>
        @if (session('status'))
            <div class="alert ok">{{ session('status') }}</div>
        @endif
    </div>
</div>

<div class="soft-panel">
    <strong style="color:#1d4f91;letter-spacing:.6px;">ROL ACTIVO: {{ $labels[$rol] ?? 'Usuario' }}</strong>
</div>

<div class="quick-strip">
    <span class="quick-pill">Modulos habilitados: {{ count($modulos) + (($rol === 'administrador' || $rol === 'administracion') ? 1 : 0) }}</span>
    <span class="quick-pill">Acceso rapido desde este panel</span>
</div>

<div class="panel-grid {{ ($rol === 'administrador' || $rol === 'administracion') ? 'admin-grid' : '' }}">
    @if ($rol === 'administrador' || $rol === 'administracion')
        <div class="card module-card">
            <div>
                <h3>Usuarios</h3>
                <p>Alta, edicion, activacion, desactivacion y eliminacion de cuentas.</p>
            </div>
            <a class="btn" href="{{ route('users.index') }}">Entrar a Usuarios</a>
        </div>
    @endif

    @if (in_array('laboratorio', $modulos, true))
        <div class="card module-card">
            <div>
                <h3>Laboratorio</h3>
                <p>Gestion de pacientes, examenes y seguimiento de estados.</p>
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
                <p>Analisis clinico, consumo y actividad por modulo.</p>
            </div>
            <a class="btn" href="{{ route('reportes') }}">Ver Reportes</a>
        </div>
    @endif
</div>

@push('styles')
<style>
    .dashboard-header { justify-content: center; }
    .dashboard-header .page-header-center { flex: 0 1 auto; width: 100%; text-align: center; }
    .quick-strip {
        margin-top: 12px;
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .quick-pill {
        display: inline-flex;
        align-items: center;
        min-height: 30px;
        padding: 0 12px;
        border-radius: 999px;
        border: 1px solid #cddced;
        background: #f0f6fd;
        color: #1e466f;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .2px;
    }
    .admin-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    @media (max-width: 900px) {
        .dashboard-header {
            justify-content: center;
        }
        .dashboard-header .page-header-center {
            text-align: center;
        }
        .admin-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
@endsection
