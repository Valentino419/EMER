<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear zona de estacionamiento</title>
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

        .form-control {
            height: 50px;
            border-radius: 10px;
            font-size: 1rem;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">Nueva Zona</h2>

    <!-- Mostrar errores si hay -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('zones.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nombre de la zona</label>
            <input type="text" name="name" id="name" class="form-control" 
                   value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="rate" class="form-label">Monto de la Zona por hora </label>
            <input type="number" name="rate" id="rate" class="form-control" 
                   min="0" 
                   value="{{ old('rate') }}" 
                   placeholder="1500" required>
            @error('rate')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Guardar Zona</button>
            <a href="{{ route('zones.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>