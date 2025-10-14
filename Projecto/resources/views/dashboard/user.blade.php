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
            height: 100%; /* Asegura que todas las tarjetas tengan la misma altura */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Centra verticalmente */
            align-items: center; /* Centra horizontalmente */
        }

        .card-menu:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .card-menu .card-body {
            padding: 25px;
            text-align: center; /* Centra el texto horizontalmente */
            flex-grow: 1; /* Ocupa el espacio disponible */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Centra verticalmente dentro del card-body */
            align-items: center; /* Centra horizontalmente dentro del card-body */
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
            text-align: center; /* Asegura centrado del título */
        }

        .btn {
            padding: 8px 20px;
            font-weight: 500;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: block; /* Asegura que el botón ocupe su propio espacio */
            margin: 0 auto; /* Centra el botón horizontalmente */
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

        #active-parking-widget {
            position: fixed;
            bottom: 0;
            right: 0;
            margin: 20px;
            padding: 15px;
            background-color: #4a90e2;
            color: white;
            border-radius: 8px;
            min-width: 200px;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: none; /* Oculto por defecto, se muestra con JS */
        }

        #active-parking-widget h5 {
            margin: 0 0 10px 0;
            font-size: 1.1em;
        }

        #active-parking-widget button {
            width: 100%;
            margin-top: 5px;
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

            .col-md-4,
            .col-md-6 {
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

            <!-- Fila combinada para Zonas e Historial -->
            <div class="col-md-6">
                <div class="card card-menu">
                    <div class="card-body">
                        <span class="emoji">🌍</span>
                        <h5 class="card-title">Zonas</h5>
                        <a href="{{ route('zone.index') }}" class="btn btn-primary">Gestionar Zonas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-menu">
                    <div class="card-body">
                        <span class="emoji">📋</span>
                        <h5 class="card-title">Historial de Estacionamientos</h5>
                        <a href="{{ route('parking.show') }}" class="btn btn-primary">Ver Historial</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventanita de estacionamiento activo (oculta por defecto) -->
        <div id="active-parking-widget">
            <h5>Estacionamiento Activo</h5>
            <p id="dashboard-timer">Cargando...</p>
            <button id="go-to-parking" class="btn btn-light btn-sm mt-2">Ver Detalles</button>
            <button id="end-parking" class="btn btn-danger btn-sm mt-2">Finalizar</button>
        </div>
    </div>

    <script>
        let dashboardTimeLeft = localStorage.getItem('parkingTimeLeft') ? parseInt(localStorage.getItem('parkingTimeLeft')) : 0;
        let dashboardTimerInterval;

        const activeParkingWidget = document.getElementById('active-parking-widget');
        if (dashboardTimeLeft > 0) {
            activeParkingWidget.style.display = 'block';
            updateDashboardTimer();
            dashboardTimerInterval = setInterval(updateDashboardTimer, 1000);
        }

        function updateDashboardTimer() {
            if (dashboardTimeLeft > 0) {
                dashboardTimeLeft--;
                localStorage.setItem('parkingTimeLeft', dashboardTimeLeft);
                const hours = Math.floor(dashboardTimeLeft / 3600);
                const minutes = Math.floor((dashboardTimeLeft % 3600) / 60);
                const seconds = dashboardTimeLeft % 60;
                document.getElementById('dashboard-timer').textContent = `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} restantes`;
            } else {
                clearInterval(dashboardTimerInterval);
                document.getElementById('dashboard-timer').textContent = 'Tiempo terminado';
                localStorage.removeItem('parkingTimeLeft');
                localStorage.removeItem('parkingSessionId');
                activeParkingWidget.style.display = 'none';
            }
        }

        document.getElementById('go-to-parking')?.addEventListener('click', function() {
            window.location.href = '{{ route('parking.create') }}';
        });

        document.getElementById('end-parking')?.addEventListener('click', function() {
            dashboardTimeLeft = 0;
            localStorage.removeItem('parkingTimeLeft');
            localStorage.removeItem('parkingSessionId');
            clearInterval(dashboardTimerInterval);
            activeParkingWidget.style.display = 'none';
            alert('Estacionamiento finalizado manualmente.');
        });
    </script>
</body>

</html>