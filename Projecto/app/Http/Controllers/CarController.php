<?php
namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::with('user')->get();
        $users = User::all();

        return view('cars.index', compact('cars','users'));
    }

    
    public function create()
    {
        $users = User::all(); // Para seleccionar el dueño del auto
        return view('cars.create', compact('users'));
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'car_plate' => 'required|string|max:10',
            'user_id' => 'required|exists:users,id'
        ]);

        Car::create($request->only('car_plate', 'user_id'));

        return redirect()->route('cars.index')->with('success', 'Auto creado correctamente.');
    }

   
    public function edit(Car $car)
    {
        $users = User::all();
        return view('cars.edit', compact('car', 'users'));
    }

   
    public function update(Request $request, Car $car)
    {
        $request->validate([
            'car_plate' => 'required|string|max:10',
            'user_id' => 'required|exists:users,id'
        ]);

        $car->update($request->only('car_plate', 'user_id'));

        return redirect()->route('cars.index')->with('success', 'Auto actualizado.');
    }

   
    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('cars.index')->with('success', 'Auto eliminado.');
    }
}
