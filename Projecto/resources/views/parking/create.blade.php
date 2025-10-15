<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Estacionamiento</title>
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

        .active-sessions-list {
            margin-top: 20px;
            list-style: none;
            padding: 0;
        }

        .active-sessions-list li {
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .active-sessions-list a {
            color: #007bff;
            text-decoration: none;
        }

        .active-sessions-list a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="custom-card">
        <a href="index.html" class="back-arrow" title="Volver al inicio" aria-label="Volver al inicio">&#8592;</a>
        <div class="form-header">
            <h2>Registrar Estacionamiento (Pre-Pago)</h2>
        </div>

        <form id="parking-form">
            <div class="form-section">
                <div class="form-title">Datos del Estacionamiento</div>
                <div class="form-body">
                    <div class="mb-4">
                        <label for="licensePlate">Patente</label>
                        <input type="text" id="licensePlate" required>
                    </div>
                    <div class="mb-4">
                        <label for="duration">Duración (minutos)</label>
                        <select id="duration" required>
                            <option value="">Selecciona una duración</option>
                            <option value="60">1 hora</option>
                            <option value="120">2 horas</option>
                            <option value="180">3 horas</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-blue">Iniciar Estacionamiento</button>
                </div>
            </div>
        </form>

        <div id="active-sessions" class="details-section">
            <h3>Estacionamientos Activos</h3>
            <ul class="active-sessions-list" id="active-sessions-list"></ul>
        </div>
    </div>

    <script>
        // Cargar y guardar estacionamientos en localStorage
        function loadParkings() {
            return JSON.parse(localStorage.getItem('parkings') || '[]');
        }

        function saveParkings(parkings) {
            localStorage.setItem('parkings', JSON.stringify(parkings));
        }

        // Iniciar un nuevo estacionamiento
        document.getElementById('parking-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const licensePlate = document.getElementById('licensePlate').value;
            const duration = parseInt(document.getElementById('duration').value);
            if (licensePlate && duration) {
                const parkings = loadParkings();
                const newId = parkings.length > 0 ? Math.max(...parkings.map(p => p.id)) + 1 : 1;
                parkings.push({
                    id: newId,
                    licensePlate: licensePlate,
                    startTime: new Date().toISOString(),
                    duration: duration,
                    status: 'active'
                });
                saveParkings(parkings);
                alert('Estacionamiento iniciado correctamente.');
                document.getElementById('licensePlate').value = '';
                document.getElementById('duration').value = '';
                updateActiveSessions();
            }
        });

        // Actualizar lista de estacionamientos activos
        function updateActiveSessions() {
            const parkings = loadParkings();
            const activeParkings = parkings.filter(p => p.status === 'active');
            const list = document.getElementById('active-sessions-list');

            if (activeParkings.length === 0) {
                list.innerHTML = '<li>No hay estacionamientos activos.</li>';
                return;
            }

            list.innerHTML = '';
            activeParkings.forEach(parking => {
                const now = new Date();
                const start = new Date(parking.startTime);
                const end = new Date(start.getTime() + parking.duration * 60000);
                let timeLeft = Math.max(0, Math.floor((end - now) / 1000));
                const hours = Math.floor(timeLeft / 3600);
                const minutes = Math.floor((timeLeft % 3600) / 60);
                const seconds = timeLeft % 60;

                const li = document.createElement('li');
                li.innerHTML = `<a href="details.html?id=${parking.id}">Patente: ${parking.licensePlate}</a> - Tiempo: ${hours}h ${minutes}m ${seconds}s`;
                list.appendChild(li);

                // Actualizar tiempo en tiempo real
                setInterval(() => {
                    const updatedNow = new Date();
                    timeLeft = Math.max(0, Math.floor((end - updatedNow) / 1000));
                    const updatedHours = Math.floor(timeLeft / 3600);
                    const updatedMinutes = Math.floor((timeLeft % 3600) / 60);
                    const updatedSeconds = timeLeft % 60;
                    li.innerHTML = `<a href="details.html?id=${parking.id}">Patente: ${parking.licensePlate}</a> - Tiempo: ${updatedHours}h ${updatedMinutes}m ${updatedSeconds}s`;

                    if (timeLeft <= 0) {
                        parking.status = 'expired';
                        saveParkings(parkings);
                        updateActiveSessions();
                    }
                }, 1000);
            });
        }

        // Cargar estacionamientos al iniciar
        window.onload = updateActiveSessions;
    </script>
</head>
</html>