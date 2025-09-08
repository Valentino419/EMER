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
    h2 { color: #1a3c6d; font-weight: 700; margin-bottom: 20px; }
    label { font-weight: 600; color: #333; margin-bottom: 5px; display: block; }
    select, input[type="time"], input[type="text"] {
        width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px;
        margin-bottom: 20px; font-size: 15px; transition: border-color 0.3s;
    }
    select:focus, input:focus { border-color: #007bff; outline: none; }
    .btn-blue {
        background-color: #007bff; color: white; font-weight: 600; padding: 10px 20px;
        border: none; border-radius: 8px; cursor: pointer; transition: 0.3s;
    }
    .btn-blue:hover { background-color: #0056b3; transform: translateY(-2px); }
    .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .form-section { border-radius: 8px; overflow: hidden; margin-bottom: 25px; }
    .form-section .form-title { background-color: #007bff; color: #fff; padding: 12px; font-weight: 600; font-size: 16px; }
    .form-section .form-body { padding: 20px; }
    .summary { background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
    .summary p { margin: 5px 0; }
</style>

<div class="custom-card">
    <div class="form-header">
        <h2>Registrar Estacionamiento</h2>
    </div>

    {{-- Inicio de estacionamiento --}}
    <form action="{{ route('parking.store') }}" method="POST">
        @csrf
        <div class="form-section">
            <div class="form-title">Datos del Estacionamiento</div>
            <div class="form-body">
                <div class="mb-4">
                    <label for="car_id">Vehículo (Patente)</label>
                    <input type="text" name="car_id" id="car_id" placeholder="Ej: ABC123" required>
                </div>

                <div class="mb-4">
                    <label for="zone_id">Zona</label>
                    <select name="zone_id" id="zone_id" required>
                        <option value="">Seleccioná una zona</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                <label for="estimated_hours">Tiempo estimado (horas)</label>
                <select name="estimated_hours" id="estimated_hours" required>
                    <option value="">Seleccioná horas</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ $i }} hora{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>


                <div class="flex justify-end">
                    <button type="submit" class="btn-blue">Iniciar Estacionamiento</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Ejemplo de sesión activa --}}
    @if(isset($session))
        <div class="form-section">
            <div class="form-title">Sesión Activa</div>
            <div class="form-body">
                <div class="summary">
                    <p><strong>Patente:</strong> {{ $session->car->plate }}</p>
                    <p><strong>Zona:</strong> {{ $session->zone->name }}</p>
                    <p><strong>Hora inicio:</strong> {{ $session->start_time }}</p>
                    <p><strong>Tiempo total:</strong> {{ $session->duration }} minutos</p>
                    <p><strong>Monto estimado:</strong> ${{ $session->amount }}</p>
                </div>

                <form action="{{ route('parking.end', $session->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-blue w-100">Finalizar y Calcular Pago</button>
                </form>
            </div>
        </div>
    @endif

    {{-- Ejemplo de pago pendiente --}}
    @if(isset($payment))
        <div class="form-section">
            <div class="form-title">Pago Pendiente</div>
            <div class="form-body">
                <div class="summary">
                    <p><strong>Patente:</strong> {{ $payment->car->plate }}</p>
                    <p><strong>Duración:</strong> {{ $payment->duration }} minutos</p>
                    <p><strong>Monto a pagar:</strong> ${{ $payment->amount }}</p>
                </div>

                <button class="btn-blue w-100">Pagar con Mercado Pago (Prueba)</button>
            </div>
        </div>
    @endif

</div>

@endsection
