@extends('layouts.app')

@section('title', 'Dashboard')

@section('sidebar')
    <div class="col-md-2 sidebar">
        <h4 class="text-center mb-4">Menú</h4>
        <a href="{{ route('dashboard.user') }}"><i class="fas fa-home"></i> Inicio</a>
        <a href="{{ route('cars.index') }}"><i class="fas fa-car"></i> Autos</a>
        <a href="{{ route('inspectors.index') }}"><i class="fas fa-user-shield"></i> Inspectores</a>
        <a href="{{ route('infractions.index') }}"><i class="fas fa-exclamation-triangle"></i> Infracciones</a>
        <a href="{{ route('parking.create') }}"><i class="fas fa-map-pin"></i> Registrar Estacionamiento</a>
        
    </div>
@endsection

@section('header')
    <div class="header">
        <span class="welcome">Bienvenido, <strong>{{ Auth::user()->name ?? 'Invitado' }}</strong></span>
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Cerrar sesión</button>
        </form>
    </div>
@endsection

@section('content')
    <h1>Dashboard</h1>
    <p>Bienvenido a tu panel de control. Desde aquí puedes navegar por todas las secciones del sistema.</p>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <h5>Autos</h5>
                <p>Gestiona todos los autos registrados.</p>
                <a href="{{ route('cars.index') }}" class="btn btn-primary">Ver</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <h5>Inspectores</h5>
                <p>Administra la lista de inspectores.</p>
                <a href="{{ route('inspectors.index') }}" class="btn btn-primary">Ver</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <h5>Infracciones</h5>
                <p>Consulta y gestiona las infracciones.</p>
                <a href="{{ route('infractions.index') }}" class="btn btn-primary">Ver</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <h5>Estacionamiento</h5>
                <p>Registrar un nuevo estacionamiento.</p>
                <a href="{{ route('parking.create') }}" class="btn btn-primary">Ver</a>
            </div>
        </div>
    </div>
@endsection