<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Calle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .container { max-width: 600px; margin: 30px auto; padding: 20px; }
        h1 { color: #1a3c6d; font-weight: 600; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .btn-primary { background-color: #4a90e2; border: none; padding: 8px 20px; border-radius: 5px; }
        .btn-primary:hover { background-color: #357abd; }
        .btn-secondary { background-color: #6c757d; border: none; padding: 8px 20px; border-radius: 5px; margin-right: 10px; }
        .btn-secondary:hover { background-color: #5a6268; }
        .alert-danger { border-radius: 5px; }
        .button-group { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Calle</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('street.update', $street->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $street->name) }}" required>
            </div>
            <div class="form-group">
                <label for="start_number">Número Inicial:</label>
                <input type="number" name="start_number" id="start_number" class="form-control" value="{{ old('start_number', $street->start_number) }}" required>
            </div>
            <div class="form-group">
                <label for="end_number">Número Final:</label>
                <input type="number" name="end_number" id="end_number" class="form-control" value="{{ old('end_number', $street->end_number) }}" required>
            </div>
            <div class="form-group">
                <label for="zone_id">Zona:</label>
                <select name="zone_id" id="zone_id" class="form-control" required>
                    <option value="">Selecciona una zona</option>
                    @foreach ($zones as $zone)
                        <option value="{{ $zone->id }}" {{ old('zone_id', $street->zone_id) == $zone->id ? 'selected' : '' }}>
                            {{ $zone->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="button-group">
                <a href="{{ route('zones.show', ['zone' => $street->zone_id]) }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</body>
</html>