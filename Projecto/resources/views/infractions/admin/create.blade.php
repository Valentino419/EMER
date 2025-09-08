<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Infracción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #1a3c6d;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>

<body>
<div class="container mt-4">
    <h2>Registrar Nueva Infracción</h2>

    <form action="{{ route('infractions.store') }}" method="POST" class="mt-3">
        @csrf

        <div class="mb-3">
            <label for="inspector_id" class="form-label">Inspector</label>
            <select name="inspector_id" id="inspector_id" class="form-select" required>
                <option value="" disabled selected>Selecciona un inspector</option>
                @foreach ($inspectors as $inspector)
                    <option value="{{ $inspector->id }}">{{ $inspector->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="car_id" class="form-label">Auto</label>
            <select name="car_id" id="car_id" class="form-select" required>
                <option value="" disabled selected>Selecciona un auto</option>
                @foreach ($cars as $car)
                    <option value="{{ $car->id }}">{{ $car->car_plate }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="fine" class="form-label">Monto de la Multa</label>
            <input type="number" name="fine" id="fine" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Fecha</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Estado</label>
            <input type="text" name="status" id="status" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Registrar</button>
        <a href="{{ route('infractions.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
