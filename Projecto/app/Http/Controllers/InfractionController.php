<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Infraction;
use App\Models\User;
use App\Traits\LicensePlateValidator;
use Illuminate\Http\Request;

class InfractionController extends Controller
{
    use LicensePlateValidator;

    // Mostrar listado de infracciones del inspector logueado
    public function index(Request $request)
    {
        $user = auth()->user();

        // Query principal
        $query = Infraction::with('car')
            ->where('user_id', $user->id);

        // Filtrado por patente si viene 'search'
        if ($search = $request->input('search')) {
            $query->whereHas('car', function ($q) use ($search) {
                $q->where('car_plate', 'like', "%{$search}%");
            });
        }

        $infractions = $query->get();

        return view('infractions.index', compact('infractions'));
    }

    // Formulario para crear nueva infracción (solo admin o inspector)
    public function create()
    {
        $user = auth()->user();

        // Traemos autos que pueda usar el inspector
        $cars = Car::all();
        $infractions = Infraction::all();
        $inspectors = User::all();

        return view('infractions.admin.create', compact('cars', 'infractions', 'inspectors'));
    }

    // Guardar nueva infracción
    public function store(Request $request)
    {
        //dd($request->all());
        
        $plate = $request->input('car_plate');
        $result = $this->validateAndCleanLicensePlate($plate); // Now this works!
        //DD($result);
        if (! $result['valid']) {
            return back()->withErrors(['car_plate' => 'Invalid license plate format']);
        }

        // Search for existing car
        $car = Car::where('car_plate', $result['cleaned'])
            ->first(); // Use exact match for better performance

        if (! $car) {
            $car = Car::create([
                'user_id' => 0,
                'car_plate' => $result['cleaned'],
            ]);
        }

        try {
            // Create the infraction
            Infraction::create([
                'user_id' => $car->user_id,
                'car_id' => $car->car_id,
                'fine' => 5000,
                'date' => now()->format('Y-m-d'),
                'status' => 'pending',
            ]);

            return redirect()->route('infractions.index')
                ->with('success', 'Infracción registrada correctamente para '.$car->car_plate);

        } catch (\Exception $e) {
            return back()->withErrors([
                'car_plate' => 'Error creating infraction: '.$e->getMessage(),
            ]);
        }

    }

    // Editar infracción (solo si pertenece al inspector)
    public function edit(Infraction $infraction)
    {
        $user = auth()->user();

        if ($infraction->user_id != $user->id) {
            abort(403, 'No tiene permiso para editar esta infracción.');
        }

        $cars = Car::all();

        return view('infractions.edit', compact('infraction', 'cars'));
    }

    // Actualizar infracción
    public function update(Request $request, Infraction $infraction)
    {
        $user = auth()->user();

        if ($infraction->user_id != $user->id) {
            abort(403, 'No tiene permiso para actualizar esta infracción.');
        }

        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'fine' => 'required|integer|min:0',
            'date' => 'required|date',
            'status' => 'required|string|max:255',
        ]);

        $infraction->update([
            'car_id' => $request->car_id,
            'fine' => $request->fine,
            'date' => $request->date,
            'status' => $request->status,
        ]);

        return redirect()->route('infractions.index')->with('success', 'Infracción actualizada correctamente.');
    }

    // Eliminar infracción (solo si pertenece al inspector)
    public function destroy(Infraction $infraction)
    {
        $user = auth()->user();

        if ($infraction->user_id != $user->id) {
            abort(403, 'No tiene permiso para eliminar esta infracción.');
        }

        $infraction->delete();

        return redirect()->route('infractions.index')->with('success', 'Infracción eliminada correctamente.');
    }
}
