<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <a href="/login">login</a>
            <a href="/about">Acerca de</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2025 Mi Aplicación</p>
    </footer>

    <script src="{{ asset('js/app.tsx') }}"></script>
</body>
</html>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>EMER</title>
    <meta charset="UTF-8">
    <title>User Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      
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
            position: relative;
            width: 350px;
        }

        .login-container::before, .login-container::after {
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

        .login-container::before {
            left: -120px;
        }

        .login-container::after {
            right: -120px;
        }

        .icon {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            margin: -60px auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }

        h2 {
            margin-bottom: 10px;
            font-size: 24px;
            color: #333;
        }

        p.subtitle {
            color: #aaa;
            font-size: 14px;
            margin-bottom: 30px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            color: #010000ff

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

        .forgot {
            margin-top: 20px;
            font-size: 14px;
            color: #888;
            text-decoration: underline;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <div class="login-container">
        <div class="icon">
            👤
        </div>
        <h2>USER LOGIN</h2>
        <p class="subtitle">Welcome to the website</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="text" name="email" placeholder="USERNAME" required>
            <input type="password" name="password" placeholder="PASSWORD" required>

            <div class="remember">
                <input type="checkbox" id="remember" name="remember" style="margin-right: 8px;">
                <label for="remember">remember me</label>
            </div>

            <button type="submit">LOGIN</button>
        </form>
         
        <div class="register">
            <a href="{{ route('register') }}" 
            class="btn btn-outline-primary w-100">
            Registrarse
            </a>
        </div>

        <div class="forgot">
            Forgot password?
        </div>
    </div>
</body>
</html>