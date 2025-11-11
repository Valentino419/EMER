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
                    <input type="text" name="search" class="form-control" placeholder="Buscar patente (ej: AA123BB)"
                        value="{{ request('search') }}" maxlength="7" style="text-transform: uppercase;">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </form>
            @if (Auth::user()->role->name === 'admin' || Auth::user()->role->name === 'inspector')
                {{-- Botón que abre el modal --}}
                <button class="btn btn-primary btn-new" data-bs-toggle="modal" data-bs-target="#infraccionModal">
                    Nueva Infracción
                </button>
            @endif
        </div>
        @if (request('search'))
            <div class="row justify-content-center mb-4">
                <div class="col-md-8">
                    @if ($carStatus === 'activo')
                        <div class="alert alert-success d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill"></i>
                            <div>
                                <strong>Patente {{ $car->car_plate }}</strong> está <strong>ACTIVA</strong>.
                                @if ($hasTodayInfraction)
                                    <br><span class="text-danger">Ya tiene una multa PENDIENTE HOY.</span>
                                @else
                                    <br><span class="text-success">Listo para registrar infracción.</span>
                                @endif
                            </div>
                        </div>
                    @elseif ($carStatus === 'no_encontrado')
                        <div class="alert alert-warning d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>
                                <strong>Patente {{ request('search') }}</strong> no está registrada.
                                <br><small>Se creará automáticamente al registrar la infracción.</small>
                            </div>
                        </div>
                    @elseif ($carStatus === 'formato_invalido')
                        <div class="alert alert-danger d-flex align-items-center gap-2">
                            <i class="bi bi-x-circle-fill"></i>
                            <div>
                                Formato de patente inválido. Use: <strong>AA123BB</strong> o <strong>ABC123</strong>.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
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
        </div>
    </div>

    {{-- Modal para nueva infracción --}}
    <div class="modal fade" id="infraccionModal" tabindex="-1" aria-labelledby="infraccionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="infraccionModalLabel">Registrar Nueva Infracción</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Toast de éxito -->
                @if (session('success'))
                    <div class="toast-container position-fixed bottom-0 end-0 p-3">
                        <div id="successToast" class="toast align-items-center text-white bg-success border-0"
                            role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    {{ session('success') }}
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                    data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var toastEl = document.getElementById('successToast');
                            var toast = new bootstrap.Toast(toastEl, {
                                delay: 5000
                            });
                            toast.show();
                        });
                    </script>
                @endif

                <div class="modal-body">
                    <form action="{{ route('infractions.store') }}" method="POST" id="infraccionForm">
                        @csrf

                        <div class="mb-3">
                            <label for="car_plate" class="form-label">Patente del Auto</label>
                            <input type="text" class="form-control" id="car_plate" name="car_plate"
                                placeholder="Ej: ABC123" required>
                        </div>

                        <div class="mb-3">
                            <label for="fine" class="form-label">Monto de la Multa</label>
                            <input type="number" class="form-control" id="fine" name="fine" value="5000"
                                readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="infraccionForm" class="btn btn-primary">Registrar</button>
                </div>

                @if (session('success'))
   |            <!-- Toast de éxito -->
                @endif

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
