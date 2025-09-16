<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Estacionamiento</title>
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin-top: 40px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #1a3c6d;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e0e4e8;
        }

        .table th {
            background-color: #007bff;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 16px;
        }

        .table td {
            padding: 16px;
            vertical-align: middle;
            color: #333;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8fafc;
        }

        .table-hover tbody tr:hover {
            background-color: #e6f0fa;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 8px 18px;
            font-weight: 500;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            padding: 8px 18px;
            font-weight: 500;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-danger:hover {
            background-color: #b02a37;
            transform: translateY(-2px);
        }

        .alert-success, .alert-danger {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .table th, .table td {
                min-width: 120px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Detalles del Estacionamiento</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (isset($noParking) && $noParking)
            <div class="alert alert-danger">
                No tienes estacionamientos completados aún. Registra uno nuevo.
            </div>
            <a href="{{ route('parking.create') }}" class="btn btn-primary">Registrar Estacionamiento</a>
        @elseif (isset($parking))
            <table class="table table-striped table-hover">
                <tbody>
                    <tr>
                        <th>Patente</th>
                        <td>{{ $parking->license_plate }}</td>
                    </tr>
                    <tr>
                        <th>Duración</th>
                        <td>{{ number_format($parking->end_time->diffInHours($parking->start_time), 1) }} horas</td>
                    </tr>
                    <tr>
                        <th>Monto Pagado</th>
                        <td>${{ number_format($parking->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Inicio</th>
                        <td>{{ $parking->start_time->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Fin</th>
                        <td>{{ $parking->end_time->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Estado del Pago</th>
                        <td>
                            <span class="badge bg-success">Completado</span>
                        </td>
                    </tr>
                    @if ($parking->payment_id)
                        <tr>
                            <th>ID de Pago (Stripe)</th>
                            <td>{{ $parking->payment_id }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="mt-3">
                <a href="{{ route('parking.create') }}" class="btn btn-primary">Registrar otro estacionamiento</a>
                <a href="{{ route('home') }}" class="btn btn-danger">Volver al inicio</a>
            </div>
        @else
            <div class="alert alert-danger">
                Error al cargar los detalles. Intenta recargar la página.
            </div>
        @endif
    </div>
</body>
</html>