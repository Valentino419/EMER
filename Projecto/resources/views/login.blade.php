<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg text-center">
        <h1 class="text-3xl font-bold mb-6">Bienvenido al Sistema de Estacionamiento medido de Entre Rios</Em></h1>
        <p class="text-gray-600 mb-8">Selecciona una sección para comenzar:</p>
        <div class="space-y-4">
            <a href="{{ route('machines.index') }}" class="block bg-blue-500 text-white py-3 px-6 rounded-lg hover:bg-blue-600 transition">Gestión de Máquinas</a>
            <a href="{{ route('works.index') }}" class="block bg-green-500 text-white py-3 px-6 rounded-lg hover:bg-green-600 transition">Gestión de Obras</a>
            <a href="{{ route('workMachines.create') }}" class="block bg-blue-300 text-white py-3 px-6 rounded-lg hover:bg-blue-400 transition">Asignaciones</a>
            <a href="{{ route('maintenances.index') }}" class="block bg-purple-500 text-white py-3 px-6 rounded-lg hover:bg-purple-600 transition">Gestión de Mantenimientos</a>
        </div>
    </div>
</body>
</html>