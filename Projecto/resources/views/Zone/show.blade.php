<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Zona: {{ $zone->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 15px; }
        h1 { color: #1a3c6d; font-weight: 600; margin-bottom: 20px; }
        h2 { color: #2c3e50; margin-top: 30px; }
        .table { border-radius: 10px; overflow: hidden; border: 1px solid #e0e4e8; }
        .table th { background-color: #4a90e2; color: white; font-weight: 600; padding: 12px; }
        .table td { padding: 12px; vertical-align: middle; }
        .btn-primary { background-color: #4a90e2; border: none; padding: 6px 12px; border-radius: 5px; }
        .btn-primary:hover { background-color: #357abd; }
        .btn-secondary { background-color: #6c757d; border: none; padding: 6px 12px; border-radius: 5px; }
        .btn-secondary:hover { background-color: #5a6268; }
        .alert-success { border-radius: 5px; margin-bottom: 20px; }
        .button-group { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Zona: {{ $zone->name }}</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="button-group">
            <a href="{{ route('zones.index') }}" class="btn btn-secondary">Volver a Zonas</a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver al Inicio</a>
            <a href="{{ route('street.create', ['zone_id' => $zone->id]) }}" class="btn btn-primary">Agregar Nueva Calle</a>
        </div>

        <h2>Calles Asociadas</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Inicio del Cobro</th>
                    <th>Fin del Cobro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($zone->streets as $street)
                    <tr>
                        <td>{{ $street->id }}</td>
                        <td>{{ $street->name }}</td>
                        <td>{{ $street->start_number }}</td>
                        <td>{{ $street->end_number }}</td>
                        <td>
                            <a href="{{ route('street.edit', $street) }}" class="btn btn-primary btn-sm">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay calles asociadas a esta zona.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>