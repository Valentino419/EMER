<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EMER - Dashboard Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff !important;
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
            background-color: #ffffff !important;
        }

        h1 {
            color: #1a3c6d;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .text-muted {
            font-size: 0.95em;
            margin-bottom: 30px;
            color: #6b7280;
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

        .card-menu .btn {
            padding: 8px 20px;
            font-weight: 500;
            border-radius: 5px;
            border: 2px solid rgba(0, 0, 0, 0.15);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-menu .btn-primary {
            background-color: #4a90e2;
            border-color: rgba(0, 0, 0, 0.15);
            color: white;
        }

        .card-menu .btn-primary:hover {
            background-color: #357abd;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
        }

        .card-menu .btn-success {
            background-color: #28a745;
            border-color: rgba(0, 0, 0, 0.15);
            color: white;
        }

        .card-menu .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
        }

        .btn-light {
            background-color: #e9ecef;
            color: #2c3e50;
            border: none;
        }

        .btn-light:hover {
            background-color: #d1d9e6;
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background-color: #b02a37;
            transform: translateY(-2px);
        }

        .active-sessions-section {
            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .active-sessions-section h3 {
            color: #1a3c6d;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.3em;
            width: 100%;
        }

        .active-parking-widget {
            max-width: 350px;
            flex: 1 1 auto;
            padding: 15px;
            background-color: #bad8ff !important;
            color: #2c3e50;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .active-parking-widget:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
        }

        .active-parking-widget h5 {
            margin: 0 0 10px 0;
            font-size: 1.1em;
            font-weight: 600;
            color: #1a3c6d;
        }

        .active-parking-widget p {
            margin: 5px 0;
            font-size: 0.9em;
        }

        .active-parking-widget button {
            width: 100%;
            margin-top: 5px;
            font-family: 'Segoe UI', sans-serif;
        }

        .emoji {
            margin-right: 6px;
            font-size: 1em;
        }

        .alert-success,
        .alert-danger {
            border-radius: 8px;
            margin-bottom: 20px;
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

            .active-sessions-section {
                flex-direction: column;
                gap: 10px;
            }

            .active-parking-widget {
                width: 90%;
                margin-left: auto;
                margin-right: auto;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <span class="navbar-text">
            Bienvenido, <strong>{{ $user->name ?? 'Usuario' }}</strong>
        </span>
        <form action="{{ route('logout') }}" method="POST" class="mb-0">
            @csrf
            <button type="submit" class="btn">Cerrar sesión</button>
        </form>
    </nav>

    <div class="container">
        <h1 class="text-center mb-4">{{ $data['title'] }}</h1>
        <p class="text-center text-muted mb-5">Consulta y gestiona tus autos, pagos y notificaciones.</p>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4 justify-content-center">
            @foreach ($data['widgets'] as $widget)
                <div class="col-md-4">
                    <div class="card card-menu">
                        <div class="card-body">
                            <span class="emoji">
                                @if ($widget['name'] == 'Mis Autos') 🚗
                                @elseif ($widget['name'] == 'Iniciar Estacionamiento') 🅿️
                                @elseif ($widget['name'] == 'Multas') ⚠️
                                @elseif ($widget['name'] == 'Zonas') 🌍
                                @elseif ($widget['name'] == 'Historial de Estacionamientos') 📋
                                @else 🛠️
                                @endif
                            </span>
                            <h5 class="card-title">{{ $widget['name'] }}</h5>
                            <a href="{{ $widget['link'] }}" class="btn {{ $widget['name'] == 'Iniciar Estacionamiento' ? 'btn-success' : 'btn-primary' }}">Ir</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sesiones activas -->
        @if(isset($data['activeSessions']) && $data['activeSessions']->isNotEmpty())
            <div class="active-sessions-section">
                <h3>Sesiones de Estacionamiento Activas</h3>
                @foreach ($data['activeSessions'] as $session)
                    <div id="active-parking-widget-{{ $session->id }}" class="active-parking-widget">
                        <h5><span class="emoji">🚗</span> Estacionamiento Activo</h5>
                        <p><strong>Vehículo:</strong> {{ $session->car->license_plate ?? $session->car->car_plate }}</p>
                        <p><strong>Zona:</strong> {{ $session->street->zone->name }}</p>
                        <p><strong>Calle:</strong> {{ $session->street->name }}</p>
                        <p><strong>Hora de inicio:</strong> {{ $session->start_time->format('d/m/Y H:i') }}</p>
                        <p><strong>Duración:</strong> {{ $session->duration }} minutos</p>
                        <p><strong>Monto:</strong> ${{ number_format($session->amount, 2) }}</p>
                        <p id="dashboard-timer-{{ $session->id }}">Cargando...</p>
                        <button class="btn btn-light btn-sm mt-2" onclick="window.location.href='{{ route('parking.show') }}'">Ver Detalles</button>
                        <form id="end-parking-form-{{ $session->id }}" action="{{ route('parking.end', $session->id) }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-danger btn-sm mt-2">Finalizar</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        // Temporizadores para sesiones activas
        const timers = {};
        @if(isset($data['activeSessions']) && $data['activeSessions']->isNotEmpty())
            @foreach ($data['activeSessions'] as $session)
                timers[{{ $session->id }}] = {
                    startTime: new Date('{{ $session->start_time->toIso8601String() }}').getTime(),
                    duration: {{ $session->duration }},
                    interval: null
                };
                (function(sessionId) {
                    const widget = document.getElementById('active-parking-widget-' + sessionId);
                    const timerElement = document.getElementById('dashboard-timer-' + sessionId);
                    const now = new Date().getTime();
                    const endTime = timers[sessionId].startTime + timers[sessionId].duration * 60 * 1000;
                    let timeLeft = Math.floor((endTime - now) / 1000);
                    if (timeLeft > 0) {
                        widget.style.display = 'block';
                        timers[sessionId].timeLeft = timeLeft;
                        timers[sessionId].interval = setInterval(() => updateTimer(sessionId, timerElement, widget), 1000);
                    } else {
                        widget.style.display = 'none';
                        expireSession(sessionId);
                    }
                })({{ $session->id }});
            @endforeach
        @endif

        function updateTimer(sessionId, timerElement, widget) {
            if (timers[sessionId].timeLeft > 0) {
                timers[sessionId].timeLeft--;
                const hours = Math.floor(timers[sessionId].timeLeft / 3600);
                const minutes = Math.floor((timers[sessionId].timeLeft % 3600) / 60);
                const seconds = timers[sessionId].timeLeft % 60;
                timerElement.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} restantes`;
            } else {
                clearInterval(timers[sessionId].interval);
                timerElement.textContent = 'Tiempo terminado';
                widget.style.display = 'none';
                expireSession(sessionId);
            }
        }

        function expireSession(sessionId) {
            fetch(`/parking/expire/${sessionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('El tiempo de estacionamiento ha terminado.');
                  } else {
                      console.error('Error al expirar sesión:', data.message);
                  }
              }).catch(error => {
                  console.error('Error de red al expirar sesión:', error);
              });
        }

        // Manejar finalización de estacionamientos
        @if(isset($data['activeSessions']) && $data['activeSessions']->isNotEmpty())
            @foreach ($data['activeSessions'] as $session)
                document.getElementById('end-parking-form-{{ $session->id }}').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        });
                        const data = await response.json();
                        if (response.ok && data.success) {
                            alert(data.message);
                            clearInterval(timers[{{ $session->id }}].interval);
                            document.getElementById('active-parking-widget-{{ $session->id }}').style.display = 'none';
                            window.location.reload();
                        } else {
                            alert(data.message || `Error desconocido al finalizar el estacionamiento. Código: ${response.status}`);
                            console.error('Respuesta del servidor:', data, 'Estado:', response.status);
                        }
                    } catch (error) {
                        console.error('Error al finalizar estacionamiento:', error);
                        alert(`Error al finalizar el estacionamiento: ${error.message}`);
                    }
                });
            @endforeach
        @endif
    </script>
</body>
</html>