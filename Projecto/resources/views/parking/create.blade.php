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
</style>

<div class="custom-card">
    <div class="form-header">
        <h2>Registrar Estacionamiento</h2>
    </div>
    <form action="{{ route('parking.store') }}" method="POST">
        @csrf
        <div class="form-section">
            <div class="form-title">Datos del Estacionamiento</div>
            <div class="form-body">
                <div class="mb-4">
                    <label for="car_id">Vehículo (Patente)</label>
                </div>

                <div class="mb-4">
                    <label for="zone_id">Zona</label>
                    <select id="zona" class="form-control">
                        <option value="">Selecciona una zona</option>
                        <option value="zona1">Zona 1</option>
                        <option value="zona2">Zona 2</option>
                        <option value="zona3">Zona 3</option>
                    </select>
                    <div style="width: 70%; margin: 10px auto; text-align: center;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12612.156494877916!2d-58.511!3d-33.0079!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bb5f9f9f9f9f9f%3A0x123456789!2zR3VhbGVndWF5Y2jDuw!5e0!3m2!1ses!2sar!4v1694000000000"
                            width="150%"
                            height="250"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                <div class="mb-4">
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
