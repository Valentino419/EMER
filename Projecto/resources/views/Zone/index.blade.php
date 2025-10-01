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
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">
            @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'inspector'))
                Gestión de Zonas
            @else
                Mis Zonas
            @endif
        </h1>

        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver al Inicio</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Botón de crear solo para admin/inspector -->
        @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'inspector'))
            <a href="{{ route('zones.create') }}" class="btn btn-primary mb-3">Nueva Zona</a>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cantidad de Calles</th>
                    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'inspector'))
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($zones as $zone)
                    <tr>
                        <td>{{ $zone->id }}</td>
                        <td>{{ $zone->name }}</td>
                        <td>{{ $zone->streets->count() }}</td>
                        @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'inspector'))
                            <td>
                                <a href="{{ route('zones.show', $zone) }}" class="btn btn-primary btn-sm">Ver Calles</a>
                                <a href="{{ route('zones.edit', $zone) }}" class="btn btn-primary btn-sm">Editar</a>
                                <form action="{{ route('zones.destroy', $zone) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?');">Eliminar</button>
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
                        <td colspan="@if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'inspector')) 4 @else 3 @endif" class="text-center">No hay zonas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>