<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Zona: {{ $zone->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Zona: {{ $zone->name }}</h1>

      

        <div class="button-group">
            <a href="{{ route('zones.index') }}" class="btn btn-secondary">Volver a Zonas</a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver al Inicio</a>
            @if(Auth::check() && in_array(strtolower(Auth::user()->role->name ?? ''), ['admin', 'inspector']))
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
                    @if(Auth::check() && in_array(strtolower(Auth::user()->role->name ?? ''), ['admin', 'inspector']))
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
                        @if(Auth::check() && in_array(strtolower(Auth::user()->role->name ?? ''), ['admin', 'inspector']))
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
                        <td colspan="@if(Auth::check() && in_array(strtolower(Auth::user()->role->name ?? ''), ['admin', 'inspector'])) 5 @else 4 @endif" class="text-center">No hay calles asociadas a esta zona.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h2>Mapa de la Zona con Calles de Cobro</h2>
        <div id="map"></div>
    </div>

  <div id="map"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Inicializar el mapa en el centro de Gualeguaychú
    var map = L.map('map').setView([-33.007, -58.518], 16);

    // Agregar capa base de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Asegurar altura del mapa
    document.getElementById('map').style.height = '500px';

    // Perímetro del centro (rectángulo entre Rocamora y Seguí)
    var perimeterCoords = [
        [-33.0080, -58.5210],  // Noroeste (Seguí y Luis N. Palma)
        [-33.0080, -58.5150],  // Noreste (Rocamora y Luis N. Palma)
        [-33.0055, -58.5150],  // Sureste (Rocamora y Urquiza)
        [-33.0055, -58.5210],  // Suroeste (Seguí y Urquiza)
        [-33.0080, -58.5210]   // Cierra
    ];

    L.polygon(perimeterCoords, {
        color: 'blue',
        fillColor: 'blue',
        fillOpacity: 0.1,
        weight: 2,
        opacity: 0.6
    }).addTo(map).bindPopup('<b>Zona Centro: {{ $zone->name }}</b>');

    // Ajustar vista al perímetro
    map.fitBounds(perimeterCoords);

    // Coordenadas reales para las calles
    var streetCoords = {
        'san martín': [[-33.0072, -58.5205], [-33.0072, -58.5160]],
        '25 de mayo': [[-33.0070, -58.5200], [-33.0068, -58.5165]],
        'urquiza': [[-33.0065, -58.5208], [-33.0065, -58.5168]],
        'luis n. palma': [[-33.0078, -58.5202], [-33.0078, -58.5162]],
        'rocamora': [[-33.0080, -58.5160], [-33.0055, -58.5160]],  // Límite este
        'seguí': [[-33.0080, -58.5205], [-33.0055, -58.5205]]      // Límite oeste
    };

    // Aliases para nombres de calles
    var streetAliases = {
        'calle san martín': 'san martin',
        'calle 25 de mayo': '25 de mayo',
        'calle urquiza': 'urquiza',
        'calle luis n. palma': 'luis n. palma',
        'rocamora': 'rocamora',
        'seguí': 'segui'
    };

    // Depuración: mostrar calles en la DB
    var dbStreets = [];
    @if($zone->streets->isNotEmpty())
        @foreach($zone->streets as $street)
            dbStreets.push('{{ $street->name }}');
        @endforeach
    @else
        dbStreets = [];
    @endif
    console.log('Calles en la DB:', dbStreets);

    // Dibujar calles de cobro (rojo) y límites (verde)
    @if($zone->streets->isNotEmpty())
        @foreach($zone->streets as $street)
            (function() {
                var streetName = '{{ $street->name }}'.trim().toLowerCase().replace(/\s+/g, ' ');
                var normalizedName = streetAliases[streetName] || streetName;
                var coords = streetCoords[normalizedName] || null;
                if (coords) {
                    var lineColor = (normalizedName === 'rocamora' || normalizedName === 'seguí') ? 'green' : 'red';
                    L.polyline(coords, {
                        color: lineColor,
                        weight: 5,
                        opacity: 0.8
                    }).addTo(map).bindPopup('<b>{{ $street->name }}</b><br>Entre Rocamora y Seguí<br>Inicio: {{ $street->start_number }} - Fin: {{ $street->end_number }}');
                } else {
                    console.warn('No se encontraron coordenadas para: ' + streetName + ' (normalizado: ' + normalizedName + ')');
                }
            })();
        @endforeach
    @else
        console.log('No hay calles en la DB. Dibujando las especificadas.');
        ['San Martín', '25 de Mayo', 'Urquiza', 'Luis N. Palma'].forEach(function(name) {
            var normalizedName = name.toLowerCase().replace(/\s+/g, ' ');
            var coords = streetCoords[normalizedName];
            if (coords) {
                L.polyline(coords, {
                    color: 'red',
                    weight: 5,
                    opacity: 0.8
                }).addTo(map).bindPopup('<b>' + name + '</b><br>Entre Rocamora y Seguí');
            }
        });
        L.polyline(streetCoords['rocamora'], {color: 'green', weight: 4}).addTo(map).bindPopup('Límite: Rocamora');
        L.polyline(streetCoords['seguí'], {color: 'green', weight: 4}).addTo(map).bindPopup('Límite: Seguí');
    @endif
</script>


    <!-- Depuración: Rol actual (mantener por compatibilidad) -->
    @if(Auth::check())
        <p style="display: none;">Rol: {{ Auth::user()->role->name ?? 'Sin rol' }}</p>
    @endif
    
</body>
</html>