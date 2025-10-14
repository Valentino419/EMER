<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Infracción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 800px;
            margin-top: 40px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #1a3c6d;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 500;
            color: #333;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            font-weight: 500;
            border-radius: 6px;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 10px 20px;
            font-weight: 500;
            border-radius: 6px;
        }
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
        .alert-danger {
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('infractions.index') }}" class="back-arrow" title="Volver al listado">
            &#8592;
        </a>
        <h1 class="mb-4">Editar Infracción </h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('infractions.update', $infraction) }}">
            @csrf
            @method('PATCH')
            <div class="mb-3">
                <label for="car_id" class="form-label">Auto</label>
                <select class="form-control" id="car_plate" name="car_plate" required>
                    <option value="">Selecciona un auto</option>
                    @foreach($cars as $car)
                        <option value="{{ $car->car_plate }}" {{ old('car_plate', $infraction->car_id) == $car->id ? 'selected' : '' }}>
                            {{ $car->car_plate }} ({{ $car->user->name ?? 'Sin usuario' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="fine" class="form-label">Monto de la Multa</label>
                <input type="number" class="form-control" id="fine" name="fine" value="{{ old('fine', $infraction->fine) }}" min="0" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $infraction->date) }}" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Estado</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="pending" {{ old('status', $infraction->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="paid" {{ old('status', $infraction->status) == 'paid' ? 'selected' : '' }}>Pagada</option>
                    <option value="cancelled" {{ old('status', $infraction->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $infraction->description ?? '') }}</textarea>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('infractions.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>