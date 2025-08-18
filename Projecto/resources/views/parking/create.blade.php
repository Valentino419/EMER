@extends('layouts.app')

@section('content')

<style>
    body {
        background-color: #f0f4f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .custom-card {
        max-width: 900px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #1a3c6d;
        font-weight: 700;
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }

    select, input[type="number"], input[type="text"] {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 15px;
        transition: border-color 0.3s;
    }

    select:focus, input:focus {
        border-color: #007bff;
        outline: none;
    }

    .btn-blue {
        background-color: #007bff;
        color: white;
        font-weight: 600;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .btn-blue:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .form-header h2 {
        margin: 0;
    }

    .form-section {
        border-radius: 8px;
        overflow: hidden;
    }

    .form-section .form-title {
        background-color: #007bff;
        color: #fff;
        padding: 12px;
        font-weight: 600;
        font-size: 16px;
    }

    .form-section .form-body {
        padding: 20px;
    }
</style>

<div class="custom-card">
    <div class="form-header">
        <h2>Registrar Estacionamiento</h2>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('parking.store') }}" method="POST">
        @csrf

        <div class="form-section">
            <div class="form-title">Datos del Estacionamiento</div>
            <div class="form-body">
                
                <!-- Patente -->
                <div class="mb-4">
                    <label for="car_id">Vehículo (Patente)</label>
                    <input type="text" name="car_id" id="car_id" placeholder="Ingrese la patente sin espacios ni puntos" required>
                </div>

                <!-- Zona -->
                <div class="mb-4">
                    <label for="zone_id">Zona</label>
                    <select name="zone_id" id="zone_id" required>
                        <option value="">Seleccioná una zona</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tiempo -->
                <div class="mb-4">
                    <label for="estimated_minutes">Tiempo estimado (minutos)</label>
                    <input type="time" name="estimated_minutes" id="estimated_minutes" min="15" step="15" required>
                </div>
                <br>
                
                <!-- Botón -->
                <div class="flex justify-end">
                    <button type="submit" class="btn-blue">Iniciar Estacionamiento</button>
                </div>

            </div>
        </div>
    </form>
</div>

@endsection
