<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMER - Verifica tu correo</title>
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
        .logo { margin-bottom: 20px; }
        h3 { margin: 10px 0; font-size: 22px; color: #333; }
        p { color: #aaa; font-size: 14px; margin: 15px 0; }
        button {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin: 10px 0;
        }
        .links a { color: #0072ff; text-decoration: underline; font-size: 14px; }
        .status { color: #5cb85c; font-weight: bold; font-size: 15px; }
    </style>
</head>
<body>

<div class="login-container">
    <div class="logo">
        <img src="{{ asset('imagen/logo-EMER.png') }}" alt="EMER" width="150">
    </div>
    <h3>Verifica tu correo</h3>
    <p>Te enviamos un enlace a:<br>
        <strong>{{ auth()->user()->email }}</strong>
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="status">¡Listo! Revisa tu correo (y spam).</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">
            Reenviar correo
        </button>
    </form>

    <div class="links">
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none;border:none;color:#0072ff;padding:0;font-size:14px">
                Cerrar sesión
            </button>
        </form>
        &nbsp;&nbsp;
    </div>
</div>
</body>
</html>