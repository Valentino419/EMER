<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Logueados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 15px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #1a3c6d;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e0e4e8;
        }

        .table th {
            background-color: #4a90e2;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 16px;
        }

        .table td {
            padding: 16px;
            vertical-align: middle;
            color: #333;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8fafc;
        }

        .table-hover tbody tr:hover {
            background-color: #e6f0fa;
            transition: background-color 0.3s ease;
        }

        .btn-primary,
        .btn-secondary,
        .btn-danger {
            padding: 8px 18px;
            font-weight: 500;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            border: none;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #b02a37;
            transform: translateY(-2px);
        }

        /* Paginación personalizada */
        .pagination {
            margin-top: 20px;
            justify-content: center;
        }

        .page-link {
            color: #1a3c6d;
            border: 1px solid #dee2e6;
            padding: 8px 16px;
            border-radius: 6px;
            margin: 0 4px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .page-link:hover {
            background-color: #e6f0fa;
            border-color: #4a90e2;
            color: #1a3c6d;
            transform: translateY(-1px);
        }

        .page-item.active .page-link {
            background-color: #4a90e2;
            border-color: #4a90e2;
            color: white;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .modal-body input,
        .modal-body select {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0e4e8;
            border-radius: 6px;
            background-color: #f8fafc;
        }

        .modal-body input:focus,
        .modal-body select:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .table th,
            .table td {
                min-width: 120px;
            }
        }

        /* Paginación Compacta y Elegante */
        .pagination {
            gap: 4px;
        }

        .page-link {
            padding: 6px 12px !important;
            font-size: 0.875rem;
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px !important;
            color: #1a3c6d;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
        }

        .page-link:hover {
            background-color: #e6f0fa;
            border-color: #4a90e2;
            color: #1a3c6d;
            transform: translateY(-1px);
        }

        .page-item.active .page-link {
            background-color: #4a90e2 !important;
            border-color: #4a90e2 !important;
            color: white !important;
            font-weight: 600;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        /* Flechas más pequeñas y sutiles */
        .page-link[aria-label="Previous"] span,
        .page-link[aria-label="Next"] span {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .pagination .page-link {
            padding: 6px 12px !important;
            font-size: .875rem;
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">Usuarios Logueados</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Volver al Inicio</a>

        <!-- Barra de búsqueda -->
        <form action="{{ route('user.logged') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Buscar por nombre, apellido, DNI, email o rol">
                <button type="submit" class="btn btn-primary">Buscar</button>
                @if (request('search'))
                    <a href="{{ route('user.logged') }}" class="btn btn-secondary ms-2">Limpiar</a>
                @endif
            </div>
        </form>

        @if ($loggedUsers->isEmpty())
            <p class="text-center text-muted">No hay usuarios logueados en este momento.</p>
        @else
            <p class="text-center text-muted mb-2">
                Mostrando {{ $loggedUsers->firstItem() }} al {{ $loggedUsers->lastItem() }} de
                {{ $loggedUsers->total() }} usuarios
            </p>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loggedUsers as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }} {{ $user->surname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $user->role_name }}</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalEditarUsuario{{ $user->id }}">
                                        Editar
                                    </button>
                                    <form action="{{ route('user.destroy', $user) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación (mantiene el parámetro de búsqueda) -->

            <div class="d-flex flex-column align-items-center mt-4">
                {{-- “Showing … results” line (optional but matches the screenshot) --}}
                @if ($loggedUsers->total())
                    <p class="text-muted mb-2">
                        Mostrando {{ $loggedUsers->firstItem() }} al {{ $loggedUsers->lastItem() }}
                        de {{ $loggedUsers->total() }} resultados
                    </p>
                @endif

                {{-- Pagination links – preserves ?search=… --}}
                {{ $loggedUsers->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif

        <!-- Modales de Edición -->
        @foreach ($loggedUsers as $user)
            <div class="modal fade" id="modalEditarUsuario{{ $user->id }}" tabindex="-1"
                aria-labelledby="modalEditarUsuarioLabel{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditarUsuarioLabel{{ $user->id }}">
                                Editar Usuario #{{ $user->id }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('user.update', $user) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Apellido</label>
                                    <input type="text" name="surname" class="form-control"
                                        value="{{ old('surname', $user->surname) }}">
                                    @error('surname')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">DNI</label>
                                    <input type="number" name="dni" class="form-control"
                                        value="{{ old('dni', $user->dni) }}">
                                    @error('dni')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <span class="error">{{ $message }}</span>
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
                                    @error('role_id')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary"
                                        onclick="return confirm('¿Estás seguro de guardar los cambios?')">
                                        Guardar Cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
