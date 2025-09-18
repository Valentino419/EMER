<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;gi

class CarController extends Controller
{
    // Mostrar todos los autos
    public function index()
    {
        $cars = Car::with('user')->get();
        $users = User::orderBy('name')->get();
        return view('cars.index',compact('cars','users'));
    }

    // Mostrar el formulario de creación
    public function create()
    {   
        $users = User::all();
        if (Auth::user()->role->name == 'admin') {
            // Vista para administradores
            return view('cars.admin.create', compact('users'));
        }else return view('cars.createUser', compact('users'));;
         // Vista para usuarios comunes
    }

    // Guardar un nuevo auto
    public function store(Request $request)
    {
        $request->validate([
            'car_plate' => 'required|string|max:255',
        ]);

        Car::create([
        'car_plate' => $request->car_plate,
        'user_id'=> Auth::id(), // Obtiene el ID del usuario autenticado
        ]);

        return redirect()->route('cars.index')->with('success', 'Auto creado correctamente.');
    }

    // Mostrar el formulario de edición
    public function edit(Car $car)
    {
        $users = User::all();

        if (Auth::user()->role === 'admin') {
            // Vista para administradores
            return view('cars.admin.edit', compact('users','car'));

        } else return view('cars.editUser', compact('users', 'car'));
        // Vista para usuarios comunes
    }

    // Actualizar un auto
    public function update(Request $request, Car $car)
    {
        $request->validate([
            'car_plate' => 'required|string|max:255',
            'user_id' => Auth::id(),
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
