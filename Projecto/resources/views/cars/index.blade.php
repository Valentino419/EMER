<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Autos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
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
</head>
<body>
    <div class="container">
        <a href="{{ route('dashboard') }}" class="back-arrow" title="Volver al inicio">
            &#8592;
        </a>
        <h2>Lista de Autos</h2>
        <hr>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <a>
        <a href="{{ route('cars.create') }}" class="btn btn-primary mb-3"> Registrar nuevo auto</a>
       
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cars as $car)
                    <tr>
                        <td>{{ $car->id }}</td>
                        <td>{{ $car->car_plate }}</td>
                        <td>
                           
                        <a href="{{ route('cars.edit' , $car->id)}}" class="btn btn-primary btn-sm">Editar</a>   
                        <form action="{{ route('cars.destroy', $car->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este auto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if($cars->isEmpty())
                    <tr>
                        <td colspan="4">No hay autos registrados.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>
</html>
