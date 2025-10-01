<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Logueados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 15px; }
        h1 { color: #1a3c6d; font-weight: 600; margin-bottom: 20px; }
        .table { border-radius: 10px; overflow: hidden; border: 1px solid #e0e4e8; }
        .table th { background-color: #4a90e2; color: white; font-weight: 600; padding: 12px; }
        .table td { padding: 12px; vertical-align: middle; }
        .btn-secondary { background-color: #6c757d; border: none; padding: 6px 12px; border-radius: 5px; }
        .btn-secondary:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Usuarios Logueados</h1>

        @if(Auth::check())
            <p>Usuario actual: {{ Auth::user()->name }} | Rol: {{ Auth::user()->role->name ?? 'Sin rol' }}</p>
        @endif

        <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Volver al Inicio</a>

        @if($loggedUsers->isEmpty())
            <p class="text-center">No hay usuarios logueados en este momento.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loggedUsers as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role_name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>