<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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

        .alert-success {
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #d4edda;
            color: #155724;
        }

        /* Responsive */
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
                            <input type="hidden" name="id_car" value="{{ $sesion->car_id }}">
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
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>