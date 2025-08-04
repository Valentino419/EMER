<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Crear Auto</title>
</head>
<body>

    <h1>Registrar un nuevo auto</h1>

    <form action="{{ route('cars.store') }}" method="POST">
        @csrf

        <label>Patente:</label><br>
        <input type="text" name="car_plate" required><br><br>

        <label>Dueño:</label><br>
        <select name="user_id" required>
            <option value="">Seleccionar usuario</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select><br><br>

        <button type="submit">Guardar</button>
    </form>

    <br>
    <a href="{{ route('cars.index') }}">Volver a la lista</a>

</body>
</html>
