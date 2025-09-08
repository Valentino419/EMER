<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Infracciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .table th, .table td {
                min-width: 120px;
            }
        }
        .back-arrow {
            display: inline-block;
            font-size: 32px; /* más grande */
            font-weight: bold;
            color: #1a3c6d;
            text-decoration: none;
            margin-bottom: 15px;
            background: #fff;
            border-radius: 50%;
            padding: 8px 14px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }

        .back-arrow:hover {
            background: #007bff;
            color: #fff;
            transform: scale(1.1);
        }
</style>
<body>
<div class="container mt-4">
    <a href="{{ route('dashboard') }}" class="back-arrow" title="Volver al inicio">
        &#8592;
    </a> <h2 class="mb-4">Listado de Infracciones</h2> 
   
    <hr>
    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'inspector')
        <a href="{{ route('infractions.create') }}" class="btn btn-primary mb-3">Nueva Infracción</a>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Inspector</th>
                <th>Auto</th>
                <th>Multa</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($infractions as $infraction)
            <tr>
                <td>{{ $infraction->id }}</td>
                <td>{{ $infraction->inspector->name }}</td>
                <td>{{ $infraction->car->car_plate }}</td>
                <td>${{ $infraction->fine }}</td>
                <td>{{ $infraction->date }}</td>
                <td>{{ $infraction->status }}</td>
                <td>
                   
                    <button class="btn btn-primary editBtn"
                            data-id="{{ $infraction->id }}"
                            data-inspector="{{ $infraction->inspector->name }}"
                            data-car="{{ $infraction->car->car_plate }}"
                            data-fine="{{ $infraction->fine }}"
                            data-date="{{ $infraction->date }}"
                            data-status="{{ $infraction->status }}">
                        Editar
                    </button>

                    <form action="{{ route('infractions.destroy', $infraction) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Estás seguro de eliminar esta infracción?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Infracción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="fine" class="form-label">Multa</label>
                    <input type="number" class="form-control" id="fine" name="fine">
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="date" name="date">
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Estado</label>
                    <select class="form-control" id="status" name="status">
                        <option value="pendiente">Pendiente</option>
                        <option value="pagada">Pagada</option>
                        <option value="anulada">Anulada</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    const form = document.getElementById('editForm');

    document.querySelectorAll('.editBtn').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const fine = button.getAttribute('data-fine');
            const date = button.getAttribute('data-date');
            const status = button.getAttribute('data-status');

            // Rellenar inputs
            document.getElementById('fine').value = fine;
            document.getElementById('date').value = date;
            document.getElementById('status').value = status;

            // Cambiar la action del formulario
            form.action = `/infractions/${id}`;

            // Mostrar modal
            editModal.show();
        });
    });
});
</script>
</body>
</html>
