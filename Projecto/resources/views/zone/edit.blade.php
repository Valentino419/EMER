<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Zona</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
</head>
<body>

<div class="modal-card">
    <div class="modal-header">
        <h5 class="modal-title">Editar Zona</h5>
    </div>

    <form action="{{ route('zones.store') }}" method="POST">
        @csrf

        <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Ej: Centro" required autofocus>
            </div>

          <div class="mb-3">
                <label for="rate" class="form-label">Monto por hora</label>
                <input type="number" step="0.01" name="rate" id="rate" class="form-control" placeholder="0.00" required>
            </div>
        </div>

       
        <button type="submit" class="btn btn-primary">Actualizar</button>   
        <a href="{{ route('zones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>