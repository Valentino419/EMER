<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMER - Dashboard Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background: linear-gradient(90deg, #4a90e2, #63b8ff);
            color: white;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .navbar .navbar-text, .navbar strong {
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
        }

        .navbar .btn:hover {
            background-color: #e9ecef;
            color: #357abd;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
        }

        h1 {
            color: #1a3c6d;
            font-weight: 600;
        }

        .card-menu {
            border: none;
            border-radius: 15px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .card-menu:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .emoji {
            font-size: 2.5rem;
            color: #4a90e2;
        }
    </style>
</head>

<body>
    <nav class="navbar d-flex justify-content-between align-items-center">
        <span class="navbar-text">
            Bienvenido, <strong>{{ Auth::user()->name ?? 'Usuario' }}</strong>
        </span>

        <form action="{{ route('logout') }}" method="POST" class="mb-0">
            @csrf
            <button type="submit" class="btn">Cerrar sesión</button>
        </form>
    </nav>

    <div class="container text-center mt-4">
        <h1>Dashboard Usuario</h1>
        <p class="text-muted mb-5">Consulta y gestiona tus autos, pagos y notificaciones.</p>

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
                        <h5 class="card-title">Iniciar Estacionamiento</h5>
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

            <div class="col-md-6">
                <div class="card card-menu">
                    <div class="card-body">
                        <span class="emoji">🌍</span>
                        <h5 class="card-title">Zonas</h5>
                        <a href="{{ route('zone.index') }}" class="btn btn-primary">Gestionar Zonas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($unreadCount >= 0)
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        Swal.fire({
            icon: 'warning',
            title: '¡Tienes una nueva infracción!',
            text: 'Por favor revisa tus notificaciones.',
            confirmButtonText: 'Ver ahora',
            confirmButtonColor: '#3085d6',
            });
    </script>
@endif

</body>
</html>
