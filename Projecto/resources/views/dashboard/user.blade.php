<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMER - Dashboard Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: linear-gradient(90deg, #4a90e2, #63b8ff);
            color: white;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .navbar .navbar-text,
        .navbar strong {
            color: white !important;
            font-size: 1.1em;
        }

        .navbar .btn {
            background-color: #fff;
            color: #4a90e2;
            border: none;
            padding: 5px 15px;
            font-weight: 500;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar .btn:hover {
            background-color: #e9ecef;
            color: #357abd;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 15px;
        }

        h1 {
            color: #1a3c6d;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .text-muted {
            font-size: 0.95em;
            margin-bottom: 30px;
        }

        .card-menu {
            border: none;
            border-radius: 15px;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-menu:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .card-menu .card-body {
            padding: 25px;
            text-align: center;
        }

        .card-menu .emoji {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #4a90e2;
        }

        .card-menu .card-title {
            font-size: 1.1em;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .btn {
            padding: 8px 20px;
            font-weight: 500;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary {
            background-color: #4a90e2;
            border: none;
        }

        .btn-primary:hover {
            background-color: #357abd;
            transform: translateY(-1px);
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 10px;
            }

            .navbar .navbar-text {
                font-size: 1em;
            }

            .row {
                flex-direction: column;
            }

            .col-md-4 {
                width: 100%;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <span class="navbar-text">
            Bienvenido, <strong>{{ Auth::user()->name ?? 'Usuario' }}</strong>
        </span>
        <form action="{{ route('logout') }}" method="POST" class="mb-0">
            @csrf
            <button type="submit" class="btn">Cerrar sesión</button>
        </form>
    </nav>

    <div class="container">
        <h1 class="text-center mb-4">Dashboard Usuario</h1>
        <p class="text-center text-muted mb-5">Consulta y gestiona tus autos, pagos y notificaciones.</p>

        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card card-menu">
                    <div class="card-body">
                        <span class="emoji">🚗</span>
                        <h5 class="card-title">Mis Autos</h5>
                        <a href="{{ route('cars.index') }}" class="btn btn-primary">Ver Autos</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-menu">
                    <div class="card-body">
                        <span class="emoji">🅿️</span>
                        <h5 class="card-title">Iniciar estacionamiento</h5>
                        <a href="{{ route('parking.create') }}" class="btn btn-success">Iniciar</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-menu">
                    <div class="card-body">
                        <span class="emoji">⚠️</span>
                        <h5 class="card-title">Multas</h5>
                        <a href="{{ route('infractions.index') }}" class="btn btn-primary">Ver Infracciones</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>