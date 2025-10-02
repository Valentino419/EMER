<?php

namespace App\Http\Controllers;

use App\Traits\LicensePlateValidator;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    use LicensePlateValidator;
    // Mostrar todos los autos
    public function index()
    {
        $user = auth()->user();

        if ($user->role->name === 'admin' || $user->role->name === 'inspector') {
            // Admin e inspectores ven todos
            $cars = Car::with('user')->latest()->paginate(10);
        } else {
            // Usuarios solo sus autos
            $cars = Car::where('user_id', $user->id)
                ->with('user')
                ->latest()
                ->paginate(10);
        }

        return view('cars.index', compact('cars'));
    }


    // Mostrar el formulario de creación
    public function create()
    {
        $users = User::all();
        if (Auth::user()->role->name == 'admin') {
            // Vista para administradores
            return view('cars.admin.create', compact('users'));
        } else {
            return view('cars.createUser', compact('users'));
        }
        // Vista para usuarios comunes
    }

    // Guardar un nuevo auto
    public function store(Request $request)
    {
        $request->validate([
            'car_plate' => [
                'required',
                'string',
                'max:10',
                Rule::unique('cars')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }), // Única por usuario (ignora otros usuarios/inspectores)
            ],
        ]);

        $plate = $request->input('car_plate');
        $result = $this->validateAndCleanLicensePlate($plate);

        if (!$result['valid']) {
            return back()->withErrors(['car_plate' => 'Formato de patente inválido.']); // Cambiado a 'car_plate'
        }

        Car::create([
            'car_plate' => $result['cleaned'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('cars.index')->with('success', 'Auto creado correctamente.');
    }

    // Mostrar el formulario de edición
    public function edit(Car $car)
    {
        $users = User::all();

        if (Auth::user()->role === 'admin') {
            // Vista para administradores
            return view('cars.admin.edit', compact('users', 'car'));
        } else {
            return view('cars.editUser', compact('users', 'car'));
        }
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
