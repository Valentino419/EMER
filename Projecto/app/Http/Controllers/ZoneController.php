<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zones= Zone::all();
        return view('Zone.index', compact('zones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $zones= Zone::all();
        return view('Zone.create', compact('zones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'numeration' => 'required|string|max:255',
        ]);
        
        $zone= Zone::create([
            'name'=>$request->name,
            'numeration'=>$request->numeration, 
        ]);

        return redirect()->route('zone.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $zone= Zone::findOrFail($id);
        return view('zone.edit', compact('zone'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'numeration' => 'required|max:255',
        ]);
       $zone = Zone::find($id);
       $zone->update($request->all());
       return redirect()->route('zone.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $zone= Zone::findOrFail($id);
        $zone->delete();

        return redirect()->route('zone.index');
    }
}
