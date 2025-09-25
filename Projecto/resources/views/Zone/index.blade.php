<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Zonas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .alert-success {
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">Gestión de Zonas</h1>

        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver al Inicio</a>
        
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif


        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cantidad de Calles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($zones as $zone)
                    <tr>
                        <td>{{ $zone->id }}</td>
                        <td>{{ $zone->name }}</td>
                        <td>{{ $zone->streets->count() }}</td>
                        <td>
                            <a href="{{ route('zone.show', $zone) }}" class="btn btn-primary btn-sm">Ver Calles</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay zonas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
