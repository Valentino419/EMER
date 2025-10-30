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

        .active-sessions-list {
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

        .btn-red {
            background-color: #dc3545;
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-red:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }
    </style>

    <div class="custom-card">
        <h2>Mis Estacionamientos</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($activeSessions->isEmpty())
            <p>No hay estacionamientos activos.</p>
        @else
            <ul class="active-sessions-list">
                @foreach ($activeSessions as $session)
                    <li>
                        <a href="{{ route('parking.show', $session->id) }}">Patente: {{ $session->license_plate }}</a>
                        - Zona: {{ $session->zone->name ?? 'N/A' }}
                        - Calle: {{ $session->street->name ?? 'N/A' }}
                        - Tiempo restante: <span id="time-{{ $session->id }}" data-end="{{ $session->end_time }}"></span>
                        <form action="{{ route('parking.end', $session->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn-red">Finalizar</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-4">
            <a href="{{ route('parking.create') }}" class="btn-blue">Iniciar Nuevo Estacionamiento</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach ($activeSessions as $session)
                updateTime('{{ $session->id }}', '{{ $session->end_time }}');
            @endforeach

            function updateTime(sessionId, endTime) {
                const end = new Date(endTime);
                const timer = setInterval(() => {
                    const now = new Date();
                    let timeLeft = Math.max(0, Math.floor((end - now) / 1000));
                    const hours = Math.floor(timeLeft / 3600);
                    const minutes = Math.floor((timeLeft % 3600) / 60);
                    const seconds = timeLeft % 60;
                    document.getElementById(`time-${sessionId}`).textContent = `${hours}h ${minutes}m ${seconds}s`;

                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        window.location.reload();
                    }
                }, 1000);
            }
        });
    </script>
@endsection