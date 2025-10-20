<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Notificación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5 p-4 bg-white rounded shadow">
    <h2>Crear Nueva Notificación</h2>
    <hr>
    <form action="{{ route('notifications.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mensaje</label>
            <textarea name="message" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Usuario</label>
            <select name="user_id" class="form-select" required>
                <option value="">Seleccionar usuario</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="read" value="1">
            <label class="form-check-label">Marcar como leída</label>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('notifications.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
