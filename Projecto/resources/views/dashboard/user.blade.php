
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMER - Dashboard Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f5fa; font-family: 'Segoe UI', sans-serif; }
        .navbar { background: linear-gradient(90deg, #00c6ff, #0072ff); color: white; }
        .navbar .navbar-text, .navbar strong { color: white !important; }
        .card-menu {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        .card-menu:hover { transform: translateY(-5px); }
        .card-menu .card-body {
            text-align: center;
            padding: 30px;
        }
        .card-menu i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #0072ff;
        }
    </style>
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
</head>
<body>

<nav class="navbar px-4">
    <span class="navbar-text">
        Bienvenido, <strong>{{ Auth::user()->name ?? 'Usuario' }}</strong>
    </span>
    <form action="{{ route('logout') }}" method="POST" class="mb-0">
        @csrf
        <button class="btn btn-light btn-sm">Cerrar sesión</button>
    </form>
</nav>

<div class="container my-5">
    <h1 class="text-center mb-4">Dashboard Usuario</h1>
    <p class="text-center text-muted mb-5">Consulta y gestiona tus autos, pagos y notificaciones.</p>

    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="card card-menu">
                <div class="card-body">
                    <i class="fas fa-car"></i>
                    <h5 class="card-title">Mis Autos</h5>
                    <a href="{{ route('cars.index') }}" class="btn btn-primary mt-3">Ver Autos</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card card-menu">
                <div class="card-body">
                    <i class="fas fa-credit-card"></i>
                    <h5 class="card-title">Iniciar estacionamiento</h5>
                    <a href="{{ route('parking.create') }}" class="btn btn-success mt-3">Iniciar</a>
                </div>
