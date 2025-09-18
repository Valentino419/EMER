<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    // Mostrar todos los autos
    public function index()
    {
        $cars = Car::with('user')->get();
        $users = User::orderBy('name')->get();

        return view('cars.index', compact('cars', 'users'));
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
       
    $plate = $request->input('car_plate');
    $result = $this->validateAndCleanLicensePlate($plate);
    DD($result);
    if (!$result['valid']) {
        return back()->withErrors(['license_plate' => 'Invalid license plate format']);
    }

        Car::create([
            'car_plate' => $result['cleaned'],
            'user_id' => Auth::id(), // Obtiene el ID del usuario autenticado
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

    public function validateAndCleanLicensePlate(string $plate): array
    {
        // Clean the input: remove non-alphanumeric chars, uppercase, trim
        $cleanedPlate = preg_replace('/[^A-Z0-9]/', '', strtoupper(trim($plate)));

        // Define regex patterns for each country
        $patterns = [
            'Argentina' => [
                '/^[A-Z]{2}\d{3}[A-Z]{2}$/',  // AA000AA (Mercosur)
                '/^[A-Z]{3}\d{3}$/',          // AAA000 (1995-2016)
                '/^[A-Z]\d{6}$/',              // A000000 (pre-1995)
            ],
            'Brazil' => [
                '/^[A-Z]{3}\d[A-Z]\d{2}$/',   // AAA0A00 (Mercosur)
                '/^[A-Z]{3}\d{4}$/',           // AAA0000 (pre-2018)
            ],
            'Uruguay' => [
                '/^[A-Z]{3}\d{4}$/',           // AAA0000 (Mercosur)
            ],
        ];

        $isValid = false;
        $validCountry = null;

        // Check each country
        foreach ($patterns as $country => $countryPatterns) {
            foreach ($countryPatterns as $pattern) {
                if (preg_match($pattern, $cleanedPlate)) {
                    $isValid = true;
                    $validCountry = $country;
                    break 2; // Break both loops
                }
            }
        }

        return [
            'valid' => $isValid,
            'cleaned' => $cleanedPlate,
            'country' => $validCountry,
            'original' => $plate,
        ];
    }
}
