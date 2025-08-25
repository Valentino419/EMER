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
<div class="modal fade" id="editInspectorModal{{ $inspector->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
        <div class="modal-header">
            <h5 class="modal-title">Editar Inspector</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        
        
        <form action="{{ route('inspectors.update', $inspector) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">

        <div class="mb-3">
            <label for="user_id" class="form-label">Usuario</label>
            <select name="user_id" id="user_id" class="form-select" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $inspector->user_id ? 'selected' : '' }}>
                        {{ $user->name }} - {{ $user->email }}
                    </option>
                @endforeach
            </select>
        </div>
       
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" name="email" id="email" class="form-control"
                   value="{{ $inspector->email }}" required>
        </div>

        <div class="mb-3">
            <label for="badge_number" class="form-label">Número de Placa</label>
            <input type="text" name="badge_number" id="badge_number" class="form-control"
                   value="{{ $inspector->badge_number }}" required>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('inspectors.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
    </form>
</div>
</body>
</html>
