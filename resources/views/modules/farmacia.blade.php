@extends('layouts.app', ['title' => 'Farmacia'])

@section('content')
<div class="card" style="padding:24px;">
    <h1 class="title" style="font-size:38px;">Módulo Farmacia</h1>
    <p class="subtitle" style="font-size:20px;">Acceso permitido según tu rol.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-dark" style="display:inline-block;text-decoration:none;margin-top:14px;">Volver al panel</a>
</div>
@endsection

