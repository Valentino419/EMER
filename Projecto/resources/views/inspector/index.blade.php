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
</style>
<body>
 <div class="container">
    
    <h2>Lista de Inspectores</h2>
    <hr>

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
                    
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditarInspector{{ $inspector->id }}"> Editar</button>
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

@foreach ($inspectors as $inspector)
<div class="modal fade" id="modalEditarInspector{{ $inspector->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Editar Inspector #{{ $inspector->id }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <form action="{{ route('inspectors.update', $inspector->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-body">

          <!-- Usuario -->
          <div class="mb-3">
            <label class="form-label">Usuario</label>
            <select name="user_id" class="form-select" required>
              @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $user->id == $inspector->user_id ? 'selected' : '' }}>
                  {{ $user->name }} — {{ $user->email }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Email (del usuario asociado) -->
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email', $inspector->user->email) }}" required>
          </div>

          <!-- Número de placa -->
          <div class="mb-3">
            <label class="form-label">Número de Placa</label>
            <input type="text" name="badge_number" class="form-control"
                   value="{{ old('badge_number', $inspector->badge_number) }}" required>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

