
@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #ffffff !important;
            font-family: 'Segoe UI', sans-serif;
        }

        .custom-card {
            max-width: 900px;
            margin: 40px auto;
            padding: 25px;
            background-color: #ffffff !important;
            border: 2px solid rgba(0, 0, 0, 0.15);
            border-radius: 15px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .custom-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }

        h2 {
            color: #1a3c6d;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 1.6em;
        }

        label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 6px;
            display: block;
            font-size: 0.95em;
        }

        select,
        input[type="number"],
        input[type="text"],
        input[type="time"] {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d9e6;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 0.95em;
            font-family: 'Segoe UI', sans-serif;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        select:focus,
        input:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
            outline: none;
        }

        .btn-blue {
            background-color: #4a90e2;
            color: white;
            font-weight: 500;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Segoe UI', sans-serif;
            font-size: 0.95em;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-blue:hover {
            background-color: #357abd;
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            font-weight: 500;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Segoe UI', sans-serif;
            font-size: 0.9em;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-danger:hover {
            background-color: #b02a37;
            transform: translateY(-2px);
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-header h2 {
            margin: 0;
            font-size: 1.6em;
        }

        .form-section {
            border-radius: 10px;
            overflow: hidden;
            background-color: #f9fafb;
        }

        .form-section .form-title {
            background-color: #4a90e2;
            color: #fff;
            padding: 10px;
            font-weight: 600;
            font-size: 0.95em;
            font-family: 'Segoe UI', sans-serif;
        }

        .form-section .form-body {
            padding: 15px;
        }

        .back-arrow {
            display: inline-block;
            font-size: 24px;
            font-weight: bold;
            color: #4a90e2;
            text-decoration: none;
            margin-bottom: 15px;
            background: #fff;
            border-radius: 50%;
            padding: 6px 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .back-arrow:hover {
            background: #4a90e2;
            color: #fff;
            transform: scale(1.1);
        }

        .active-sessions-section {
            margin-top: 25px;
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

        .active-parking-widget h3 {
            margin: 0 0 10px 0;
            font-size: 1.1em;
            font-weight: 600;
            color: #1a3c6d;
        }

        .active-parking-widget p {
            margin: 6px 0;
            font-size: 0.9em;
        }

        .active-parking-widget button {
            width: 100%;
            margin-top: 8px;
            font-family: 'Segoe UI', sans-serif;
        }

        .emoji {
            margin-right: 6px;
            font-size: 1em;
        }

        .active-warning {
            color: #dc3545;
            font-weight: 500;
            margin-top: 10px;
            text-align: center;
            font-size: 0.9em;
        }

        @media (max-width: 768px) {
            .custom-card {
                margin: 15px;
                padding: 15px;
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

    <div class="custom-card">
        <div class="form-header">
            <h2>Registrar Estacionamiento (Pre-Pago)</h2>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
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

        <a href="{{ route('dashboard') }}" class="back-arrow" title="Volver al inicio" aria-label="Volver al inicio">
            &#8592;
        </a>

        <form id="parking-form" action="{{ route('parking.store') }}" method="POST">
            @csrf
            <div class="form-section">
                <div class="form-title">Datos del Estacionamiento</div>
                <div class="form-body">
                    <div class="mb-4">
                        <label for="car_id">Vehículo</label>
                        <select name="car_id" id="car_id" class="form-select" required>
                            <option value="">Seleccione un auto</option>
                            @foreach ($cars as $car)
                                <option value="{{ $car->id }}">{{ $car->license_plate ?? $car->car_plate }}</option>
                            @endforeach
                        </select>
                        @error('car_id')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="zone_id">Zona</label>
                        <select name="zone_id" id="zone_id" class="form-control" required>
                            <option value="">Selecciona una zona</option>
                            @foreach ($zones as $zone)
                                <option value="{{ $zone->id }}" data-rate="{{ $zone->rate ?? 5.0 }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                        @error('zone_id')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="street_id">Calle</label>
                        <select name="street_id" id="street_id" class="form-control" required>
                            <option value="">Seleccione una calle</option>
                            @foreach ($streets as $street)
                                <option value="{{ $street->id }}" data-zone-id="{{ $street->zone_id }}">{{ $street->name }}</option>
                            @endforeach
                        </select>
                        @error('street_id')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="start_time">Hora de inicio</label>
                        <input type="time" name="start_time" id="start_time" class="form-control" required>
                        @error('start_time')
                            <div class="text-red-600">{{ $message }}</div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="duration">Duración (minutos)</label>
                        <select name="duration" id="duration" class="form-control" required>
                            <option value="">Selecciona una duración</option>
                            <option value="60">1 hora</option>
                            <option value="120">2 horas</option>
                            <option value="180">3 horas</option>
                            <option value="240">4 horas</option>
                            <option value="360">6 horas</option>
                            <option value="480">8 horas</option>
                        </select>
                        @error('duration')
                            <div class="text-red-600">{{ $message }}</div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label>Monto Estimado</label>
                        <p id="amount-preview">$0.00</p>
                    </div>

                    <input type="hidden" name="timezone_offset" id="timezone_offset">
                    <button type="button" id="start-parking" class="btn-blue">Iniciar Estacionamiento</button>
                    <div id="active-warning" class="active-warning" style="display: none;">Tienes un estacionamiento activo para esta patente. Finalízalo antes de iniciar otro.</div>
                </div>
            </div>
        </form>

        <!-- Sesiones activas -->
        @if(isset($activeSessions) && $activeSessions->isNotEmpty())
            <div class="active-sessions-section">
                <h3>Sesiones de Estacionamiento Activas</h3>
                @foreach ($activeSessions as $session)
                    <div id="active-parking-widget-{{ $session->id }}" class="active-parking-widget">
                        <h3><span class="emoji">🚗</span> Estacionamiento Activo</h3>
                        <p><strong>Vehículo:</strong> {{ $session->car->license_plate ?? $session->car->car_plate }}</p>
                        <p><strong>Zona:</strong> {{ $session->zone->name }}</p>
                        <p><strong>Calle:</strong> {{ $session->street->name }}</p>
                        <p><strong>Hora de inicio:</strong> {{ $session->start_time->format('d/m/Y H:i') }}</p>
                        <p><strong>Duración:</strong> {{ $session->duration }} minutos</p>
                        <p><strong>Monto:</strong> ${{ number_format($session->amount, 2) }}</p>
                        <p id="timer-{{ $session->id }}">Cargando...</p>
                        <form id="end-parking-form-{{ $session->id }}" action="{{ route('parking.end', $session->id) }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn-danger">Finalizar</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        // Configuración inicial del formulario
        const now = new Date();
        const currentTime = now.toTimeString().slice(0, 5);
        document.getElementById('start_time').min = currentTime;
        document.getElementById('start_time').value = currentTime;
        document.getElementById('timezone_offset').value = now.getTimezoneOffset();

        // Manejar cambio de zona
        document.getElementById('zone_id').addEventListener('change', async function() {
            const zoneId = this.value;
            const streetSelect = document.getElementById('street_id');

            if (zoneId) {
                try {
                    const response = await fetch(`/api/zones/${zoneId}/streets`);
                    const streets = await response.json();
                    streetSelect.innerHTML = '<option value="">Seleccione una calle</option>';
                    streets.forEach(street => {
                        const option = document.createElement('option');
                        option.value = street.id;
                        option.textContent = street.name;
                        option.setAttribute('data-zone-id', street.zone_id);
                        streetSelect.appendChild(option);
                    });

                    const rateResponse = await fetch(`/api/zones/${zoneId}/rate`);
                    const rateData = await rateResponse.json();
                    updateAmount(rateData.rate || 5.0);
                } catch (error) {
                    console.error('Error al obtener datos:', error);
                    alert('Error al obtener datos de la zona. Intenta de nuevo.');
                }
            } else {
                streetSelect.innerHTML = '<option value="">Seleccione una calle</option>';
                @foreach ($streets as $street)
                    streetSelect.innerHTML += `<option value="{{ $street->id }}" data-zone-id="{{ $street->zone_id }}">{{ $street->name }}</option>`;
                @endforeach
                updateAmount(5.0);
            }
        });

        // Actualizar monto estimado
        document.getElementById('duration').addEventListener('change', function() {
            const zoneSelect = document.getElementById('zone_id');
            const selectedOption = zoneSelect.options[zoneSelect.selectedIndex];
            const rate = selectedOption ? parseFloat(selectedOption.getAttribute('data-rate')) || 5.0 : 5.0;
            updateAmount(rate);
        });

        function updateAmount(rate) {
            const duration = parseInt(document.getElementById('duration').value) || 0;
            const amount = duration ? (duration / 60) * rate : 0;
            document.getElementById('amount-preview').textContent = `$${amount.toFixed(2)}`;
        }

        // Verificar sesiones activas al cambiar el vehículo
        document.getElementById('car_id').addEventListener('change', async function() {
            const carId = this.value;
            const startButton = document.getElementById('start-parking');
            const warning = document.getElementById('active-warning');
            if (carId) {
                try {
                    const response = await fetch(`/api/parking/check-active/${carId}`);
                    if (!response.ok) {
                        const errorData = await response.json();
                        alert(errorData.message || `Error al verificar: ${response.status}`);
                        return;
                    }
                    const data = await response.json();
                    startButton.disabled = data.active;
                    warning.style.display = data.active ? 'block' : 'none';
                } catch (error) {
                    console.error('Error al verificar vehículo:', error);
                    alert('Error al verificar vehículo. Intenta de nuevo.');
                }
            } else {
                startButton.disabled = false;
                warning.style.display = 'none';
            }
        });

        // Iniciar estacionamiento con pago
        document.getElementById('start-parking').addEventListener('click', async function(e) {
            e.preventDefault();
            const carId = document.getElementById('car_id').value;
            if (!carId) {
                alert('Por favor, selecciona un vehículo.');
                return;
            }

            try {
                const response = await fetch(`/api/parking/check-active/${carId}`);
                if (!response.ok) {
                    const errorData = await response.json();
                    alert(errorData.message || `Error al verificar: ${response.status}`);
                    return;
                }
                const data = await response.json();
                if (data.active) {
                    alert('Ya tienes un estacionamiento activo para esta patente. Finalízalo antes de iniciar otro.');
                    return;
                }
                document.getElementById('parking-form').submit();
            } catch (error) {
                console.error('Error de red al verificar sesión activa:', error);
                alert('Error de conexión al verificar el estacionamiento activo. Intenta de nuevo.');
            }
        });

        // Temporizadores para sesiones activas
        const timers = {};
        @if(isset($activeSessions) && $activeSessions->isNotEmpty())
            @foreach ($activeSessions as $session)
                timers[{{ $session->id }}] = {
                    startTime: new Date('{{ $session->start_time->toIso8601String() }}').getTime(),
                    duration: {{ $session->duration }},
                    interval: null
                };
                (function(sessionId) {
                    const widget = document.getElementById('active-parking-widget-' + sessionId);
                    const timerElement = document.getElementById('timer-' + sessionId);
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
                      window.location.reload();
                  } else {
                      console.error('Error al expirar sesión:', data.message);
                  }
              }).catch(error => {
                  console.error('Error de red al expirar sesión:', error);
              });
        }

        // Manejar finalización de estacionamientos
        @if(isset($activeSessions) && $activeSessions->isNotEmpty())
            @foreach ($activeSessions as $session)
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
                            document.getElementById('active-warning').style.display = 'none';
                            document.getElementById('start-parking').disabled = false;
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
@endsection
