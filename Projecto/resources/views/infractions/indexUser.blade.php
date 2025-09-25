<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Infracciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f5fa;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: #0d6efd;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="mb-0">🚗 Mis Infracciones</h3>
            </div>
            <div class="card-body">
                @if($infractions->isEmpty())
                    <div class="alert alert-info text-center">
                        No tienes infracciones registradas. 🎉
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped align-middle text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Patente</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Inspector</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($infractions as $index => $infraction)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $infraction->car->license_plate ?? 'N/A' }}</td>
                                        <td>{{ $infraction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>${{ number_format($infraction->amount, 2, ',', '.') }}</td>
                                        <td>{{ $infraction->inspector->name ?? 'No disponible' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">⬅ Volver al inicio</a>
            </div>
        </div>
    </div>
</body>

</html>