<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Inspector</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

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
    </style>
<body>
<div class="container">
    <h2 class="mb-4">Nueva Zonas</h2>

    <form action="{{ route('zone.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input  type="text" name="name" id="name" class="form-select" required>
        </div>
        
        <div class="mb-3">
            <label for="numeration" class="form-label">Numeracion</label>
            <input type="text" name="numeration" id="numeration" class="form-select" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('zone.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
