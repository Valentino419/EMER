<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lista de Autos</title>
</head>
<body>

    <h1>Autos registrados</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('cars.create') }}">Registrar nuevo auto</a>
    @csrf
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Patente</th>
                <th>Dueño</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cars as $car)
                <tr>
                    <td>{{ $car->id }}</td>
                    <td>{{ $car->car_plate }}</td>
                    <td>{{ $car->user->name ?? 'Sin usuario' }}</td>
                    <td>
                        <a href="{{ route('cars.edit', $car) }}">Editar</a>
                        <form action="{{ route('cars.destroy', $car) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Eliminar este auto?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
