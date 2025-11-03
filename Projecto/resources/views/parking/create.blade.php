@extends('layouts.app')

@section('content')
    

    <style>
        /* [Mantené los estilos existentes sin cambios] */
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
            background: linear-gradient(135deg, #00b4db, #0083b0) !important;
            color: white;
            font-weight: 600;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Segoe UI', sans-serif;
            font-size: 1em;
            transition: all 0.3s;
            width: 100%;
            box-shadow: 0 4px 12px rgba(0, 180, 219, 0.3);
        }

        .btn-blue:hover {
            background: linear-gradient(135deg, #0083b0, #00607d) !important;
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
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <a href="{{ route('dashboard') }}" class="back-arrow" title="Volver al inicio" aria-label="Volver al inicio">
            ←
        </a>

        <form action="{{ route('parking.store') }}" method="POST" id="parking-form">
            @csrf
            <div class="form-section">
                <div class="form-title">Datos del Estacionamiento</div>
                <div class="form-body">
                    <!-- Vehículo -->
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

                    <!-- Zona -->
                    <div class="mb-4">
                        <label for="zone_id">Zona</label>
                        <select name="zone_id" id="zone_id" class="form-control" required>
                            <option value="">Selecciona una zona</option>
                            @foreach ($zones as $zone)
                                <option value="{{ $zone->id }}" data-rate="{{ $zone->rate ?? 5.0 }}">
                                    {{ $zone->name }}</option>
                            @endforeach
                        </select>
                        @error('zone_id')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Calle -->
                    <div class="mb-4">
                        <label for="street_id">Calle</label>
                        <select name="street_id" id="street_id" class="form-control" required>
                            <option value="">Seleccione una calle</option>
                            @foreach ($streets as $street)
                                <option value="{{ $street->id }}" data-zone-id="{{ $street->zone_id }}">
                                    {{ $street->name }}</option>
                            @endforeach
                        </select>
                        @error('street_id')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hora de inicio -->
                    <div class="mb-4">
                        <label for="start_time">Hora de inicio</label>
                        <input type="time" name="start_time" id="start_time" class="form-control" required>
                        @error('start_time')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Duración -->
                    <div class="mb-4">
                        <label for="duration">Duración</label>
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
                        @enderror
                    </div>

                    <!-- Monto estimado -->
                    <div class="mb-4">
                        <label>Monto Estimado</label>
                        <p id="amount-preview" class="font-bold text-lg">$0.00</p>
                    </div>

                    <input type="hidden" name="timezone_offset" id="timezone_offset">
                    <button type="submit" class="btn btn-success">
                        Iniciar Estacionamiento
                    </button>
                    <!--<button type="submit" class="btn-blue">Pagar y Iniciar <span id="amount-button">$0.00</span></button>-->
                </div>
            </div>
        </form>

        <!-- SESIONES ACTIVAS -->
        @if (isset($activeSessions) && $activeSessions->isNotEmpty())
            <div class="active-sessions-section mt-6">
                <h3>Sesiones de Estacionamiento Activas</h3>
                @foreach ($activeSessions as $session)
                    <div id="active-parking-widget-{{ $session->id }}" class="active-parking-widget">
                        <h3>Estacionamiento Activo</h3>
                        <p><strong>Vehículo:</strong> {{ $session->car->license_plate ?? $session->car->car_plate }}</p>
                        <p><strong>Zona:</strong> {{ $session->zone->name }}</p>
                        <p><strong>Calle:</strong> {{ $session->street->name }}</p>
                        <p><strong>Inicio:</strong> {{ $session->start_time->format('d/m/Y H:i') }}</p>
                        <p><strong>Duración:</strong> {{ $session->duration }} min</p>
                        <p><strong>Monto:</strong> ${{ number_format($session->amount, 2) }}</p>
                        <p id="timer-{{ $session->id }}" class="font-mono font-bold">Cargando...</p>

                        <form id="end-parking-form-{{ $session->id }}" action="{{ route('parking.end', $session->id) }}"
                            method="POST" onsubmit="event.preventDefault(); endParking({{ $session->id }})">
                            @csrf
                            <button type="submit" class="btn-danger">Finalizar</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
        const now = new Date();
        const startTimeInput = document.getElementById('start_time');
        const timezoneOffset = document.getElementById('timezone_offset');
        const zoneSelect = document.getElementById('zone_id');
        const streetSelect = document.getElementById('street_id');
        const durationSelect = document.getElementById('duration');
        const amountPreview = document.getElementById('amount-preview');

        // Inicialización
        startTimeInput.value = now.toTimeString().slice(0, 5);
        startTimeInput.min = now.toTimeString().slice(0, 5);
        timezoneOffset.value = now.getTimezoneOffset();

        // Datos pre-cargados
        const streetsData = @json($streets->map(fn($s) => [
            'id' => $s->id, 'name' => $s->name, 'zone_id' => $s->zone_id
        ]));

        const updateStreets = (zoneId) => {
            streetSelect.innerHTML = '<option value="">Seleccione una calle</option>';
            streetsData
                .filter(s => !zoneId || s.zone_id == zoneId)
                .forEach(s => {
                    const opt = new Option(s.name, s.id);
                    streetSelect.add(opt);
                });
        };

        const updateAmount = () => {
            const rate = parseFloat(zoneSelect.selectedOptions[0]?.dataset.rate) || 5.0;
            const duration = parseInt(durationSelect.value) || 0;
            const amount = (duration / 60) * rate;
            amountPreview.textContent = `$${amount.toFixed(2)}`;
        };

        zoneSelect.addEventListener('change', () => {
            updateStreets(zoneSelect.value);
            updateAmount();
        });

        durationSelect.addEventListener('change', updateAmount);
        updateStreets(); // inicial
        updateAmount();

        // Temporizadores
        @foreach ($activeSessions as $session)
            (function() {
                const end = {{ $session->start_time->addMinutes($session->duration)->timestamp * 1000 }};
                const timerEl = document.getElementById('timer-{{ $session->id }}');
                const widget = document.getElementById('active-parking-widget-{{ $session->id }}');

                const update = () => {
                    const left = Math.max(0, Math.floor((end - Date.now()) / 1000));
                    if (left === 0) {
                        timerEl.textContent = 'Finalizado';
                        widget.style.opacity = '0.6';
                        clearInterval(interval);
                        setTimeout(() => location.reload(), 3000);
                        return;
                    }
                    const h = String(Math.floor(left / 3600)).padStart(2, '0');
                    const m = String(Math.floor((left % 3600) / 60)).padStart(2, '0');
                    const s = String(left % 60).padStart(2, '0');
                    timerEl.textContent = `${h}:${m}:${s} restantes`;
                };
                update();
                const interval = setInterval(update, 1000);
            })();
        @endforeach
    });

    async function endParking(sessionId) {
        const form = document.getElementById(`end-parking-form-${sessionId}`);
        const widget = document.getElementById(`active-parking-widget-${sessionId}`);
        const button = widget.querySelector('button');

        try {
            button.disabled = true;
            button.textContent = 'Finalizando...';

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                widget.style.transition = 'opacity 0.5s';
                widget.style.opacity = '0.5';
                setTimeout(() => widget.remove(), 800);
            } else {
                alert(data.message || 'Error');
                button.disabled = false;
                button.textContent = 'Finalizar';
            }
        } catch (e) {
            alert('Error de conexión');
            button.disabled = false;
            button.textContent = 'Finalizar';
        }
    }
</script>
@endsection
