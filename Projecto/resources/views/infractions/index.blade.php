<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Autos</title>
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
            /* más grande */
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
            <a href="{{ route('dashboard') }}" class="back-arrow" title="Volver al inicio">
                &#8592;
            </a>
            <h2 class="mb-4">Mis Infracciones</h2>
            <hr>

            {{-- Buscador por patente --}}
            <form method="GET" action="{{ route('infractions.index') }}" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por patente..."
                        value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </form>

            {{-- Mensaje de éxito --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Botón y tabla solo para admin e inspector --}}


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
                        @foreach ($infractions as $infraction)
                            <tr>
                                <td>{{ $infraction->id }}</td>
                                <td>{{ $infraction->car->car_plate }}</td>
                                <td>${{ $infraction->fine }}</td>
                                <td>{{ $infraction->date }}</td>
                                <td>{{ $infraction->status }}</td>
                                <td>
                                    <a href="{{ route('infractions.edit', $infraction) }}" class="btn btn-sm btn-primary">Editar</a>

                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
<!-- Modal para nueva infracción (igual que lo armamos antes) -->
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
                        {{-- Si querés autocompletar con las patentes del usuario: --}}
                        <input list="carsList" id="car_plate" name="car_plate" class="form-control" required>
                        <datalist id="carsList">
                            @if(isset($userCars))
                                @foreach($userCars as $id => $plate)
                                    <option value="{{ $plate }}"></option>
                                @endforeach
                            @endif
                        </datalist>
                    </div>

                    <div class="mb-3">
                        <label for="fine" class="form-label">Multa</label>
                        <input type="number" name="fine" id="fine" class="form-control" value="5000" readonly>
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

</body>
</html>