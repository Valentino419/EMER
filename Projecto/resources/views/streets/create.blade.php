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
            <label for="start_street" class="form-label">desde </label>
            <input type="text" name="start_street" id="start_street" class="form-control" value="{{ old('start_street') }}" required>
        </div>
        <div class="mb-3">
            <label for="end_street" class="form-label">hasta</label>
            <input type="text" name="end_street" id="end_street" class="form-control" value="{{ old('end_street') }}" required>
        </div>
        <div class="mb-3">
            <label for="zone_id" class="form-label">Zona</label>
            <select name="zone_id" id="zone_id" class="form-control" required>
                @foreach ($zones as $z)
                    <option value="{{ $z->id }}" {{ $zone_id == $z->id ? 'selected' : '' }}>{{ $z->name }}</option>
                @endforeach
            </select>
        </div>        
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
@endsection