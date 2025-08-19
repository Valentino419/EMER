<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMER - Dashboard Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f5fa; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, #0072ff, #00c6ff); color: white; padding-top: 20px; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 10px 15px; border-radius: 8px; margin: 5px 0; }
        .sidebar a:hover { background-color: rgba(255, 255, 255, 0.2); }
        .content { padding: 20px; }
        .navbar { background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar">
            <h4 class="text-center mb-4">Menú Usuario</h4>
            <a href="{{ route('dashboard.user') }}">🏠 Inicio</a>
            <a href="{{ route('cars.index') }}">🚗 Mis Autos</a>
            <a href="{{ route('payment.index') }}">💳 Pagos</a>
            <a href="{{ route('logout') }}">🚪 Cerrar sesión</a>
        </div>

        <div class="col-md-10">
            <nav class="navbar navbar-light justify-content-between px-4">
                <span class="navbar-text">
                    Bienvenido, <strong>{{ Auth::user()->name ?? 'Usuario' }}</strong>
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">Cerrar sesión</button>
                </form>
            </nav>

            <div class="content">
                <h1 class="mb-4">Dashboard Usuario</h1>
                <p>Panel de control para usuarios. Consulta tus autos, pagos y notificaciones.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
