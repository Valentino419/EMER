<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin-top: 40px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #343a40;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .table {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }

        .table th {
            background-color: #007bff;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 15px;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }

        .table-hover tbody tr:hover {
            background-color: #e9ecef;
            transition: background-color 0.2s ease;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 8px 16px;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            padding: 8px 16px;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-danger:hover {
            background-color: #b02a37;
            transform: translateY(-2px);
        }

        .alert-success {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Usuario</h2>

        <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <hr>
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" value="{{old('name', $user->name)}}">
                @error('type')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div> <br>

            <div class="form-group">
                <label for="name">Apellido</label>
                <input type="text" name="surname" id="surname" value="{{old('surname', $user->surname)}}">
                @error('type')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div> <br>
           
            <div class="form-group">
                <label for="name">dni</label>
                <input type="number" name="dni" id="dni" value="{{old('dni', $user->dni)}}">
                @error('type')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div> <br>
              <div class="form-group">
                <label for="name">Email</label>
                <input type="text" name="email" id="email" value="{{old('email', $user->email)}}">
                @error('type')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div> <br>
            
            <button type="submit" class="btn btn-primary" onsubmit="return confirm('¿Estás seguro de guardar los cambios?');"> Guardar cambios</button>
        </form>
    </div>
</body>
