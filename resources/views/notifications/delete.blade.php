<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Notificación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5 p-4 bg-white rounded shadow">
    <h2 class="text-danger">Confirmar Eliminación</h2>
    <p>¿Estás seguro de que quieres eliminar esta notificación?</p>
    <ul>
        <li><strong>Título:</strong> {{ $notification->title }}</li>
        <li><strong>Mensaje:</strong> {{ $notification->message }}</li>
    </ul>

    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Eliminar</button>
        <a href="{{ route('notifications.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
