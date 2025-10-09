<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Infraction;
use App\Http\Controllers\InfraccionNotification;
use App\Http\Controllers\User;
use App\Traits\LicensePlateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class InfractionController extends Controller
{
    use LicensePlateValidator;

    public function index(Request $request)
    {
         $query = Infraction::with('car');

            if (Auth::user()->role->name !== 'admin' && Auth::user()->role->name !== 'inspector') {
             $query->whereHas('car', fn($q) => $q->where('user_id', Auth::id()));
            }

            if ($request->filled('search')) {
              $query->whereHas('car', fn($q) => $q->where('car_plate', 'like', '%' . $request->search . '%'));
            }

        $infractions = $query->latest()->paginate(10);

   
        $user = Auth::user();
        $user->unreadNotifications()
         ->where('type', InfraccionNotification::class)
         ->update(['read_at' => now()]);

        return view('infractions.index', compact('infractions', 'user'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role->name !== 'admin' && Auth::user()->role->name !== 'inspector') {
            abort(403);
        }
        $request->validate([
            'car_plate' => 'required|string|max:10',
            'fine' => 'nullable|integer|min:0',
            'date' => 'nullable|date',
            'status' => 'nullable|in:pending,paid,canceled',
        ]);
        $plate = $this->validateAndCleanLicensePlate($request->car_plate);
        if (!$plate['valid']) {
            return back()->withErrors(['car_plate' => 'Patente inválida'])->withInput();
        }
        $car = Car::firstOrCreate(
            ['car_plate' => $plate['cleaned']],
            ['user_id' => Auth::id()]
        );

        $infraction = Infraction::create([
            'user_id' => auth()->id(), // Inspector como user_id
            'car_id' => $car->id,
            'fine' => 5000,
            'date' => now()->format('Y-m-d'),
            'status' => 'pending',
        ]);

        // Notificar al propietario del auto si existe
        if ($car->user_id && $car->user_id != 0) {
            $user = User::find($car->user_id);
            if ($user) {
                $user->notify(new InfraccionNotification([
                    'car_plate' => $car->car_plate,
                    'date' => $infraction->date,
                    'hour' => now()->format('H:i'),
                    'ubication' => 'No especificado',
                    'infraccion_id' => $infraction->id,
                ]));
                \Log::info('Notificación enviada a usuario ID: ' . $user->id); // Depuración
            } else {
                \Log::warning('No se encontró usuario para car_id: ' . $car->id);
            }
        } else {
            \Log::warning('El auto no tiene un user_id válido: ' . $car->id);
        }

        return redirect()->route('infractions.index')->with('success', 'Infracción registrada');
    }

    public function edit(Infraction $infraction)
    {
        if (Auth::user()->role->name !== 'admin' && $infraction->user_id !== Auth::id()) {
            abort(403);
        }
        return view('infractions.edit', compact('infraction'));
    }

    public function update(Request $request, Infraction $infraction)
    {
        if (Auth::user()->role->name !== 'admin' && $infraction->user_id !== Auth::id()) {
            abort(403);
        }
        $request->validate([
            'car_plate' => 'required|string|max:10',
            'fine' => 'required|integer|min:0',
            'date' => 'required|date',
            'status' => 'required|in:pending,paid,canceled',
        ]);
        $plate = $this->validateAndCleanLicensePlate($request->car_plate);
        if (!$plate['valid']) {
            return back()->withErrors(['car_plate' => 'Patente inválida'])->withInput();
        }
        $car = Car::firstOrCreate(
            ['car_plate' => $plate['cleaned']],
            ['user_id' => Auth::id()]
        );
        $infraction->update([
            'car_id' => $car->id,
            'fine' => $request->fine,
            'date' => $request->date,
            'status' => $request->status,
        ]);
        return redirect()->route('infractions.index')->with('success', 'Infracción actualizada');
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
