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
                    <th>Acciones</th>
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
                        <td>
                            @if (Auth::user()->role->name === 'admin' || Auth::user()->role->name === 'inspector')
                                <a href="{{ route('infractions.edit', $infraction) }}"
                                    class="btn btn-sm btn-primary">Editar</a>
                                <form action="{{ route('infractions.destroy', $infraction) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Estás seguro de eliminar esta infracción?')">Eliminar</button>
                                </form>
                            @else
                                <form action="{{ route('infractions.index', $infraction) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Pagar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    @if ($infractions->isEmpty())
                        <div class="text-center py-5 my-4">
                            <div class="bg-light rounded-3 p-5 shadow-sm border border-light"
                                style="max-width: 600px; margin: 0 auto;">
                                <div class="mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#6c757d"
                                        class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                                        <path
                                            d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                        <path
                                            d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z" />
                                    </svg>
                                </div>
                                <h5 class="text-muted fw-bold">
                                    No se encontraron infracciones
                                </h5>
                                <p class="text-secondary mb-3">
                                    para la patente <strong
                                        class="text-primary">{{ strtoupper($car_plate ?? 'desconocida') }}</strong>
                                </p>
                            </div>
                        </div>
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
                        <div class="mb-3">
                            <label for="status" class="form-label">Estado</label>
                            <select name="status" id="status" class="form-control">
                                <option value="pending"
                                    {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>
                                    Pendiente</option>
                                <option value="paid" {{ old('status') === 'paid' ? 'selected' : '' }}>Pagada
                                </option>
                                <option value="canceled" {{ old('status') === 'canceled' ? 'selected' : '' }}>
                                    Cancelada
                                </option>
                            </select>
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
