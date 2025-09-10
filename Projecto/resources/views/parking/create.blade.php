@extends('layouts.app')

@section('content')

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
    .back-arrow {
    display: inline-block;
    font-size: 32px; /* más grande */
    font-weight: bold;
    color: #1a3c6d;
    text-decoration: none;
    margin-bottom: 15px;
    background: #fff;
    border-radius: 50%;
    padding: 8px 14px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

.back-arrow:hover {
    background: #007bff;
    color: #fff;
    transform: scale(1.1);
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
    
    <a href="{{ route('dashboard') }}" class="back-arrow" title="Volver al inicio">
        &#8592;
    </a>

    <form id="parking-form" action="{{ route('parking.store') }}" method="POST">
        @csrf

        <div class="form-section">
            <div class="form-title">Datos del Estacionamiento</div>
            <div class="form-body">
                
                <!--Patente  -->
                <div class="mb-4">
                    <label for="car_id">Vehículo</label>
                <select name="car_id" id="car_id" class="form-select" required>
                    <option value="">Seleccione un auto</option>
                    @foreach ($cars as $car)
                        <option value="{{ $car->id }}">{{ $car->car_plate }}</option>
                    @endforeach
                </select>

                <!-- Zona -->
                <div class="mb-4">
                    <label for="zone_id">Zona</label>
                    <select id="zone_id" class="form-control">
                        <option value="">Selecciona una zona</option>
                        <option value="zona1">Zona 1</option>
                        <option value="zona2">Zona 2</option>
                        <option value="zona3">Zona 3</option>
                    </select>
                    <!-- <div style="width: 70%; margin: 10px auto; text-align: center;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12612.156494877916!2d-58.511!3d-33.0079!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bb5f9f9f9f9f9f%3A0x123456789!2zR3VhbGVndWF5Y2jDuw!5e0!3m2!1ses!2sar!4v1694000000000"
                            width="150%"
                            height="250"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe> -->
                    </div>
                </div>
                
                
                <!-- Tiempo -->
                <div class="mb-4">
                    <label for="estimated_minutes">Tiempo estimado (horas)</label>
                    <input type="number" name="start_time" id="start_time">
                </div>
                <br>

                <button id="iniciar-estacionamiento" class="btn-blue">Iniciar Estacionamiento</button>
                 <!-- Tarifa -->
                <div class="mb-4">
                    <label for="rate"></label>
                    <input type="number" name="rate" id="rate" class="form-control" step="0.01" hidden>
                </div>

                 <!-- Calle -->
                <div class="mb-4">
                    <label for="street_id"></label>
                    <select name="street_id" id="street_id" class="form-control" hidden>
                        <option value="">Seleccione una calle</option>
                        @foreach ($streets as $street)
                            <option value="{{ $street->id }}">{{ $street->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Estado -->
                <div class="mb-4">
                    <label for="status"></label>
                    <select name="status" id="status" class="form-control" hidden>
                        <option value="active">Activo</option>
                        <option value="completed">Completado</option>
                    </select>
                </div>
</div>
</form>

<!-- <script>
    const parkingStoreRoute = '{{ route("parking.store") }}';
</script>
<script src="{{ asset('js/parking.js') }}" defer></script> -->
@endsection

