<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Calles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 15px; }
        h1 { color: #1a3c6d; font-weight: 600; margin-bottom: 20px; }
        .table { border-radius: 10px; overflow: hidden; border: 1px solid #e0e4e8; }
        .table th { background-color: #4a90e2; color: white; font-weight: 600; padding: 12px; }
        .table td { padding: 12px; vertical-align: middle; }
        .btn-primary { background-color: #4a90e2; border: none; padding: 6px 12px; border-radius: 5px; }
        .btn-primary:hover { background-color: #357abd; }
        .alert-success { border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Gestión de Calles @if($zone_id) - Zona {{ $zone_id }} @endif</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif


        <a href="{{ route('street.create', ['zone_id' => $zone_id]) }}" class="btn btn-primary mb-3">Nueva Calle</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Número Inicial</th>
                    <th>Número Final</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($streets as $street)
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
                        <td colspan="5" class="text-center">No hay calles registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>