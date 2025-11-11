@extends('layouts.app')

@section('content')
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

        .alert-success,
        .alert-danger {
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

        .search-form {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .search-form input[type="text"] {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            border-radius: 6px 0 0 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .search-form input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
        }

        .search-form button {
            padding: 10px 20px;
            border-radius: 0 6px 6px 0;
            border: none;
            background-color: #007bff;
            color: white;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .search-form button:hover {
            background-color: #0056b3;
        }

        .totals-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e0e4e8;
        }

        .totals-section h3 {
            color: #1a3c6d;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .totals-section ul {
            list-style-type: none;
            padding-left: 0;
        }

        .totals-section li {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }

        .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-top: 20px;
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

            .table th,
            .table td {
                min-width: 120px;
            }

            .search-form {
                flex-direction: column;
            }

            .search-form input[type="text"] {
                border-radius: 6px;
                margin-bottom: 10px;
            }

            .search-form button {
                border-radius: 6px;
                width: 100%;
            }
        }
    </style>


    <div class="container">
        <h2>Estacionamientos</h2>

        <a href="{{ route('dashboard') }}" class="back-arrow" title="Volver al inicio" aria-label="Volver al inicio">
            &#8592;
        </a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (isset($sessions) && $sessions->isEmpty())
            <div class="alert alert-danger">
                No tienes estacionamientos registrados aún. Registra uno nuevo.
            </div>
            <a href="{{ route('parking.create') }}" class="btn btn-primary">Registrar Estacionamiento</a>
        @elseif (isset($session))
            <!-- Detalles de un estacionamiento específico -->
            <table class="table table-striped table-hover">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>{{ $session->id }}</td>
                    </tr>
                    <tr>
                        <th>Patente</th>
                        <td>{{ $session->license_plate }}</td>
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
                        <th>Duración</th>
                        <td>{{ number_format($session->duration / 60, 1) }} horas</td>
                    </tr>
                    <tr>
                        <th>Monto Estimado</th>
                        <td>${{ number_format($session->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Inicio</th>
                        <td>{{ $session->start_time->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Fin</th>
                        <td>{{ $session->end_time ? $session->end_time->format('d/m/Y H:i') : 'No finalizado' }}</td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td>
                            @if ($session->status === 'active')
                                <span class="badge bg-warning">Activo</span>
                            @elseif ($session->payment_status === 'completed')
                                <span class="badge bg-success">Completado</span>
                            @else
                                <span class="badge bg-secondary">Pendiente</span>
                            @endif
                        </td>
                    </tr>
                    @if ($session->payment_id)
                        <tr>
                            <th>Monto Pagado</th>
                            <td>ARS {{ number_format($session->amount, 2) }}</td>
                        </tr>
                        @if ($session->payment_id)
                            <tr>
                                <th>ID de Pago (Mercado Pago)</th>
                                <td>{{ $session->payment_id }}</td>
                            </tr>
                        @endif
                    @endif
                </tbody>
            </table>
            <div class="mt-3">
                <a href="{{ route('parking.create') }}" class="btn btn-primary">Registrar otro</a>
                <a href="{{ route('parking.show') }}" class="btn btn-secondary">Volver al Historial</a>
            </div>
        @elseif (isset($sessions))
            <!-- Lista de todos los estacionamientos (historial) -->
            <form method="GET" action="{{ route('parking.show') }}" class="search-form">
                <input type="text" name="search" placeholder="Buscar por patente, zona o calle..." value="{{ request('search') }}">
                <button type="submit">Buscar</button>
            </form>

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patente</th>
                        <th>Zona</th>
                        <th>Calle</th>
                        <th>Inicio</th>
                        <th>Duración</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                
                        <tr>
                            <td>{{ $session->id }}</td>
                            <td>{{ $session->license_plate }}</td>
                            <td>{{ $session->street->zone->name }}</td>
                            <td>{{ $session->street->name }}</td>
                            <td>{{ $session->start_time->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($session->duration / 60, 1) }} horas</td>
                            <td>${{ number_format($session->amount, 2) }}</td>
                            <td>
                                @if ($session->status === 'active')
                                    <span class="badge bg-warning">Activo</span>
                                @elseif ($session->payment_status === 'completed')
                                    <span class="badge bg-success">Completado</span>
                                @else
                                    <span class="badge bg-secondary">Pendiente</span>
                                @endif
                            </td>
                          
                            
                    @endforeach
                </tbody>
            </table>

            @php
                $total = $sessions->sum('amount');
                $grouped = $sessions->groupBy('license_plate');
            @endphp

            <div class="totals-section">
                <h3>Totales por Patente</h3>
                <ul>
                    @foreach($grouped as $plate => $group)
                        <li>{{ $plate }}: ${{ number_format($group->sum('amount'), 2) }}</li>
                    @endforeach
                </ul>
                <div class="grand-total">
                    Total General: ${{ number_format($total, 2) }}
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('parking.create') }}" class="btn btn-primary">Registrar nuevo</a>
            </div>
        @else
            <div class="alert alert-danger">
                Error al cargar. Recarga la página.
            </div>
        @endif
    </div>
@endsection