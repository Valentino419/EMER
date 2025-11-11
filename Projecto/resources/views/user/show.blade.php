<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 1100px;
            margin-top: 50px;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        h2,
        h5 {
            color: #1a3c6d;
            font-weight: 700;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .list-group-item {
            padding: 14px 20px;
            border-bottom: 1px solid #e0e4e8;
        }

        .btn {
            padding: 6px 12px;
            font-size: .875rem;
            border-radius: 6px;
            transition: all .3s ease;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: .8rem;
        }

        .btn-back {
            background-color: #6c757d;
        }

        .btn-edit {
            background-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .accordion-button:not(.collapsed) {
            background-color: #e6f0fa;
            color: #1a3c6d;
        }

        .badge-status {
            font-size: .85rem;
        }

        .table-sm th,
        .table-sm td {
            padding: .5rem;
        }

        .action-btns {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .action-btns {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Detalle del Usuario</h2>
        <hr>

        <!-- User Info Card -->
        <div class="card mb-4">
            <div class="card-header">Información Personal</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Nombre:</strong> {{ $user->name }} {{ $user->surname }}</li>
                <li class="list-group-item"><strong>DNI:</strong> {{ $user->dni }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                <li class="list-group-item">
                    <strong>Rol:</strong>
                    <span class="badge bg-primary">{{ $user->role?->name ?? 'Sin rol' }}</span>
                </li>
            </ul>
        </div>

        <!-- Actions -->
        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('user.logged') }}" class="btn btn-back text-white">Volver</a>
            <button type="button" class="btn btn-edit text-white" data-bs-toggle="modal"
                data-bs-target="#editUserModal">Editar</button>
            <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                onsubmit="return confirm('¿Eliminar usuario?');" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger text-white">Eliminar</button>
            </form>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
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
        <!-- Collapsible Sections -->
        <div class="accordion" id="userDetailsAccordion">

            <!-- 1. Cars -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseCars">
                        <strong>Vehículos Registrados ({{ $user->cars->count() }})</strong>
                    </button>
                </h2>
                <div id="collapseCars" class="accordion-collapse collapse" data-bs-parent="#userDetailsAccordion">
                    <div class="accordion-body">
                        @if ($user->cars->isEmpty())
                            <p class="text-muted">No hay vehículos registrados.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Patente</th>
                                            <th>Registrado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->cars as $car)
                                            <tr>
                                                <td><strong>{{ $car->car_plate }}</strong></td>
                                                <td>{{ $car->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="action-btns text-center">
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#editCar{{ $car->id }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('cars.destroy', $car) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('¿Eliminar vehículo {{ $car->car_plate }}?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 2. Infractions -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseInfractions">
                        <strong>Multas ({{ $user->cars->sum(fn($c) => $c->infractions->count()) }})</strong>
                    </button>
                </h2>
                <div id="collapseInfractions" class="accordion-collapse collapse"
                    data-bs-parent="#userDetailsAccordion">
                    <div class="accordion-body">
                        @if ($user->infractions->isEmpty())
                            <p class="text-muted">No tiene multas.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Patente</th>
                                            <th>Monto</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->infractions as $infraction)
                                            <tr>
                                                <td>{{ $infraction->car->car_plate }}</td>
                                                <td>${{ number_format($infraction->fine, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($infraction->date)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $infraction->status == 'pendiente' ? 'bg-warning' : 'bg-success' }}">
                                                        {{ ucfirst($infraction->status) }}
                                                    </span>
                                                </td>
                                                <td class="action-btns text-center">
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#editInfraction{{ $infraction->id }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('infractions.destroy', $infraction) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('¿Eliminar multa?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 3. Parking Sessions -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseSessions">
                        <strong>Sesiones de Estacionamiento
                            ({{ $user->cars->sum(fn($c) => $c->parkingSessions->count()) }})</strong>
                    </button>
                </h2>
                <div id="collapseSessions" class="accordion-collapse collapse" data-bs-parent="#userDetailsAccordion">
                    <div class="accordion-body">
                        @php
                            $hasSessions = $user->cars->contains(fn($car) => $car->parkingSessions->isNotEmpty());
                        @endphp

                        @if (!$hasSessions)
                            <p class="text-muted">No hay sesiones de estacionamiento.</p>
                        @else
                            @foreach ($user->cars as $car)
                                @if ($car->parkingSessions->isNotEmpty())
                                    <h6 class="mt-3 mb-2">
                                        <strong>Patente:</strong> {{ $car->car_plate }}
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Zona</th>
                                                    <th>Calle</th>
                                                    <th>Inicio</th>
                                                    <th>Fin</th>
                                                    <th>Monto</th>
                                                    <th>Estado</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($car->parkingSessions as $session)
                                                    <tr>
                                                        <td>{{ $session->zone?->name ?? '-' }}</td>
                                                        <td>{{ $session->street?->name ?? '-' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($session->start_time)->format('d/m H:i') }}
                                                        </td>
                                                        <td>{{ $session->end_time ? \Carbon\Carbon::parse($session->end_time)->format('d/m H:i') : '-' }}
                                                        </td>
                                                        <td>${{ number_format($session->amount, 0, ',', '.') }}</td>
                                                        <td>
                                                            <span
                                                                class="badge
                                                                @if ($session->status == 'active') bg-success
                                                                @elseif($session->status == 'expired') bg-danger
                                                                @elseif($session->status == 'cancelled') bg-secondary
                                                                @else bg-warning @endif">
                                                                {{ ucfirst($session->status) }}
                                                            </span>
                                                        </td>
                                                        <td class="action-btns text-center">
                                                            <button class="btn btn-primary btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editSession{{ $session->id }}">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <form action="{{ route('parking.destroy', $session) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('¿Eliminar sesión?');">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== MODALS ==================== -->

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Editar Usuario #{{ $user->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('user.update', $user) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="surname" class="form-control"
                                value="{{ old('surname', $user->surname) }}" required>
                            @error('surname')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">DNI</label>
                            <input type="text" name="dni" class="form-control"
                                value="{{ old('dni', $user->dni) }}" required>
                            @error('dni')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <select name="role_id" class="form-select" required>
                                @foreach ($roles as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('role_id', $user->role_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Car Modals -->
    @foreach ($user->cars as $car)
        <div class="modal fade" id="editCar{{ $car->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Editar Vehículo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('cars.update', $car) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Patente</label>
                                <input type="text" name="car_plate" class="form-control"
                                    value="{{ $car->car_plate }}" required>
                                @error('car_plate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Edit Infraction Modals -->
    @foreach ($user->infractions as $infraction)
        <div class="modal fade" id="editInfraction{{ $infraction->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Editar Multa #{{ $infraction->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('infractions.update', $infraction) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="modal-body">
                            <!-- Patente (solo lectura) -->
                            <div class="mb-3">
                                <label class="form-label">Patente</label>
                                <input type="text" class="form-control" value="{{ $infraction->car->car_plate }}"
                                    readonly>
                            </div>

                            <!-- Monto -->
                            <div class="mb-3">
                                <label class="form-label">Monto (ARS)</label>
                                <input type="number" name="fine" class="form-control"
                                    value="{{ $infraction->fine }}" min="0" step="1" required>
                            </div>

                            <!-- Fecha -->
                            <div class="mbajalabel class="form-label">Fecha</label>
                                <input type="date" name="date" class="form-control"
                                    value="{{ $infraction->date }}" required>
                            </div>

                            <!-- Estado -->
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select" required>
                                    <option value="pendiente"
                                        {{ $infraction->status == 'pendiente' ? 'selected' : '' }}>
                                        Pendiente
                                    </option>
                                    <option value="pagada" {{ $infraction->status == 'pagada' ? 'selected' : '' }}>
                                        Pagada
                                    </option>
                                    <option value="cancelada"
                                        {{ $infraction->status == 'cancelada' ? 'selected' : '' }}>
                                        Cancelada
                                    </option>
                                </select>
                            </div>

                            <!-- Errores -->
                            @if ($errors->hasAny(['fine', 'date', 'status']))
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @error('fine')
                                            <li>{{ $message }}</li>
                                        @enderror
                                        @error('date')
                                            <li>{{ $message }}</li>
                                        @enderror
                                        @error('status')
                                            <li>{{ $message }}</li>
                                        @enderror
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- resources/views/partials/_edit_parking_modal.blade.php --}}
    @foreach ($user->cars->flatMap(fn($car) => $car->parkingSessions ?? []) as $session)
        <div class="modal fade" id="editSession{{ $session->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('parking.update', $session) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Editar Sesión #{{ $session->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Vehículo -->
                            <div class="mb-3">
                                <label class="form-label">Vehículo</label>
                                <select name="car_id" class="form-select">
                                    @foreach ($user->cars as $car)
                                        <option value="{{ $car->id }}"
                                            {{ $car->id == $session->car_id ? 'selected' : '' }}>
                                            {{ $car->license_plate ?? strtoupper($car->car_plate ?? 'N/A') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Zona y Calle -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Zona</label>
                                    <select name="zone_id" class="form-select" required
                                        onchange="loadStreets(this.value, {{ $session->id }})">
                                        <option value="">-- Seleccione --</option>
                                        @foreach (\App\Models\Zone::all() as $zone)
                                            <option value="{{ $zone->id }}" data-rate="{{ $zone->rate }}"
                                                {{ $zone->id == $session->zone_id ? 'selected' : '' }}>
                                                {{ $zone->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Calle</label>
                                    <select name="street_id" class="form-select" required>
                                        <option value="">-- Seleccione zona --</option>
                                        @foreach (\App\Models\Street::where('zone_id', $session->zone_id)->get() as $street)
                                            <option value="{{ $street->id }}"
                                                {{ $street->id == $session->street_id ? 'selected' : '' }}>
                                                {{ $street->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Fecha y Hora de Inicio -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha de Inicio</label>
                                    <input type="date" name="start_date" class="form-control" required
                                        value="{{ \Carbon\Carbon::parse($session->start_time)->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Hora de Inicio</label>
                                    <input type="text" name="start_time" class="form-control" placeholder="14:30"
                                        value="{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}"
                                        pattern="^[0-2][0-9]:[0-5][0-9]$" required>
                                    <small class="text-muted">Formato 24h</small>
                                </div>
                            </div>

                            <!-- Duración -->
                            <div class="mb-3">
                                <label class="form-label">Duración</label>
                                <select name="duration" class="form-select" required
                                    onchange="updateAmount({{ $session->id }})">
                                    @foreach ([60, 120, 180, 240, 360, 480] as $min)
                                        <option value="{{ $min }}"
                                            {{ $min == $session->duration ? 'selected' : '' }}>
                                            {{ $min / 60 }} hora{{ $min > 60 ? 's' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Estado (STATUS) -->
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $session->status == 'pending' ? 'selected' : '' }}>
                                        Pendiente</option>
                                    <option value="active" {{ $session->status == 'active' ? 'selected' : '' }}>Activa
                                    </option>
                                    <option value="expired" {{ $session->status == 'expired' ? 'selected' : '' }}>
                                        Expirada</option>
                                    <option value="cancelled" {{ $session->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelada</option>
                                </select>
                            </div>

                            <!-- Monto (solo lectura) -->
                            <div class="mb-3">
                                <label class="form-label">Monto (ARS)</label>
                                <input type="text" id="amount{{ $session->id }}" class="form-control"
                                    value="{{ $session->amount }}" readonly>
                            </div>

                            <!-- Timezone offset (oculto) -->
                            <input type="hidden" name="timezone_offset" value="">

                            <!-- Errores -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>


<script>
    function loadStreets(zoneId, sessionId) {
        if (!zoneId) return;
        fetch(`/parking/streets/${zoneId}`)
            .then(r => r.json())
            .then(streets => {
                const select = document.querySelector(`#editSession${sessionId} select[name=street_id]`);
                select.innerHTML = '<option value="">-- Seleccione --</option>';
                streets.forEach(s => {
                    select.insertAdjacentHTML('beforeend', `<option value="${s.id}">${s.name}</option>`);
                });
                updateAmount(sessionId);
            });
    }

    function updateAmount(sessionId) {
        const modal = document.getElementById(`editSession${sessionId}`);
        const zoneSelect = modal.querySelector('select[name=zone_id]');
        const duration = modal.querySelector('select[name=duration]').value;
        if (!zoneSelect.value || !duration) return;

        const rate = zoneSelect.selectedOptions[0].dataset.rate || 5.0;
        const amount = (duration / 60) * rate;
        modal.querySelector(`#amount${sessionId}`).value = amount.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Timezone offset
        document.querySelectorAll('[name=timezone_offset]').forEach(input => {
            input.value = new Date().getTimezoneOffset();
        });

        // Monto inicial
        document.querySelectorAll('[id^=editSession]').forEach(modal => {
            const sessionId = modal.id.replace('editSession', '');
            updateAmount(sessionId);
        });
    });
</script>

</html>
