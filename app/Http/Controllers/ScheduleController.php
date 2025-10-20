<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Zone;
use Illuminate\Http\Request;


class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $schedules = Schedule::with('zone')->get();
        $is_active = false;
        $active_schedules = [];

        // Si se pasa un zone_id, verificar horarios activos
        if ($request->has('zone_id')) {
            $validated = $request->validate([
                'zone_id' => 'required|exists:zones,id',
                'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
                'current_hour' => 'required|integer|between:0,23',
            ]);

        $active_schedules = Schedule::where('zone_id', $validated['zone_id'])
                ->where('day_of_week', $validated['day_of_week'])
                ->where('start_hour', '<=', $validated['current_hour'])
                ->where('end_hour', '>=', $validated['current_hour'])
                ->get();

            $is_active = $active_schedules->isNotEmpty();
        }

        return view('schedule.index', compact('schedules', 'is_active', 'active_schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $zones = Zone::all();
        return view('schedules.create', compact('zones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'day_of_week' => 'required|exists:day_of_week,id',
            'start_hour'=> 'required|exists:start_hour,id',
            'end_hour'=> 'required|exists:end_hour,id',
        ]);

        Schedule::create([
            'zone_id' =>  $validated['zone_id'],
            'day_of_week' =>  $validated['day_of_week'],
            'start_hour' =>  $validated['start_hour'],
            'end_hour' =>  $validated['end_hour'],
        ]);
        return redirect()->route('schedule.index')->with('success', 'Horario creado con éxito');
    }


    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        $schedule->load('zone');
        return view('schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $zones = Zone::all();
        return view('schedules.edit', compact('schedule', 'zones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'zone_id' => 'exists:zones,id',
            'day_of_week' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_hour' => 'integer|between:0,23',
            'end_hour' => 'integer|between:0,23|gte:start_hour',
        ]);

        $schedule->update($validated);

        return redirect()->route('schedule.index')->with('success', 'Horario actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedule.index')->with('success', 'Horario eliminado con éxito');
    }

    public function checkActiveSchedule(Request $request)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'current_hour' => 'required|integer|between:0,23',
        ]);

        $schedules = Schedule::where('zone_id', $validated['zone_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where('start_hour', '<=', $validated['current_hour'])
            ->where('end_hour', '>=', $validated['current_hour'])
            ->get();

        return view('schedules.check', [
            'is_active' => $schedules->isNotEmpty(),
            'schedules' => $schedules,
            'zone_id' => $validated['zone_id'],
        ]);
    }
}
