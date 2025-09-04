<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inspectores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        background-color: #f0f4f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
        max-width: 1200px;
        margin-top: 40px;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #1a3c6d;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .table {
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e0e4e8;
    }

    .table th {
        background-color: #007bff;
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

    .alert-success {
        border-radius: 8px;
        margin-bottom: 20px;
        background-color: #d4edda;
        color: #155724;
    }

    /* Search Form Styles */
    .search-form {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .search-form input[type="text"] {
        width: 100%;
        max-width: 400px;
        padding: 10px;
        border: 1px solid #e0e4e8;
        border-radius: 6px;
        font-size: 1rem;
        color: #333;
        background-color: #f8fafc;
        transition: border-color 0.3s ease;
    }

    .search-form input[type="text"]:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
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

    /* Pagination Styles */
    .pagination {
        margin-top: 20px;
        justify-content: center;
    }

    .pagination .page-link {
        border-radius: 6px;
        border: 1px solid #e0e4e8;
        color: #007bff;
        font-weight: 500;
        margin: 0 3px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .pagination .page-link:hover {
        background-color: #e6f0fa;
        color: #0056b3;
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        cursor: not-allowed;
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

        .search-form {
            flex-direction: column;
            align-items: stretch;
        }

        .search-form input[type="text"] {
            max-width: 100%;
        }
    }
</style>

<body>
    <div class="container">
        <h2>Lista de Inspectores</h2>
        <hr>

        <!-- Search Form -->
        <form action="{{ route('inspectors.index') }}" method="GET" class="search-form">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, apellido, DNI o email">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

    <a href="{{ route('inspectors.create') }}" class="btn btn-primary mb-3">+ Agregar Inspector</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nombre de Usuario</th>
            <th>Email</th>
            <th>Número de Placa</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($inspectors as $inspector)
            <tr>
                <td>{{ $inspector->id }}</td>
                <td>{{$inspector->user ? $inspector->user->name: 'Sin usuario'}}</td>
                <td>{{ $inspector->user ? $inspector->user->email: 'Sin email' }}</td>
                <td>{{ $inspector->badge_number }}</td>
                <td>
                    
                <button type="button" class="btn btn-sm btn-primary"> Editar</button>
                    <form action="{{ route('inspectors.destroy', $inspector) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('¿Estás seguro de eliminar este inspector?');">
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
</body>

</html>