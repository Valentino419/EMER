<?php

namespace App\Http\Controllers;

use App\Models\Infraction;
use App\Models\Inspector;
use App\Models\Car;
use Illuminate\Http\Request;

class InfractionController extends Controller
{
    public function index()
    {
        $infractions = Infraction::with(['inspector', 'car'])->get();
        return view('infractions.index', compact('infractions'));
    }

    public function create()
    {
        $inspectors = Inspector::all();
        $cars = Car::all();
        return view('infractions.create', compact('inspectors', 'cars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inspector_id' => 'required|exists:inspectors,id',
            'car_id' => 'required|exists:cars,id',
            'fine' => 'required|integer|min:0',
            'date' => 'required|date',
            'status' => 'required|string|max:255',
        ]);

        Infraction::create($request->all());

        return redirect()->route('infractions.index')->with('success', 'Infracción registrada correctamente.');
    }

    public function edit(Infraction $infraction)
    {
        $inspectors = Inspector::all();
        $cars = Car::all();
        return view('infractions.edit', compact('infraction', 'inspectors', 'cars'));
    }

    public function update(Request $request, Infraction $infraction)
    {
        $request->validate([
            'inspector_id' => 'required|exists:inspectors,id',
            'car_id' => 'required|exists:cars,id',
            'fine' => 'required|integer|min:0',
            'date' => 'required|date',
            'status' => 'required|string|max:255',
        ]);

        $infraction->update($request->all());

        return redirect()->route('infractions.index')->with('success', 'Infracción actualizada correctamente.');
    }

    public function destroy(Infraction $infraction)
    {
        $infraction->delete();

        return redirect()->route('infractions.index')->with('success', 'Infracción eliminada correctamente.');
    }
}
