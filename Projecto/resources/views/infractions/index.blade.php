<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Infracciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
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
</head>

<body>
    <div class="container mt-4">
        <a href="{{ route('dashboard') }}" class="back-arrow" title="Volver al inicio">&#8592;</a>
        <h2 class="mb-4">
            @if (Auth::user()->role->name === 'inspector' || Auth::user()->role->name === 'admin')
                Gestión de Infracciones
            @else
                Mis Infracciones
            @endif
        </h2>
        <hr>
        <div class="d-flex justify-content-center align-items-center mb-4 search-new-container gap-2">
            <form method="GET" action="{{ route('infractions.index') }}" class="flex-grow-1">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por patente..."
                        value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </form>
            @if (Auth::user()->role->name === 'admin' || Auth::user()->role->name === 'inspector')
                <button class="btn btn-primary btn-new" data-bs-toggle="modal" data-bs-target="#infraccionModal">
                    Nueva Infracción
                </button>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patente</th>
                    <th>Multa</th>
                    <th>Fecha</th>
                    <th>Estado</th>

                    @if (Auth::user()->role->name === 'admin' || Auth::user()->role->name === 'user')
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>
           <tbody>
    @forelse ($infractions as $infraction)
        <tr>
            <td>{{ $infraction->id }}</td>
            <td>{{ $infraction->car?->car_plate ?? 'N/A' }}</td>
            <td>${{ $infraction->fine }}</td>
            <td>{{ $infraction->date }}</td>
            <td>{{ $infraction->status }}</td>

            @if (Auth::user()->role->name === 'admin')
                <td>
                    <a href="{{ route('infractions.edit', $infraction) }}" class="btn btn-sm btn-primary">
                        Editar
                    </a>
                    <form action="{{ route('infractions.destroy', $infraction) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('¿Eliminar esta infracción?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            @elseif (Auth::user()->role->name === 'user')
                <td>
                    @if ($infraction->status === 'pending')
                        <form action="{{ route('payments.create', $infraction) }}" method="GET" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Pagar</button>
                        </form>
                    @else
                        <span class="text-muted">{{ ucfirst($infraction->status) }}</span>
                    @endif
                </td>
            @endif
        </tr>
    @empty
        @if (request('search'))
            <!-- ====== AQUÍ VA LA LÓGICA DE BÚSQUEDA ====== -->
            @if ($carStatus === 'formato_invalido')
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="bg-light rounded-3 p-5 shadow-sm border border-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#dc3545"
                                class="bi bi-x-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                            <h5 class="text-danger fw-bold mt-3">Formato de patente inválido</h5>
                            <p class="text-muted">Use: <code>AA123BB</code> o <code>ABC123</code></p>
                        </div>
                    </td>
                </tr>

            @elseif ($carStatus === 'no_encontrado')
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="bg-light rounded-3 p-5 shadow-sm border border-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#ffc107"
                                class="bi bi-car-front" viewBox="0 0 16 16">
                                <path d="M4 9a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm10 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2H6ZM4.5 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 .5.5v3h-2V3H7v2.5H5v-3Z"/>
                                <path d="M2 13.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-1Z"/>
                            </svg>
                            <h5 class="text-warning fw-bold mt-3">Patente no registrada</h5>
                            <p class="text-muted">
                                <strong>{{ strtoupper(request('search')) }}</strong> no está en el sistema.<br>
                                <small>Se creará al registrar la infracción.</small>
                            </p>
                            <button class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#infraccionModal"
                                onclick="document.getElementById('car_plate').value = '{{ strtoupper(request('search')) }}'">
                                Registrar Infracción
                            </button>
                        </div>
                    </td>
                </tr>

            @elseif ($carStatus === 'encontrado')
                @if ($activeParkingSession)
                    <!-- ESTACIONAMIENTO ACTIVO -->
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="bg-light rounded-3 p-5 shadow-sm border border-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#28a745"
                                    class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                </svg>
                                <h5 class="text-success fw-bold mt-3">Vehículo con estacionamiento ACTIVO</h5>
                                <div class="text-start mt-3">
                                    <p><strong>Patente:</strong> {{ $car->car_plate }}</p>
                                    <p><strong>Zona:</strong> {{ $activeParkingSession->zone->name ?? 'N/A' }}</p>
                                    <p><strong>Calle:</strong> {{ $activeParkingSession->street->name ?? 'N/A' }}</p>
                                    <p><strong>Finaliza:</strong> {{ $activeParkingSession->end_time->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="alert alert-info mt-3">
                                    <strong>No se puede multar:</strong> El vehículo está estacionado correctamente.
                                </div>
                            </div>
                        </td>
                    </tr>
                @else
                    <!-- SIN ESTACIONAMIENTO ACTIVO -->
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="bg-light rounded-3 p-5 shadow-sm border border-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#dc3545"
                                    class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                </svg>
                                <h5 class="text-danger fw-bold mt-3">SIN estacionamiento activo</h5>
                                <p class="text-muted">
                                    <strong>{{ $car->car_plate }}</strong> puede ser multado.
                                </p>
                                <button class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#infraccionModal"
                                    onclick="document.getElementById('car_plate').value = '{{ $car->car_plate }}'">
                                    Registrar Infracción
                                </button>
                            </div>
                        </td>
                    </tr>
                @endif
            @endif
        @else
            <!-- SIN BÚSQUEDA: LISTA NORMAL -->
            <tr>
                <td colspan="{{ Auth::user()->role->name === 'inspector' ? 5 : 6 }}" class="text-center py-5">
                    <div class="bg-light rounded-3 p-5 shadow-sm border border-light">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#6c757d"
                            class="bi bi-inbox" viewBox="0 0 16 16">
                            <path d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5v1A1.5 1.5 0 0 1 5 11H1a1.5 1.5 0 0 1-1.5-1.5v-7A1.5 1.5 0 0 1 1 1h5.5a.5.5 0 0 1 0 1H1a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-1A.5.5 0 0 1 6 8h4.54l-3.05-3.812A.5.5 0 0 0 11.98 4H4.98z"/>
                        </svg>
                        <h5 class="text-muted fw-bold mt-3">No hay infracciones registradas</h5>
                    </div>
                </td>
            </tr>
        @endif
    @endforelse
</tbody>
        </table>

        <div class="mt-4">
            {{ $infractions->links() }}
        </div>
    </div>

    <!-- Modal para nueva infracción -->
    <div class="modal fade" id="infraccionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Registrar Nueva Infracción</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="infraccionForm" action="{{ route('infractions.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="car_plate" class="form-label">Patente</label>
                            <input type="text" id="car_plate" name="car_plate" class="form-control"
                                value="{{ old('car_plate') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="fine" class="form-label">Multa</label>
                            <input type="number" name="fine" id="fine" class="form-control"
                                value="{{ old('fine', 5000) }}" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Fecha</label>
                            <input type="date" name="date" id="date" class="form-control"
                                value="{{ old('date', now()->format('Y-m-d')) }}">
                        </div>

                    </form>
                </div>


                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="infraccionForm" class="btn btn-primary">Registrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    
  @if ($deudaPending && Auth::user()->role->name === 'user')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const deuda = {
                fine: '{{ $deudaPending->fine }}',
                carPlate: '{{ $deudaPending->car->car_plate }}',
                date: '{{ $deudaPending->date }}'
            };
            Swal.fire({
                icon: 'warning',
                title: 'Deuda Pendiente',
                text: '¡Tienes una deuda pendiente de $' + deuda.fine + ' (Patente: ' + deuda.carPlate + ', Fecha: ' +
                    deuda.date + ')!',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
</body>
</html>
