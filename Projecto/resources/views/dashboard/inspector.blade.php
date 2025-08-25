@extends('layouts.app')

@section('title', 'EMER - Dashboard Inspector')

@section('sidebar')
    <div class="col-md-2 sidebar">
        <h4 class="text-center mb-4">Menú Inspector</h4>
        <a href="{{ route('dashboard.inspector') }}">🏠 Inicio</a>
        <a href="{{ route('infractions.index') }}">⚠️ Infracciones</a>
        <a href="{{ route('cars.index') }}">🚗 Autos</a>
    </div>
@endsection


@section('header')
    <div class="header">
        <span class="welcome">Dashboard Inspector, <strong>{{ Auth::user()->name ?? 'Invitado' }}</strong></span>
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Cerrar sesión</button>
        </form>
    </div>
@endsection
@section('content')
    <h1 class="mb-4">Dashboard Inspector</h1>
    <p>Panel de control para inspectores. Gestiona y revisa infracciones y autos asignados.</p>

@endsection
