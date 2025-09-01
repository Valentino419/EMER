
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMER - Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f5fa; }
        .navbar { background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card-menu {
            transition: all 0.2s ease-in-out;
            border-radius: 15px;
            text-align: center;
            padding: 25px;
        }
        .card-menu:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .card-menu i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #0072ff;
        }
    </style>
</head>
<body>

<nav class="navbar px-4">
    <span class="navbar-text">
        Bienvenido, <strong>{{ Auth::user()->name ?? 'Admin' }}</strong>
    </span>
    <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-outline-danger btn-sm">Cerrar sesión</button>
    </form>
</nav>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Dashboard Administrador</h1>
    <p class="text-center">Selecciona una opción para gestionar el sistema:</p>

    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <a href="{{ route('cars.index') }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm card-menu">
                    <i>🚗</i>
                    <h5>Autos</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="{{ route('inspectors.index') }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm card-menu">
                    <i>🕵️</i>
                    <h5>Inspectores</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="{{ route('infractions.index') }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm card-menu">
                    <i>⚠️</i>
                    <h5>Infracciones</h5>
                </div>
            </a>
        </div>
