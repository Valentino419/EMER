<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Infracciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Listado de Infracciones</h2>

    <a href="{{ route('infractions.create') }}" class="btn btn-primary mb-3">Nueva Infracción</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Inspector</th>
                <th>Auto</th>
                <th>Multa</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($infractions as $infraction)
            <tr>
                <td>{{ $infraction->id }}</td>
                <td>{{ $infraction->inspector->name }}</td>
                <td>{{ $infraction->car->car_plate }}</td>
                <td>${{ $infraction->fine }}</td>
                <td>{{ $infraction->date }}</td>
                <td>{{ $infraction->status }}</td>
                <td>
                    <a href="{{ route('infractions.edit', $infraction) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('infractions.destroy', $infraction) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Estás seguro de eliminar esta infracción?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
