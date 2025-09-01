<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMER - User Login</title>
    <style>
        body {
            background-color: #03040cff;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
            text-align: center;
            width: 350px;
            position: relative;
        }

        .login-container::before,
        .login-container::after {
            content: '';
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            height: 200px;
            width: 200px;
            background: linear-gradient(90deg, #00c6ff, #0072ff);
            z-index: -1;
            border-radius: 30px;
            opacity: 0.4;
        }

        .login-container::before { left: -120px; }
        .login-container::after { right: -120px; }

        .logo {
            margin-bottom: 20px;
        }

        h3 {
            margin-bottom: 10px;
            font-size: 22px;
            color: #333;
        }

        p.subtitle {
            color: #aaa;
            font-size: 14px;
            margin-bottom: 20px;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            color: #010000ff;
        }

        .remember {
            display: flex;
            align-items: center;
            justify-content: left;
            font-size: 14px;
            margin: 15px 0;
            color: #555;
        }

        button {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            border: none;
            padding: 12px 20px;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .links {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .links a {
            color: #0072ff;
            text-decoration: underline;
            margin: 0 5px;
        }

        .error, .status {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .error { color: #d9534f; }
        .status { color: #5cb85c; }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Logo centrado -->
        <div class="logo">
            <img src="{{ asset('imagen/logo-EMER.png') }}" alt="Logo EMER" width="150">
        </div>
        <h3>Bienvenido a EMER</h3>
        <p class="subtitle">Accede con tus credenciales</p>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
            <input type="password" name="password" placeholder="Password" required>

            <div class="remember">
                <input type="checkbox" id="remember" name="remember" style="margin-right: 8px;">
                <label for="remember">Recuérdame</label>
            </div>

            <button type="submit">LOGIN</button>
        </form>

        <div class="text-center mt-3">
            <p>¿No tienes cuenta?</p>
            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">
                Registrarse
            </a>
        </div>

        @if (Route::has('password.request'))
            <div class="links mt-3">
                <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            </div>
        @endif
    </div>

</body>
</html>
