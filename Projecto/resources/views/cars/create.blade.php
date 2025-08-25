<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrar nuevo auto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
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
    <div class="container">
        <h2>Registrar nuevo auto</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

       <form action="{{ route('cars.store') }}" method="POST">
    @csrf
    <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <div class="mb-3">
            <label for="brand" class="form-label">Marca</label>
            <input type="text" name="brand" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="model" class="form-label">Modelo</label>
            <input type="text" name="model" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="owner_id" class="form-label">Dueño</label>
            <select name="owner_id" class="form-select" required>
                <option value="">Seleccione un dueño</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
    <script>
    document.getElementById("btnCreate").addEventListener("click", function() {
    // Llamamos a la ruta del create
    fetch("{{ route('cars.create') }}")
        .then(response => response.text())
        .then(html => {
            document.getElementById("createContent").innerHTML = html;
        })
        .catch(err => {
            document.getElementById("createContent").innerHTML = 
              '<div class="p-3 text-danger">Error al cargar el formulario</div>';
            console.error(err);
        });
});
</script>
</form>
</body>
</html>
