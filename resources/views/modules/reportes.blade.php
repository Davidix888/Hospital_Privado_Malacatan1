@extends('layouts.app', ['title' => 'Reportes'])

@section('content')
<div class="card module-shell">
    <h1 class="title" style="font-size:38px;">Módulo Reportes</h1>
    <p class="subtitle">Acceso permitido según tu rol.</p>
    <div class="module-actions">
        <a href="{{ route('dashboard') }}" class="btn btn-success">Volver al panel</a>
    </div>
</div>
@endsection
