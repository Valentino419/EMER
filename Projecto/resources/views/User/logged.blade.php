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

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 8px 18px;
            font-weight: 500;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            padding: 8px 18px;
            font-weight: 500;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-danger:hover {
            background-color: #b02a37;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        .modal-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 16px;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.25rem;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-body form div {
            margin-bottom: 15px;
        }

        .modal-body label {
            color: #1a3c6d;
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
        }

        .modal-body input,
        .modal-body select {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0e4e8;
            border-radius: 6px;
            font-size: 1rem;
            color: #333;
            background-color: #f8fafc;
            transition: border-color 0.3s ease;
        }

        .modal-body input:focus,
        .modal-body select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .modal-body .error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
            display: block;
        }

        .modal-footer {
            padding: 15px;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            background-color: #f8fafc;
        }

        .modal-footer .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            font-weight: 500;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .modal-footer .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .modal-footer .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 10px 20px;
            font-weight: 500;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .modal-footer .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
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

            .modal-dialog {
                margin: 10px;
            }
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
                    <a href="{{ route('user.logged') }}" class="btn btn-secondary">Limpiar</a>
                @endif
            </div>
        </form>



        @if ($loggedUsers->isEmpty())
            <p class="text-center">No hay usuarios logueados en este momento.</p>
        @else
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
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role_name }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalEditarUsuario{{ $user->id }}">Editar</button>
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
        @endif


        <!-- modal editar -->
        @foreach ($loggedUsers as $user)
            <div class="modal fade" id="modalEditarUsuario{{ $user->id }}" tabindex="-1"
                aria-labelledby="modalEditarUsuarioLabel{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditarUsuarioLabel{{ $user->id }}">Editar Usuario
                                #{{ $user->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('user.update', $user) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <h2>Editar Usuario</h2>

                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" name="name" id="name"
                                        value="{{ old('name', $user->name) }}">
                                    @error('type')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div> <br>

                                <div class="form-group">
                                    <label for="name">Apellido</label>
                                    <input type="text" name="surname" id="surname"
                                        value="{{ old('surname', $user->surname) }}">
                                    @error('type')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div> <br>

                                <div class="form-group">
                                    <label for="name">dni</label>
                                    <input type="number" name="dni" id="dni"
                                        value="{{ old('dni', $user->dni) }}">
                                    @error('type')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div> <br>
                                <div class="form-group">
                                    <label for="name">Email</label>
                                    <input type="text" name="email" id="email"
                                        value="{{ old('email', $user->email) }}">
                                    @error('type')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div> <br>

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary"
                                    onsubmit="return confirm('¿Estás seguro de guardar los cambios?');"> Guardar
                                    cambios</button>
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
