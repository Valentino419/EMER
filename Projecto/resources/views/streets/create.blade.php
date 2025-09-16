<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Calle</title>
</head>
<body>
    <h1>Crear Nueva Calle</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('street.store') }}" method="POST">
        @csrf
        <div>
            <label>Nombre:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label>Número Inicial:</label>
            <input type="number" name="start_number" required>
        </div>
        <div>
            <label>Número Final:</label>
            <input type="number" name="end_number" required>
        </div>
        <div>
            <label>Zona:</label>
            <select name="zone_id" required>
                <option value="">Selecciona una zona</option>
                @foreach ($zones as $zone)
                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit">Crear</button>
    </form>
</body>
</html>