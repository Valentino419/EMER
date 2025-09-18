@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Mis Infracciones</h2>
        <hr>

        {{-- Buscador por patente --}}
        <form method="GET" action="{{ route('infractions.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar por patente..."
                    value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </form>

        {{-- Mensaje de éxito --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Botón y tabla solo para admin e inspector --}}
       

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patente</th>
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
                            <td>{{ $infraction->car->car_plate }}</td>
                            <td>${{ $infraction->fine }}</td>
                            <td>{{ $infraction->date }}</td>
                            <td>{{ $infraction->status }}</td>
                            <td>
                                <a href="{{ route('infractions.edit', $infraction) }}" class="btn btn-sm btn-primary">Editar</a>
                           
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
@endsection