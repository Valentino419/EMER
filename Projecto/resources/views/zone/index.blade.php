<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Zonas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 15px;
        }

        h1 {
            color: #1a3c6d;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e0e4e8;
        }

        .table th {
            background-color: #4a90e2;
            color: white;
            font-weight: 600;
            padding: 12px;
        }

        .table td {
            padding: 12px;
            vertical-align: middle;
        }

        .btn-primary {
            background-color: #4a90e2;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #357abd;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .alert-success {
            border-radius: 5px;
            margin-bottom: 20px;
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
    <div class="container">
        <h1 class="text-center mb-4">
            @if (Auth::check() && (Auth::user()->role?->name === 'admin' || Auth::user()->role?->name === 'inspector'))
                Gestión de Zonas
            @else
                Mis Zonas
            @endif
        </h1>

        <a href="{{ route('dashboard') }}" class="back-arrow" title="Volver al inicio" aria-label="Volver al inicio">
            &#8592;
        </a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Botón de crear solo para admin -->
        @if (Auth::check() && (Auth::user()->role?->name === 'admin' || Auth::user()->role?->name === 'inspector'))
            <a href="{{ route('zones.create') }}" class="btn btn-primary mb-3">Nueva Zona</a>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cantidad de Calles</th>
                    <th>Montos</th>
                    <th>Acciones</th>
                </tr>

            </thead>
            <tbody>
                @forelse ($zones as $zone)
                    <tr>
                        <td>{{ $zone->id }}</td>
                        <td>{{ $zone->name }}</td>
                        <td>{{ $zone->streets->count() }}</td>
                        <td>{{ $zone->rate }}</td>

                        @if (Auth::check() && (Auth::user()->role?->name === 'admin' || Auth::user()->role?->name === 'inspector'))
                            <td>
                                <a href="{{ route('zones.show', $zone) }}" class="btn btn-primary btn-sm">Ver Calles</a>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editZone{{ $zone->id }}">
                                    Editar
                                </button>
                                <form action="{{ route('zones.destroy', $zone) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro?');">Eliminar</button>
                                </form>
                            </td>
                        @else
                            <td>
                                <a href="{{ route('zones.show', $zone) }}" class="btn btn-primary btn-sm">Ver Calles</a>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="@if (Auth::check() && (Auth::user()->role?->name === 'admin' || Auth::user()->role?->name === 'inspector')) 4 @else 3 @endif" class="text-center">No hay
                            zonas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


@foreach($zones as $zone)
<div class="modal fade" id="editZone{{ $zone->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border-radius: 16px; border: none;">
            
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" style="font-size: 1.5rem;">Editar Zona</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body pt-3">
                <form action="{{ route('zones.update', $zone) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-500 text-secondary">Nombre</label>
                        <input type="text" name="name" class="form-control form-control-lg" 
                               value="{{ old('name', $zone->name) }}" 
                               style="height: 50px; border-radius: 10px;" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-500 text-secondary">Monto por hora</label>
                        <input type="number" step="0.01" name="rate" class="form-control form-control-lg"
                               value="{{ old('rate', $zone->rate) }}" 
                               style="height: 50px; border-radius: 10px;" required>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-500" 
                                style="border-radius: 10px; font-size: 1rem; background: #0d6efd;">
                            Guardar
                        </button>
                        <button type="button" class="btn btn-secondary px-4 py-2 fw-500" 
                                data-bs-dismiss="modal"
                                style="border-radius: 10px; background: #6c757d; border: none;">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

</body>

</html>
