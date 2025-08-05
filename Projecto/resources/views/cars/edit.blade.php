<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Auto</title>
</head>
<body>

    <h1>Editar Auto</h1>

    <form action="{{ route('cars.update', $car) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Patente:</label><br>
        <input type="text" name="car_plate" value="{{ $car->car_plate }}" required><br><br>

        <label>Dueño:</label><br>
        <select name="user_id" required>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $car->user_id == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select><br><br>

        <button type="submit">Actualizar</button>
    </form>

    <br>
    <a href="{{ route('cars.index') }}">Volver a la lista</a>

</body>
</html>
