@extends('layouts.app')
@section('content')
<div class="custom-card">
    <h2>Comprobante de Pago</h2>
    <p><strong>Vehículo:</strong> {{ $session->car->license_plate }}</p>
    <p><strong>Zona:</strong> {{ $session->zone->name }}</p>
    <p><strong>Calle:</strong> {{ $session->street->name }}</p>
    <p><strong>Desde:</strong> {{ $session->start_time->format('d/m/Y H:i') }}</p>
    <p><strong>Hasta:</strong> {{ $session->end_time->format('d/m/Y H:i') }}</p>
    <p><strong>Monto:</strong> ${{ number_format($session->amount, 2) }}</p>
    <p><strong>ID de pago:</strong> {{ $session->payment_id }}</p>
    <a href="{{ route('dashboard') }}" class="btn-blue">Volver al inicio</a>
</div>
@endsection