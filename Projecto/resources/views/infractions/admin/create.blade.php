<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Infracción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #1a3c6d;
            font-weight: 700;
            margin-bottom: 25px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2>Registrar Nueva Infracción</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('infractions.store') }}" method="POST" class="mt-3">
            @csrf

            {{-- Buscador de autos --}}
            <div class="mb-3">
                <label for="car_plate" class="form-label">Auto (buscar por patente)</label>
                <input class="form-control" list="carsList" id="car_plate" name="car_plate"
                    placeholder="Escribí la patente..." required>
                <datalist id="carsList">
                    @foreach ($cars as $car)
                        <option value="{{ $car->id }}">{{ $car->car_plate }}</option>
                    @endforeach
                </datalist>
            </div>

            {{-- Multa fija en $5000 --}}
            <div class="mb-3">
                <label for="fine" class="form-label">Monto de la Multa</label>
                <input type="number" name="fine" id="fine" class="form-control" value="5000" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Registrar</button>
            <a href="{{ route('infractions.index') }}" class="btn btn-secondary">Cancelar</a>
          

        </form>
    </div>
</body>

</html>