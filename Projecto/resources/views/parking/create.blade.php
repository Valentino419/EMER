@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .custom-card {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #1a3c6d;
            font-weight: 700;
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        select,
        input[type="number"],
        input[type="text"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        select:focus,
        input:focus {
            border-color: #007bff;
            outline: none;
        }

        .btn-blue {
            background-color: #007bff;
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-blue:hover {
            background-color: #0056b3;
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
        }

        .form-section {
            border-radius: 8px;
            overflow: hidden;
        }

        .form-section .form-title {
            background-color: #007bff;
            color: #fff;
            padding: 12px;
            font-weight: 600;
            font-size: 16px;
        }

        .form-section .form-body {
            padding: 20px;
        }

        .back-arrow {
            display: inline-block;
            font-size: 32px;
            font-weight: bold;
            color: #1a3c6d;
            text-decoration: none;
            margin-bottom: 15px;
            background: #fff;
            border-radius: 50%;
            padding: 8px 14px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .back-arrow:hover {
            background: #007bff;
            color: #fff;
            transform: scale(1.1);
        }

        #timer {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 20px;
            text-align: center;
        }

        .active-warning {
            color: #dc3545;
            font-weight: 600;
            margin-top: 10px;
            text-align: center;
        }

        .details-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f1f1f1;
            border-radius: 8px;
        }

        .details-section h3 {
            color: #1a3c6d;
            margin-bottom: 10px;
        }

        .details-section p {
            margin: 5px 0;
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

        <form id="parking-form" action="{{ route('parking.store') }}" method="POST" @if($activeSession) style="display: none;" @endif>
            @csrf

            <div class="form-section">
                <div class="form-title">Datos del Estacionamiento</div>
                <div class="form-body">
                    <!-- Car -->
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

                    <!-- Zone -->
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

                    <!-- Street -->
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

                    <!-- Start Time -->
                    <div class="mb-4">
                        <label for="start_time">Hora de inicio</label>
                        <input type="time" name="start_time" id="start_time" class="form-control" required>
                        @error('start_time')
                            <div class="text-red-600">{{ $message }}</div>
                        @endif
                    </div>

                    <!-- Duration -->
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

                    <!-- Amount Preview -->
                    <div class="mb-4">
                        <label>Monto Estimado</label>
                        <p id="amount-preview">$0.00</p>
                    </div>

                    <!-- Hidden -->
                    <input type="hidden" name="timezone_offset" id="timezone_offset">

                    <button type="button" id="start-parking" class="btn-blue">Iniciar Estacionamiento</button>
                    <div id="active-warning" class="active-warning" style="display: none;">Tienes un estacionamiento activo. Finalízalo antes de iniciar otro.</div>
                </div>
            </div>
        </form>

        <div id="timer" style="display: none;">Tiempo restante: --:--</div>

        <!-- Sección de detalles del estacionamiento activo -->
        @if($activeSession)
            <div class="details-section">
                <h3>Detalles del Estacionamiento Activo</h3>
                <p><strong>Vehículo:</strong> {{ $activeSession->car->license_plate ?? $activeSession->car->car_plate }}</p>
                <p><strong>Zona:</strong> {{ $activeSession->street->zone->name }}</p>
                <p><strong>Calle:</strong> {{ $activeSession->street->name }}</p>
                <p><strong>Hora de inicio:</strong> {{ $activeSession->start_time }}</p>
                <p><strong>Duración:</strong> {{ $activeSession->duration }} minutos</p>
                <p><strong>Monto estimado:</strong> ${{ number_format($activeSession->amount, 2) }}</p>
                <p><strong>Estado:</strong> {{ $activeSession->status }}</p>
            </div>
        @endif
    </div>

    <script>
        // Set start_time min and value to client's local current time
        const now = new Date();
        const currentTime = now.toTimeString().slice(0, 5);
        document.getElementById('start_time').min = currentTime;
        document.getElementById('start_time').value = currentTime;
        document.getElementById('timezone_offset').value = now.getTimezoneOffset();

        // Zone change: Filter streets, get rate
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
                    console.error('Error fetching data:', error);
                }
            } else {
                streetSelect.innerHTML = '<option value="">Seleccione una calle</option>';
                @foreach ($streets as $street)
                    streetSelect.innerHTML += `<option value="{{ $street->id }}" data-zone-id="{{ $street->zone_id }}">{{ $street->name }}</option>`;
                @endforeach
                updateAmount(5.0); // Default rate
            }
        });

        // Duration change: Update amount
        document.getElementById('duration').addEventListener('change', function() {
            const zoneSelect = document.getElementById('zone_id');
            const selectedOption = zoneSelect.options[zoneSelect.selectedIndex];
            const rate = selectedOption ? selectedOption.getAttribute('data-rate') || 5.0 : 5.0;
            updateAmount(rate);
        });

        function updateAmount(rate) {
            const duration = document.getElementById('duration').value;
            const amount = duration ? (duration / 60) * rate : 0;
            document.getElementById('amount-preview').textContent = `$${amount.toFixed(2)}`;
        }

        // Temporizador y persistencia
        let timerInterval;
        let timeLeft = localStorage.getItem('parkingTimeLeft') ? parseInt(localStorage.getItem('parkingTimeLeft')) : 0;
        let sessionId = localStorage.getItem('parkingSessionId') || null;

        // Restaurar temporizador al cargar la página
        if (timeLeft > 0 && sessionId) {
            document.getElementById('timer').style.display = 'block';
            document.getElementById('parking-form').style.display = 'none';
            document.getElementById('active-warning').style.display = 'block';
            timerInterval = setInterval(updateTimer, 1000);
        }

        document.getElementById('start-parking').addEventListener('click', function(e) {
            e.preventDefault();
            if (timeLeft > 0) {
                alert('Ya tienes un estacionamiento activo. Finalízalo antes de iniciar otro.');
                return;
            }
            document.getElementById('parking-form').submit();
        });

        // Iniciar temporizador después de guardar en el controlador
        @if(session('sessionData'))
            const sessionData = @json(session('sessionData'));
            timeLeft = sessionData.duration * 60;
            sessionId = @json(session('parkingSessionId'));
            localStorage.setItem('parkingTimeLeft', timeLeft);
            localStorage.setItem('parkingSessionId', sessionId);
            document.getElementById('timer').style.display = 'block';
            document.getElementById('parking-form').style.display = 'none';
            document.getElementById('active-warning').style.display = 'block';
            timerInterval = setInterval(updateTimer, 1000);
        @endif

        function updateTimer() {
            if (timeLeft > 0) {
                timeLeft--;
                localStorage.setItem('parkingTimeLeft', timeLeft);
                const hours = Math.floor(timeLeft / 3600);
                const minutes = Math.floor((timeLeft % 3600) / 60);
                const seconds = timeLeft % 60;
                document.getElementById('timer').textContent = `Tiempo restante: ${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            } else {
                clearInterval(timerInterval);
                document.getElementById('timer').textContent = 'Tiempo terminado!';
                alert('El tiempo de estacionamiento ha terminado.');
                localStorage.removeItem('parkingTimeLeft');
                localStorage.removeItem('parkingSessionId');
                document.getElementById('parking-form').style.display = 'block';
                document.getElementById('active-warning').style.display = 'none';
            }
        }
    </script>
@endsection