@extends('layouts.app')

@section('content')

<style>
    body {
        background-color: #f0f4f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .custom-container {
        max-width: 500px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #1a3c6d;
        font-weight: 700;
        margin-bottom: 25px;
        text-align: center;
    }

    label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }

    select, input[type="number"] {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 15px;
        transition: border-color 0.3s;
    }

    select:focus, input[type="number"]:focus {
        border-color: #007bff;
        outline: none;
    }

    .btn-submit {
        background-color: #007bff;
        color: white;
        font-weight: 600;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .btn-submit:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 5px solid #28a745;
    }
</style>

<div class="container mx-auto max-w-md mt-10">
    <h2 class="text-2xl font-bold mb-6">Estacionamiento medido</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('parking.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf

        <!-- Patente del auto -->
        <div class="mb-4">
            <label for="car_id" class="block text-gray-700 font-semibold mb-1">Vehículo (Patente)</label>
            <select name="car_id" id="car_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">Seleccioná un vehículo</option>
                @foreach($cars as $car)
                    <option value="{{ $car->id }}">{{ $car->car_plate }}</option>
                @endforeach
            </select>
        </div>

        <!-- Zona -->
        <div class="mb-4">
            <label for="zone_id" class="block text-gray-700 font-semibold mb-1">Zona</label>
            <select name="zone_id" id="zone_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">Seleccioná una zona</option>
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tiempo estimado -->
        <div class="mb-4">
            <label for="estimated_minutes" class="block text-gray-700 font-semibold mb-1">Tiempo estimado (minutos)</label>
            <input type="number" name="estimated_minutes" id="estimated_minutes" min="15" step="15"
                   class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        <!-- Botón -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Iniciar Estacionamiento
            </button>
        </div>
    </form>
</div>
@endsection
