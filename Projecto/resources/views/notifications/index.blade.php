<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Notificaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5 p-4 bg-white rounded shadow">
    <h2>Lista de Notificaciones</h2>
    <hr>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('notifications.create') }}" class="btn btn-primary mb-3">Crear Notificación</a>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Mensaje</th>
                <th>Usuario</th>
                <th>Leído</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($notifications as $notification)
                <tr>
                    <td>{{ $notification->id }}</td>
                    <td>{{ $notification->title }}</td>
                    <td>{{ $notification->message }}</td>
                    <td>{{ $notification->user->name ?? 'Sin usuario' }}</td>
                    <td>{{ $notification->read ? 'Sí' : 'No' }}</td>
                    <td>
                        <a href="{{ route('notifications.delete', $notification->id) }}" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay notificaciones registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
