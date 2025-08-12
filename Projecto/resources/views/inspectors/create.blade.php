<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Inspector</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Crear Nuevo Inspector</h1>

    <form action="{{ route('inspectors.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="user_id" class="form-label">Usuario</label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">Seleccionar usuario</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="badge_number" class="form-label">Número de Placa</label>
            <input type="text" name="badge_number" id="badge_number" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('inspectors.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
