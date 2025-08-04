@extends('layouts.app')

@section('content')
    <h1>Lista de Autos</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('cars.create') }}">Registrar nuevo auto</a>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Patente</th>
                <th>Dueño</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cars as $car)
                <tr>
                    <td>{{ $car->id }}</td>
                    <td>{{ $car->car_plate }}</td>
                    <td>{{ $car->user->name ?? 'Sin usuario' }}</td>
                    <td>
                        <a href="{{ route('cars.edit', $car) }}">Editar</a>
                        <form action="{{ route('cars.destroy', $car) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Eliminar este auto?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No hay autos registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
