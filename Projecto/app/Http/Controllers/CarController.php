<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;

class CarController extends Controller
{
    // Mostrar todos los autos
    public function index()
    {
        //
    }

    // Mostrar el formulario de creación
    public function create()
    {
        $users = User::all();
        return view('cars.create', compact('users'));
    }

    // Guardar un nuevo auto
    public function store(Request $request)
    {
        $request->validate([
            'car_plate' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        Car::create($request->only('car_plate', 'user_id'));

        return redirect()->route('cars.index')->with('success', 'Auto creado correctamente.');
    }

    // Mostrar el formulario de edición
    public function edit(Car $car)
    {
        $users = User::all();
        return view('cars.edit', compact('car', 'users'));
    }

    // Actualizar un auto
    public function update(Request $request, Car $car)
    {
        $request->validate([
            'car_plate' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $car->update($request->only('car_plate', 'user_id'));

        return redirect()->route('cars.index')->with('success', 'Auto actualizado correctamente.');
    }

    // Eliminar un auto
    public function destroy(Car $car)
    {
        $car->delete();

        return redirect()->route('cars.index')->with('success', 'Auto eliminado correctamente.');
    }
}
