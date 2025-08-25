@extends('layouts.app')

@section('title', 'EMER - Dashboard Admin')

@section('sidebar')
    <div class="col-md-2 sidebar">
        <h4 class="text-center mb-4">Menú Admin</h4>
        <a href="{{ route('dashboard') }}">🏠 Inicio</a>
        <a href="{{ route('cars.index') }}">🚗 Autos</a>
        <a href="{{ route('inspectors.index') }}">🕵️ Inspectores</a>
        <a href="{{ route('infractions.index') }}">⚠️ Infracciones</a>
        <a href="{{ route('logout') }}">🚪 Cerrar sesión</a>
    </div>
@endsection
@section('header')
    <div class="header">
        <span class="welcome">Dashboard Admin, <strong>{{ Auth::user()->name ?? 'Invitado' }}</strong></span>
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Cerrar sesión</button>
        </form>
    </div>
@endsection
@section('content')
    <h1 class="mb-4">Dashboard Admin</h1>
    <p>Panel de control para administradores. Desde aquí puedes gestionar todo el sistema.</p>
@endsection