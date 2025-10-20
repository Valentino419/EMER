@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Nueva Calle</h1>
    <a href="{{ route('zones.show', ['zone' => $zone_id]) }}" class="btn btn-secondary">Volver</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('street.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre de la Calle</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label for="start_number" class="form-label">Inicio del Cobro</label>
            <input type="number" name="start_number" id="start_number" class="form-control" value="{{ old('start_number') }}" required>
        </div>
        <div class="mb-3">
            <label for="end_number" class="form-label">Fin del Cobro</label>
            <input type="number" name="end_number" id="end_number" class="form-control" value="{{ old('end_number') }}" required>
        </div>
        <div class="mb-3">
            <label for="zone_id" class="form-label">Zona</label>
            <select name="zone_id" id="zone_id" class="form-control" required>
                @foreach ($zones as $z)
                    <option value="{{ $z->id }}" {{ $zone_id == $z->id ? 'selected' : '' }}>{{ $z->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="start_lat" class="form-label">Latitud Inicio (opcional, usa decimales ej: -33.007200)</label>
            <input type="number" step="0.000001" name="start_lat" id="start_lat" class="form-control" value="{{ old('start_lat') }}" placeholder="Ej: -33.007200">
        </div>
        <div class="mb-3">
            <label for="start_lng" class="form-label">Longitud Inicio (opcional, usa decimales ej: -58.520500)</label>
            <input type="number" step="0.000001" name="start_lng" id="start_lng" class="form-control" value="{{ old('start_lng') }}" placeholder="Ej: -58.520500">
        </div>
        <div class="mb-3">
            <label for="end_lat" class="form-label">Latitud Fin (opcional, usa decimales ej: -33.007200)</label>
            <input type="number" step="0.000001" name="end_lat" id="end_lat" class="form-control" value="{{ old('end_lat') }}" placeholder="Ej: -33.007200">
        </div>
        <div class="mb-3">
            <label for="end_lng" class="form-label">Longitud Fin (opcional, usa decimales ej: -58.516000)</label>
            <input type="number" step="0.000001" name="end_lng" id="end_lng" class="form-control" value="{{ old('end_lng') }}" placeholder="Ej: -58.516000">
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
@endsection