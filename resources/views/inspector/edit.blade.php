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


                <form action="{{ route('inspectors.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="surname">Apellido</label>
                        <input type="text" name="surname" value="{{ old('surname', $user->surname) }}" required>
                        @error('surname')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="dni">DNI</label>
                        <input type="text" name="dni" value="{{ old('dni', $user->dni) }}" required>
                        @error('dni')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="email">Correo</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="password">Contraseña (dejar en blanco para no cambiar)</label>
                        <input type="password" name="password">
                        @error('password')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation">
                    </div>
                    <div>
                        <label for="role_id">Rol</label>
                        <select name="role_id" required>
                            @foreach ($roles as $id => $name)
                                <option value="{{ $id }}"
                                    {{ old('role_id', $user->role_id) == $id ? 'selected' : '' }}>{{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit">Actualizar</button>
                </form>
            </div>
</body>
</html>
