<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMER - Dashboard Inspector</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f5fa;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .dashboard-title {
            font-weight: bold;
        }

        .card-option {
            transition: transform 0.2s;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .card-option:hover {
            transform: scale(1.05);
        }

        .icon {
            font-size: 2.5rem;
        }

        .search-new-container {
            max-width: 600px;
            margin: auto;
        }

        .btn-new {
            width: 150px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-light justify-content-between px-4">
        <span class="navbar-text">
            Bienvenido, <strong>{{ Auth::user()->name ?? 'Inspector' }}</strong>
        </span>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-danger btn-sm">Cerrar sesión</button>
        </form>
    </nav>

    <div class="container py-5">
        <h1 class="dashboard-title text-center mb-4">Panel de Inspector</h1>

        {{-- Buscador y botón Nueva Infracción --}}
        <div class="d-flex justify-content-center align-items-center mb-4 search-new-container gap-2">
            <form method="GET" action="{{ route('infractions.index') }}" class="flex-grow-1">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por patente..."
                        value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </form>
            @if(Auth::user()->role->name === 'admin' || Auth::user()->role->name === 'inspector')
                <a href="{{ route('infractions.create') }}" class="btn btn-primary btn-new">Nueva Infracción</a>
            @endif
        </div>

        {{-- Botones grandes --}}
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <a href="{{ route('infractions.index') }}" class="text-decoration-none text-dark">
                    <div class="card card-option text-center p-4">
                        <div class="icon">⚠️</div>
                        <h5 class="mt-3">Gestionar Infracciones</h5>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('cars.index') }}" class="text-decoration-none text-dark">
                    <div class="card card-option text-center p-4">
                        <div class="icon">🚗</div>
                        <h5 class="mt-3">Ver Autos</h5>
                    </div>
                </a>
            </div>
        </div>
    </div>

</body>

</html>