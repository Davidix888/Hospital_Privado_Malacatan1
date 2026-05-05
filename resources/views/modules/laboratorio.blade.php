@extends('layouts.app', ['title' => 'Laboratorio'])

@section('content')
<div class="card" style="padding:24px;">
    <h1 class="title" style="font-size:38px;">Módulo Laboratorio</h1>
    <p class="subtitle" style="font-size:20px;">Acceso permitido según tu rol.</p>
    <a href="{{ route('dashboard') }}" class="btn" style="display:inline-block;text-decoration:none;margin-top:14px;">Volver al panel</a>
</div>
@endsection

