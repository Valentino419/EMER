@extends('layouts.app')

@section('title', 'EMER - Add Zone')

@section('sidebar')
    <div class="col-md-2 sidebar">
        <h4 class="text-center mb-4">Menú Admin</h4> <!-- Change to "Menú Inspector" if intended for inspectors -->
        <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Inicio</a>
        <a href="{{ route('cars.index') }}"><i class="fas fa-car"></i> Autos</a>
        <a href="{{ route('inspectors.index') }}"><i class="fas fa-user-shield"></i> Inspectores</a>
        <a href="{{ route('infractions.index') }}"><i class="fas fa-exclamation-triangle"></i> Infracciones</a>
        <a href="{{ route('parking.create') }}"><i class="fas fa-map-pin"></i> Registrar Estacionamiento</a>
        <a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
    </div>
@endsection

@section('header')
    <div class="header">
        <span class="welcome">Bienvenido, <strong>{{ Auth::user()->name ?? 'Admin' }}</strong></span>
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Cerrar sesión</button>
        </form>
    </div>
@endsection

@section('content')
    <div class="content">
        <h1 class="mb-4">Add New Zone</h1>
        <p>Enter the details for the new zone below.</p>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('zones.store') }}" class="login-container" style="width: 400px; padding: 30px;">
            @csrf
            <div class="mb-3">
                <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required
                       class="form-control" style="padding: 12px 15px; border-radius: 8px; border: 1px solid #ccc; font-size: 14px; color: #010000ff;">
            </div>
            <div class="mb-3">
                <input type="text" name="numeration" placeholder="Numeration" value="{{ old('numeration') }}" required
                       class="form-control" style="padding: 12px 15px; border-radius: 8px; border: 1px solid #ccc; font-size: 14px; color: #010000ff;">
            </div>
            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #00c6ff, #0072ff); border: none; width: 100%; padding: 12px 20px; border-radius: 8px; font-size: 16px; cursor: pointer; transition: transform 0.2s ease;">
                Save Zone
            </button>
        </form>
    </div>
@endsection