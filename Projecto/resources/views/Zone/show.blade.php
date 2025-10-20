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
        #map { height: 500px; width: 100%; border-radius: 10px; margin-top: 20px; }
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
        <h1 class="text-center mb-4">Zona: {{ $zone->name }}</h1>

        <div class="button-group">
            <a href="{{ route('dashboard') }}" class="back-arrow" title="Volver al inicio" aria-label="Volver al inicio">
            &#8592;
        </a>
            <a href="{{ route('zones.index') }}" class="btn btn-secondary">Volver a Zonas</a>
            @if(Auth::check() && in_array(strtolower(Auth::user()->role?->name ?? ''), ['admin', 'inspector']))
                <a href="{{ route('street.create', ['zone_id' => $zone->id]) }}" class="btn btn-primary">Agregar Nueva Calle</a>
            @endif
        </div>

        <h2>Calles Asociadas</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Inicio del Cobro</th>
                    <th>Fin del Cobro</th>
                    @if(Auth::check() && in_array(strtolower(Auth::user()->role?->name ?? ''), ['admin', 'inspector']))
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($zone->streets as $street)
                    <tr>
                        <td>{{ $street->id }}</td>
                        <td>{{ $street->name }}</td>
                        <td>{{ $street->start_number }}</td>
                        <td>{{ $street->end_number }}</td>
                        @if(Auth::check() && in_array(strtolower(Auth::user()->role?->name ?? ''), ['admin', 'inspector']))
                            <td>
                                <a href="{{ route('street.edit', $street) }}" class="btn btn-primary btn-sm">Editar</a>
                                <form action="{{ route('street.destroy', $street) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta calle?');">Eliminar</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="@if(Auth::check() && in_array(strtolower(Auth::user()->role?->name ?? ''), ['admin', 'inspector'])) 5 @else 4 @endif" class="text-center">No hay calles asociadas a esta zona.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h2>Mapa de la Zona</h2>
        <iframe
            id="map"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12634.123456789012!2d-58.518!3d-33.007!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95c12a2b2b2b2b2b%3A0x1234567890abcdef!2zR3VhbGVndWF5Y2jDqSwgRW50cmUgU8OzYXM!5e0!3m2!1ses!2sar!4v1696848000000"
            width="100%"
            height="500"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

    <!-- Depuración: Rol actual -->
    @if(Auth::check())
        <p style="display: none;">Rol: {{ Auth::user()->role?->name ?? 'Sin rol' }}</p>
    @endif
</body>
</html>