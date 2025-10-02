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

    $query = Infraction::with(['car', 'inspector']); // eager loading

    // Verificamos rol sin romper si es null
    $roleName = $user->role->name ?? null;

    if (!in_array($roleName, ['admin', 'inspector'])) {
        // Usuario común: filtra por sus autos
        $userPlates = $user->cars()->pluck('car_plate')->toArray();

        $query->where(function ($q) use ($user, $userPlates) {
            $q->where('user_id', $user->id) // infracciones hechas por el usuario
              ->orWhereHas('car', function ($subQuery) use ($user, $userPlates) {
                  $subQuery->where('user_id', $user->id) // autos del usuario
                           ->orWhereIn('car_plate', $userPlates); // por patente
              });
        });
    }

    // Filtro de búsqueda (antes del paginate)
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->whereHas('car', function ($subQuery) use ($search) {
                $subQuery->where('car_plate', 'like', "%$search%");
            })
            ->orWhere('description', 'like', "%$search%");
        });
    }

    // Ahora sí ejecutamos la query
    $infractions = $query->latest()->paginate(10)->appends($request->query());

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
        $plate = $request->input('car_plate');
        $result = $this->validateAndCleanLicensePlate($plate);

        if (!$result['valid']) {
            return back()->withErrors(['car_plate' => 'Formato de patente inválido.']);
        }

        // Search for existing car
        $car = Car::where('car_plate', $result['cleaned'])->first();

        if (!$car) {
            $car = Car::create([
                'user_id' => 0, // Para inspectores, user_id = 0 (no asociado)
                'car_plate' => $result['cleaned'],
            ]);
        }

        try {
            // Create the infraction
            Infraction::create([
                'user_id' => auth()->id(), // Inspector como user_id
                'car_id' => $car->id,
                'fine' => 5000,
                'date' => now()->format('Y-m-d'),
                'status' => 'pending',
            ]);

            return redirect()
                ->route('infractions.index')
                ->with('success', 'Infracción registrada correctamente para ' . $car->car_plate);
        } catch (\Exception $e) {
            return back()->withErrors([
                'car_plate' => 'Error creating infraction: ' . $e->getMessage(),
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

        \Log::info('Loading view: infractions.edit', ['path' => resource_path('views/infractions/edit.blade.php')]);

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
