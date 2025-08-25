<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #0d6efd;
        }
        .btn-primary {
            background: #0d6efd;
            border: none;
        }
        .btn-primary:hover {
            background: #0b5ed7;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Crear Cuenta</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Nombre --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Apellido --}}
            <div class="mb-3">
                <label for="surname" class="form-label">Apellido</label>
                <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" 
                       name="surname" value="{{ old('surname') }}" required>
                @error('surname')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- DNI --}}
            <div class="mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input id="dni" type="number" class="form-control @error('dni') is-invalid @enderror" 
                       name="dni" value="{{ old('dni') }}" required>
                @error('dni')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Correo --}}
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Contraseña --}}
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirmar Contraseña --}}
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" class="form-control" 
                       name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Registrarse</button>

            <div class="text-center mt-3">
                <p>¿Ya tienes cuenta?</p>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">
                    Iniciar Sesión
                </a>
            </div>
        </form>
    </div>
</body>
</html>
