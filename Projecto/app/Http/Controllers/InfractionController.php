<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Infraction;
use App\Models\User;
use App\Models\ParkingSession;
use App\Notifications\InfraccionNotification;
use App\Traits\LicensePlateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class InfractionController extends Controller
{
    use LicensePlateValidator;

    // app/Http/Controllers/InfractionController.php

    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $carPlateFilter = $request->get('car_plate'); // ← NUEVO: filtro desde autos

        $infractions = collect();
        $car = null;
        $activeParkingSession = null;
        $carStatus = null;

        // === 1. FILTRO POR PATENTE DESDE AUTOS ===
        if ($carPlateFilter) {
            $plate = $this->validateAndCleanLicensePlate($carPlateFilter);
            if ($plate['valid']) {
                $car = Car::where('car_plate', $plate['cleaned'])->first();
                if ($car) {
                    $infractions = Infraction::where('car_id', $car->id)
                        ->with('car')
                        ->latest()
                        ->paginate(10);
                    $infractions->appends(['car_plate' => $carPlateFilter]); // mantener filtro
                }
            }
            // Mostrar solo infracciones de esta patente
            $deudaPending = null;
            return view('infractions.index', compact(
                'infractions',
                'car',
                'deudaPending'
            ));
        }

        // === 2. BÚSQUEDA NORMAL (inspector busca cualquier patente) ===
        if ($search) {
            $plate = $this->validateAndCleanLicensePlate($search);
            if ($plate['valid']) {
                $cleanPlate = $plate['cleaned'];
                $car = Car::where('car_plate', $cleanPlate)->first();

                if ($car) {
                    $carStatus = 'encontrado';
                    $activeParkingSession = ParkingSession::where('car_id', $car->id)
                        ->where('status', 'active')
                        ->where('end_time', '>', now())
                        ->first();

                    $infractions = Infraction::where('car_id', $car->id)
                        ->with('car')
                        ->latest()
                        ->paginate(10);
                } else {
                    $carStatus = 'no_encontrado';
                }
            } else {
                $carStatus = 'formato_invalido';
            }
        } else {
            // === 3. LISTA NORMAL (según rol) ===
            $query = Infraction::with('car');
            if ($user->role->name !== 'admin' && $user->role->name !== 'inspector') {
                $query->whereHas('car', fn($q) => $q->where('user_id', Auth::id()));
            }
            $infractions = $query->latest()->paginate(10);
        }

        // === DEUDA PENDIENTE (solo usuarios) ===
        $deudaPending = null;
        if ($user->role->name === 'user') {
            $deudaPending = $user->infractions()->with('car')->where('status', 'pending')->first();
        }

        return view('infractions.index', compact(
            'infractions',
            'deudaPending',
            'search',
            'car',
            'activeParkingSession',
            'carStatus'
        ));
    }

    public function store(Request $request)
    {
        if (! in_array(Auth::user()->role->name, ['admin', 'inspector'])) {
            abort(403);
        }

        $request->validate([
            'car_plate' => 'required|string|max:10',
            'fine' => 'nullable|integer|min:0',
        ]);

        $plate = $this->validateAndCleanLicensePlate($request->car_plate);
        if (! $plate['valid']) {
            return back()->withErrors(['car_plate' => 'Patente inválida (formato incorrecto)'])->withInput();
        }

        $car = Car::firstOrCreate(
            ['car_plate' => $plate['cleaned']],
            ['user_id' => null] // Inspector no asigna dueño aún
        );

        // === EVITAR DUPLICADOS POR DÍA ===
        $today = now()->toDateString();
        $existing = Infraction::where('car_id', $car->id)
            ->whereDate('date', $today)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()
                ->with('error', "Ya existe una infracción pendiente HOY para la patente {$car->car_plate}.")
                ->withInput();
        }

        $infraction = Infraction::create([
            'user_id' => auth()->id(),
            'car_id' => $car->id,
            'fine' => 5000,
            'date' => $today,
            'status' => 'pending',
        ]);

        // === NOTIFICACIÓN ===
        if ($car->user_id && $car->user_id != 0) {
            $owner = User::find($car->user_id);
            if ($owner && $owner->email) {
                $owner->notify(new InfraccionNotification([
                    'car_plate' => $car->car_plate,
                    'date' => $infraction->date,
                    'hour' => now()->format('H:i'),
                    'ubication' => 'No especificado',
                    'infraccion_id' => $infraction->id,
                ]));
            }
        }

        return redirect()->route('infractions.index')
            ->with('success', "¡Multa registrada por $5000 a la patente {$car->car_plate}!");
    }

    public function edit(Infraction $infraction)
    {
        if (Auth::user()->role->name !== 'admin' && $infraction->user_id !== Auth::id()) {
            abort(403);
        }
        $cars = Car::with('user')->get();

        return view('infractions.edit', compact('infraction', 'cars'));
    }

    public function update(Request $request, Infraction $infraction)
    {
        // Solo admin puede editar cualquier multa
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Acceso denegado.');
        }

        $validated = $request->validate([
            'fine' => 'required|integer|min:0',
            'date' => 'required|date',
            'status' => 'required|in:pendiente,pagada,cancelada',
        ]);

        $infraction->update([
            'fine' => $validated['fine'],
            'date' => $validated['date'],
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Multa actualizada correctamente.');
    }

    public function destroy(Infraction $infraction)
    {
        if (Auth::user()->role->name !== 'admin' && $infraction->user_id !== Auth::id()) {
            abort(403);
        }
        $infraction->delete();

        return redirect()->route('infractions.index')->with('success', 'Infracción eliminada');
    }
}
