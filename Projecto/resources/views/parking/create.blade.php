@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Tus estilos originales (sin cambios) */
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
            transition: box-shadow .2s, transform .2s;
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
            font-size: .95em;
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
            font-size: .95em;
            transition: border-color .3s, box-shadow .3s;
        }

        select:focus,
        input:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, .1);
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
            font-size: 1em;
            transition: all .3s;
            width: 100%;
            box-shadow: 0 4px 12px rgba(0, 180, 219, .3);
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
            font-size: .9em;
            transition: background-color .3s, transform .2s;
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
            font-size: .95em;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, .05);
            transition: all .3s ease;
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
            border: 1px solid rgba(0, 0, 0, .1);
            border-radius: 8px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, .3);
            transition: transform .2s, box-shadow .2s;
        }

        .active-parking-widget:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .35);
        }

        .active-parking-widget h3 {
            margin: 0 0 10px 0;
            font-size: 1.1em;
            font-weight: 600;
            color: #1a3c6d;
        }

        .active-parking-widget p {
            margin: 6px 0;
            font-size: .9em;
        }

        .active-parking-widget button {
            width: 100%;
            margin-top: 8px;
            font-family: 'Segoe UI', sans-serif;
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
                margin: auto;
            }
        }

        /* MODAL FIJO Y OCULTO */
        .extend-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 1rem;
            display: none;
            /* Oculto por defecto */
        }

        .extend-modal.show {
            display: flex;
        }

        .extend-modal-content {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            max-width: 320px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .3);
        }

        .extend-btn {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
            font-weight: 600;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: .9em;
            margin-top: 8px;
            transition: all .3s;
            width: 100%;
        }

        .extend-btn:hover {
            background: linear-gradient(135deg, #218838, #1c6c2e);
            transform: translateY(-1px);
        }

        #start_time {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .alert-error {
            background-color: #dc3545;
            color: white;
            padding: 1.25rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-weight: bold;
            font-size: 1.1rem;
            border: 2px solid #c82333;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .alert-error ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin-bottom: 0;
        }

        .alert-error li {
            margin-bottom: 0.5rem;
        }

        .alert-success {
            background-color: #28a745;
            color: white;
            padding: 1.25rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-weight: bold;
            font-size: 1.1rem;
            border: 2px solid #218838;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: pulse 1s infinite;
        }
    </style>

    <div class="custom-card">
        <div class="form-header">
            <h2>Registrar Estacionamiento (Pre-Pago)</h2>
        </div>

        @if (session('success'))
            <div class="alert-success">
                <h4 class="text-lg mb-2">¡Éxito!</h4>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <h4 class="text-lg mb-2">¡Atención! Error al registrar:</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <a href="{{ route('dashboard') }}" class="back-arrow">←</a>

        <form action="{{ route('parking.store') }}" method="POST" id="parking-form">
            @csrf
            <div class="form-section">
                <div class="form-title">Datos del Estacionamiento</div>
                <div class="form-body">
                    <div class="mb-4">
                        <label for="car_id">Vehículo</label>
                        <select name="car_id" id="car_id" required>
                            <option value="">Seleccione un auto</option>
                            @foreach ($cars as $car)
                                <option value="{{ $car->id }}">{{ $car->license_plate ?? $car->car_plate }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="zone_id">Zona</label>
                        <select name="zone_id" id="zone_id" required>
                            <option value="">Selecciona una zona</option>
                            @foreach ($zones as $zone)
                                <option value="{{ $zone->id }}" data-rate="{{ $zone->rate ?? 100 }}">{{ $zone->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="street_id">Calle</label>
                        <select name="street_id" id="street_id" required>
                            <option value="">Seleccione una calle</option>
                            @foreach ($streets as $street)
                                <option value="{{ $street->id }}" data-zone-id="{{ $street->zone_id }}">
                                    {{ $street->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="start_time">Hora de inicio</label>
                        <input type="time" name="start_time" id="start_time" required readonly>
                    </div>

                    <div class="mb-4">
                        <label for="duration">Duración</label>
                        <select name="duration" id="duration" required>
                            <option value="">Selecciona una duración</option>
                            <option value="60">1 hora</option>
                            <option value="120">2 horas</option>
                            <option value="180">3 horas</option>
                            <option value="240">4 horas</option>
                            <option value="360">6 horas</option>
                            <option value="480">8 horas</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label>Monto Estimado</label>
                        <p id="amount-preview" class="font-bold text-lg">$0</p>
                    </div>
                    <div class="mb-4">
                        <label>Habilitar Mercado Pago</label>
                        <label class="switch">
                            <input type="checkbox" name="mercadopago_enabled" id="mercadopago_enabled"   value="1">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <input type="hidden" name="timezone_offset" id="timezone_offset">
                    <button type="submit" class="btn-blue">Iniciar Estacionamiento</button>
                </div>
            </div>
        </form>

        <!-- SESIONES ACTIVAS -->
        @if (isset($activeSessions) && $activeSessions->isNotEmpty())
            <div class="active-sessions-section mt-6">
                <h3>Sesiones de Estacionamiento Activas</h3>
                @foreach ($activeSessions as $session)
                    <div id="widget-{{ $session->id }}" class="active-parking-widget">
                        <h3>Estacionamiento Activo</h3>
                        <p><strong>Vehículo:</strong> {{ $session->car->license_plate ?? $session->car->car_plate }}</p>
                        <p><strong>Zona:</strong> {{ $session->zone->name }}</p>
                        <p><strong>Calle:</strong> {{ $session->street->name }}</p>
                        <p><strong>Inicio:</strong> {{ $session->start_time->format('d/m/Y H:i') }}</p>
                        <p><strong>Duración:</strong> <span id="dur-{{ $session->id }}">{{ $session->duration }}</span>
                            min</p>
                        <p><strong>Monto:</strong> $<span
                                id="amt-{{ $session->id }}">{{ number_format($session->amount, 0) }}</span></p>
                        <p id="timer-{{ $session->id }}" class="font-mono font-bold">Cargando...</p>

                        <button id="extend-btn-{{ $session->id }}" class="extend-btn hidden"
                            onclick="openModal({{ $session->id }})">
                            Agregar Tiempo
                        </button>

                        <form id="end-form-{{ $session->id }}" action="{{ route('parking.end', $session->id) }}"
                            method="POST" onsubmit="event.preventDefault(); endParking({{ $session->id }})">
                            @csrf
                            <button type="submit" class="btn-danger mt-2">Finalizar</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- MODAL ÚNICO (FUERA DEL FOREACH) -->
    <div id="extend-modal" class="extend-modal">
        <div class="extend-modal-content">
            <h3 class="text-lg font-bold text-[#1a3c6d] mb-2">Extender Tiempo</h3>
            <p class="text-sm text-gray-600 mb-3">Vehículo: <strong id="modal-car"></strong></p>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tiempo adicional</label>
                <select id="extra-duration" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                    <!-- Opciones se llenan con JS -->
                </select>
            </div>

            <p class="text-right text-sm font-bold mb-3">
                Costo: <span id="extend-cost" class="text-green-600">$0</span>
            </p>

            <div class="flex gap-2">
                <button onclick="closeModal()"
                    class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-lg text-sm font-medium">Cancelar</button>
                <button id="confirm-extend"
                    class="flex-1 bg-gradient-to-r from-[#28a745] to-[#1e7e34] text-black py-2 rounded-lg text-sm font-medium">
                    Pagar y Extender
                </button>
            </div>
        </div>
    </div>

    <script>
        // === DATOS DE SESIONES ===
        const sessions = <?php echo json_encode(
            $activeSessions
                ->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'start' => $s->start_time->timestamp * 1000,
                        'duration' => $s->duration,
                        'amount' => $s->amount,
                        'car' => $s->car->license_plate ?? $s->car->car_plate,
                        'rate' => $s->zone->rate ?? 100,
                    ];
                })
                ->toArray(),
        ); ?>;

        // === DATOS DE CALLES ===
        const streetsData = <?php echo json_encode(
            $streets
                ->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'name' => $s->name,
                        'zone_id' => $s->zone_id,
                    ];
                })
                ->toArray(),
        ); ?>;

        let currentSessionId = null;

        document.addEventListener('DOMContentLoaded', () => {
            const now = new Date();
            document.getElementById('start_time').value = now.toTimeString().slice(0, 5);
            document.getElementById('start_time').min = now.toTimeString().slice(0, 5);
            document.getElementById('timezone_offset').value = now.getTimezoneOffset();

            const zoneSelect = document.getElementById('zone_id');
            const streetSelect = document.getElementById('street_id');
            const durationSelect = document.getElementById('duration');
            const amountPreview = document.getElementById('amount-preview');

            const updateStreets = (zoneId) => {
                streetSelect.innerHTML = '<option value="">Seleccione una calle</option>';
                streetsData
                    .filter(s => !zoneId || s.zone_id == zoneId)
                    .forEach(s => streetSelect.add(new Option(s.name, s.id)));
            };

            const updateAmount = () => {
                const rate = parseFloat(zoneSelect.selectedOptions[0]?.dataset.rate) || 100;
                const duration = parseInt(durationSelect.value) || 0;
                amountPreview.textContent = `$${number_format((duration/60)*rate, 0)}`;
            };

            zoneSelect.addEventListener('change', () => {
                updateStreets(zoneSelect.value);
                updateAmount();
            });
            durationSelect.addEventListener('change', updateAmount);
            updateStreets();
            updateAmount();

            sessions.forEach(s => {
                let endTime = s.start + (s.duration * 60 * 1000);
                let warned = false;
                let interval = null;

                const timerEl = document.getElementById(`timer-${s.id}`);
                const btn = document.getElementById(`extend-btn-${s.id}`);
                const widget = document.getElementById(`widget-${s.id}`);

                const update = () => {
                    const left = Math.max(0, Math.floor((endTime - Date.now()) / 1000));
                    if (left === 0) {
                        timerEl.textContent = 'Finalizado';
                        timerEl.classList.add('text-red-600');
                        btn.classList.add('hidden');
                        widget.style.opacity = '0.6';
                        clearInterval(interval);
                        setTimeout(() => location.reload(), 3000);
                        return;
                    }
                    const h = String(Math.floor(left / 3600)).padStart(2, '0');
                    const m = String(Math.floor((left % 3600) / 60)).padStart(2, '0');
                    const s = String(left % 60).padStart(2, '0');
                    timerEl.textContent = `${h}:${m}:${s} restantes`;

                    if (left <= 300 && !warned) {
                        btn.classList.remove('hidden');
                        warned = true;
                    }
                };

                update();
                interval = setInterval(update, 1000);
            });
        });

        function number_format(n, d = 0) {
            return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '');
        }

        function openModal(id) {
            const s = sessions.find(x => x.id == id);
            currentSessionId = id;
            document.getElementById('modal-car').textContent = s.car;

            const select = document.getElementById('extra-duration');
            select.innerHTML = '';
            const rate = s.rate;
            const opts = [
                [60, rate, `1 hora - $${number_format(rate,0)}`],
                [120, rate * 2, `2 horas - $${number_format(rate*2,0)}`],
                [180, rate * 3, `3 horas - $${number_format(rate*3,0)}`]
            ];
            opts.forEach(o => {
                const opt = new Option(o[2], o[0]);
                opt.dataset.cost = o[1];
                select.add(opt);
            });

            document.getElementById('extend-cost').textContent = `$${number_format(opts[0][1],0)}`;
            document.getElementById('extend-modal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('extend-modal').classList.remove('show');
        }

        document.getElementById('extra-duration').addEventListener('change', () => {
            const cost = document.getElementById('extra-duration').selectedOptions[0].dataset.cost;
            document.getElementById('extend-cost').textContent = `$${number_format(cost,0)}`;
        });

        document.getElementById('confirm-extend').onclick = async () => {
            const extra = parseInt(document.getElementById('extra-duration').value);
            const btn = document.getElementById('confirm-extend');
            const txt = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Procesando...';

            try {
                const res = await fetch(`/parking/${currentSessionId}/extend`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        extra_minutes: extra
                    })
                });
                const data = await res.json();

                if (data.success) {
                    if (data.redirect) {
                        location.href = data.redirect;
                    } else {
                        closeModal();
                    }
                } else {
                    alert(data.message || 'Error');
                }
            } catch (e) {
                alert('Error de conexión');
            } finally {
                btn.disabled = false;
                btn.textContent = txt;
            }
        };

        async function endParking(id) {
            const form = document.getElementById(`end-form-${id}`);
            const widget = document.getElementById(`widget-${id}`);
            const btn = widget.querySelector('button');
            btn.disabled = true;
            btn.textContent = 'Finalizando...';

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: new FormData(form)
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    widget.style.opacity = '0.5';
                    setTimeout(() => widget.remove(), 800);
                } else {
                    alert(data.message || 'Error');
                    btn.disabled = false;
                    btn.textContent = 'Finalizar';
                }
            } catch (e) {
                alert('Error');
                btn.disabled = false;
                btn.textContent = 'Finalizar';
            }
        }
    </script>
@endsection