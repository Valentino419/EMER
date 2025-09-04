<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Inspector</title>
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
    
        <h5 class="modal-title">Editar Inspector</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
    
        <form action="{{ route('zone.update', $zone) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">

       
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $zone->name}}" required>
        </div>

        <div class="mb-3">
            <label for="numeration" class="form-label">Numeracion</label>
            <input type="text" name="numeration" id="numeration" class="form-control" value="{{ $zone->numeration }}" required>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('zone.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
    </form>
</body>