@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .custom-card {
            max-width: 600px;
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

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .details-table th,
        .details-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .details-table th {
            background-color: #007bff;
            color: white;
        }

        .time-remaining {
            font-weight: bold;
            color: #2c3e50;
        }

        .back-arrow {
            display: inline-block;
            font-size: 32px;
            font-weight: bold;
            color: #1a3c6d;
            text-decoration: none;
            margin-bottom: 15px;
            background: #fff;
            border-radius: 50%;
            padding: 8px 14px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .back-arrow:hover {
            background: #007bff;
            color: #fff;
            transform: scale(1.1);
        }
    </style>

    <div class="custom-card">
        <a href="{{ route('parking.create') }}" class="back-arrow" title="Volver" aria-label="Volver">&#8592;</a>
        <h2>Detalles del Estacionamiento</h2>

        @if ($session)
            <table class="details-table">
                <tr>
                    <th>Patente</th>
                    <td>{{ $session->car->license_plate ?? $session->car->car_plate }}</td>
                </tr>
                <tr>
                    <th>Zona</th>
                    <td>{{ $session->street->zone->name }}</td>
                </tr>
                <tr>
                    <th>Calle</th>
                    <td>{{ $session->street->name }}</td>
                </tr>
                <tr>
                    <th>Hora de inicio</th>
                    <td>{{ $session->start_time->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Duración</th>
                    <td>{{ $session->duration }} minutos</td>
                </tr>
                <tr>
                    <th>Monto estimado</th>
                    <td>${{ number_format($session->amount, 2) }}</td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td>
                        @if ($session->status === 'active')
                            <span class="badge bg-warning">Activo</span>
                        @else
                            <span class="badge bg-success">Cancelado</span>
                        @endif
                    </td>
                </tr>
                @if ($session->status === 'active' && $timeLeft)
                    <tr>
                        <th>Tiempo restante</th>
                        <td class="time-remaining">
                            <?php
                                $hours = floor($timeLeft / 3600);
                                $minutes = floor(($timeLeft % 3600) / 60);
                                $seconds = $timeLeft % 60;
                            ?>
                            {{ $hours }}h {{ $minutes }}m {{ $seconds }}s
                        </td>
                    </tr>
                @endif
            </table>

            @if ($session->status === 'active')
                <form action="{{ route('parking.end', $session->id) }}" method="POST" style="margin-top: 20px;">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn-red">Finalizar Estacionamiento</button>
                </form>
            @endif
        @else
            <h3>No hay detalles disponibles para este estacionamiento.</h3>
        @endif
    </div>
@endsection