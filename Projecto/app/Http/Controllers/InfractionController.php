<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Infraction;
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
        return view('infractions.index', compact('infractions'));
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
            'user_id' => Auth::id(),
            'car_id' => $car->id,
            'fine' => $request->input('fine', 5000),
            'date' => $request->input('date', now()->format('Y-m-d')),
            'status' => $request->input('status', 'pending'),
        ]);
        return redirect()->route('infractions.index')->with('success', 'Infracción registrada');
    }

    public function edit(Infraction $infraction)
    {
        if (Auth::user()->role->name !== 'admin' && $infraction->user_id !== Auth::id()) {
            abort(403);
        }
        $cars = Car::all();
        return view('infractions.edit', compact('infraction','cars'));
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
