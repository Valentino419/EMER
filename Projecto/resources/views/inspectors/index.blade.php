<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inspectores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Listado de Inspectores</h1>

    <a href="{{ route('inspectors.create') }}" class="btn btn-success mb-3">+ Agregar Inspector</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nombre de Usuario</th>
            <th>Email</th>
            <th>Número de Placa</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($inspectors as $inspector)
            <tr>
                <td>{{ $inspector->id }}</td>
                <td>{{ $inspector->user->name }}</td>
                <td>{{ $inspector->user->email }}</td>
                <td>{{ $inspector->badge_number }}</td>
                <td>
                    <a href="{{ route('inspectors.edit', $inspector) }}" class="btn btn-sm btn-primary">Editar</a>
                    <form action="{{ route('inspectors.destroy', $inspector) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('¿Estás seguro de eliminar este inspector?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
