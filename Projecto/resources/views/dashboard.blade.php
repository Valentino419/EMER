<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMER - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f5fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #0072ff, #00c6ff);
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 5px 0;
        }
        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .content {
            padding: 20px;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <h4 class="text-center mb-4">Menú</h4>
            <a href="{{ route('dashboard') }}">🏠 Inicio</a>
            <a href="{{ route('cars.index') }}">🚗 Autos</a>
            <a href="{{ route('inspectors.index') }}">🕵️ Inspectores</a>
            <a href="{{ route('infractions.index') }}">⚠️ Infracciones</a>
            <a href="{{ route('payment.index') }}"> Registrar Estacionamiento </a>
            <a href="{{ route('logout') }}">🚪 Cerrar sesión</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <!-- Navbar -->
            <nav class="navbar navbar-light justify-content-between px-4">
                <span class="navbar-text">
                    Bienvenido, <strong>{{ Auth::user()->name ?? 'Invitado' }}</strong>
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">Cerrar sesión</button>
                </form>
            </nav>

            <!-- Page Content -->
            <div class="content">
                <h1 class="mb-4">Dashboard</h1>
                <p>Bienvenido a tu panel de control. Desde aquí puedes navegar por todas las secciones del sistema.</p>
                <!-- Aquí puedes poner tarjetas, gráficos, etc. -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Autos</h5>
                                <p class="card-text">Gestiona todos los autos registrados.</p>
                                <a href="{{ route('cars.index') }}" class="btn btn-primary">Ver</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Inspectores</h5>
                                <p class="card-text">Administra la lista de inspectores.</p>
                                <a href="{{ route('inspectors.index') }}" class="btn btn-primary">Ver</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Infracciones</h5>
                                <p class="card-text">Consulta y gestiona las infracciones.</p>
                                <a href="{{ route('infractions.index') }}" class="btn btn-primary">Ver</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Estacionamiento</h5>
                                <p class="card-text">Registrar un nuevo estacionamiento.</p>
                                <a href="{{ route('parking.create') }}" class="btn btn-primary">Ver</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
