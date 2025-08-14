@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f0f4f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .custom-container {
        max-width: 800px;
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

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 5px solid #dc3545;
    }

    .session-info {
        background-color: #f8fafc;
        border: 1px solid #e0e4e8;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .session-info h4 {
        margin: 0 0 10px;
        font-weight: 700;
        color: #1a3c6d;
    }

    .session-info p {
        margin: 5px 0;
        color: #333;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .table th, .table td {
        padding: 10px;
        border: 1px solid #e0e4e8;
        text-align: left;
    }

    .table th {
        background-color: #007bff;
        color: white;
        font-weight: 600;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f8fafc;
    }
</style>

<div class="custom-container">
    <h2>Cobro de Estacionamiento</h2>

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Lista de sesiones activas -->
    <div class="session-info">
        <h4>Sesiones Activas</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Patente</th>
                    <th>Usuario</th>
                    <th>Inicio</th>
                    <th>Monto Estimado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($carsConSesion as $sesion)
                <tr>
                    <td>{{ $sesion->car->car_plate }}</td>
                    <td>{{ $sesion->car->user->firtsname }} {{ $sesion->car->user->lastname }}</td>
                    <td>{{ \Carbon\Carbon::parse($sesion->start_time)->format('d/m/Y H:i') }}</td>
                    <td>
                        ${{ number_format(\Carbon\Carbon::parse($sesion->start_time)->diffInMinutes(\Carbon\Carbon::now()) * $sesion->rate, 2) }}
                    </td>
                    <td>
                        <form action="{{ route('payment.store') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="id_car" value="{{ $sesion->id_car }}">
                            <select name="metodo_pago" required>
                                <option value="">Seleccionar método</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta">Tarjeta</option>
                                <option value="mercadopago">MercadoPago</option>
                                <option value="transferencia">Transferencia</option>
                            </select>
                            <button type="submit" class="btn-submit">Cobrar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">No hay sesiones activas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection