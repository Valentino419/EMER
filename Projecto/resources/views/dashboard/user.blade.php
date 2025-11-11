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
                                @if ($widget['name'] == 'Mis Autos')
                                    🚗
                                @elseif ($widget['name'] == 'Iniciar Estacionamiento')
                                    🅿️
                                @elseif ($widget['name'] == 'Multas')
                                    ⚠️
                                @elseif ($widget['name'] == 'Zonas')
                                    🌍
                                @elseif ($widget['name'] == 'Historial de Estacionamientos')
                                    📋
                                @else
                                    🛠️
                                @endif
                            </span>
                            <h5 class="card-title">{{ $widget['name'] }}</h5>
                            <a href="{{ $widget['link'] }}"
                                class="btn {{ $widget['name'] == 'Iniciar Estacionamiento' ? 'btn-success' : 'btn-primary' }}">Ir</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sesiones activas -->
        @if (isset($data['activeSessions']) && $data['activeSessions']->isNotEmpty())
            <div class="active-sessions-section">
                <h3>Sesiones de Estacionamiento Activas</h3>
                @foreach ($data['activeSessions'] as $session)
                    <div id="active-parking-widget-{{ $session->id }}" class="active-parking-widget">
                        <h5><span class="emoji">🚗</span> Estacionamiento Activo</h5>
                        <p><strong>Vehículo:</strong> {{ $session->car->license_plate ?? $session->car->car_plate }}
                        </p>
                        <p><strong>Zona:</strong> {{ $session->street->zone->name }}</p>
                        <p><strong>Calle:</strong> {{ $session->street->name }}</p>
                        <p><strong>Hora de inicio:</strong> {{ $session->start_time->format('d/m/Y H:i') }}</p>
                        <p><strong>Duración:</strong> {{ $session->duration }} minutos</p>
                        <p><strong>Monto:</strong> ${{ number_format($session->amount, 2) }}</p>
                        <p id="dashboard-timer-{{ $session->id }}">Cargando...</p>
                        <button id="extend-btn-{{ $session->id }}" class="extend-btn hidden"
                            onclick="openModal({{ $session->id }})">
                            Agregar Tiempo
                        </button>
                        <form id="end-parking-form-{{ $session->id }}"
                            action="{{ route('parking.end', $session->id) }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-danger btn-sm mt-2">Finalizar</button>
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
    // === DATOS DE SESIONES (100% SEGURO) ===
    @php
        $sessionsData = $data['activeSessions']->map(function ($s) {
            return [
                'id' => $s->id,
                'start' => $s->start_time->timestamp * 1000,
                'duration' => $s->duration,
                'amount' => $s->amount,
                'car' => $s->car->license_plate ?? $s->car->car_plate,
                'rate' => $s->street->zone->rate ?? 100,
            ];
        })->toArray();
    @endphp

    const sessions = @json($sessionsData);

    let currentSessionId = null;

    document.addEventListener('DOMContentLoaded', () => {
        sessions.forEach(s => {
            let endTime = s.start + (s.duration * 60 * 1000);
            let warned = false;

            const timerEl = document.getElementById(`dashboard-timer-${s.id}`);
            const btn = document.getElementById(`extend-btn-${s.id}`);
            const widget = document.getElementById(`active-parking-widget-${s.id}`);

            const update = () => {
                const left = Math.max(0, Math.floor((endTime - Date.now()) / 1000));
                if (left === 0) {
                    timerEl.textContent = 'Finalizado';
                    timerEl.classList.add('text-red-600');
                    btn.classList.add('hidden');
                    widget.style.opacity = '0.6';
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
            setInterval(update, 1000);
        });
    });

    function number_format(n) {
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
            [60, rate, `1 hora - $${number_format(rate)}`],
            [120, rate * 2, `2 horas - $${number_format(rate * 2)}`],
            [180, rate * 3, `3 horas - $${number_format(rate * 3)}`]
        ];
        opts.forEach(o => {
            const opt = new Option(o[2], o[0]);
            opt.dataset.cost = o[1];
            select.add(opt);
        });

        document.getElementById('extend-cost').textContent = `$${number_format(opts[0][1])}`;
        document.getElementById('extend-modal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('extend-modal').classList.remove('show');
    }

    document.getElementById('extra-duration').addEventListener('change', () => {
        const cost = document.getElementById('extra-duration').selectedOptions[0].dataset.cost;
        document.getElementById('extend-cost').textContent = `$${number_format(cost)}`;
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
                body: JSON.stringify({ extra_minutes: extra })
            });
            const data = await res.json();

            if (data.success) {
                alert('¡Tiempo extendido con éxito!');
                closeModal();
                location.reload();
            } else {
                alert(data.message || 'Error al extender');
            }
        } catch (e) {
            alert('Error de conexión');
        } finally {
            btn.disabled = false;
            btn.textContent = txt;
        }
    };

    // Finalizar estacionamiento
    document.querySelectorAll('[id^="end-parking-form-"]').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (!confirm('¿Finalizar este estacionamiento?')) return;

            const res = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: new FormData(this)
            });
            const data = await res.json();
            if (res.ok && data.success) {
                alert(data.message);
                document.getElementById('active-parking-widget-' + this.id.split('-')[3]).remove();
            } else {
                alert(data.message || 'Error');
            }
        });
    });
    
</script>
</body>

</html>
