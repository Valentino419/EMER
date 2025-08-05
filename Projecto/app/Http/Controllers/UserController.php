<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users= User::all();
        return view('User.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users= User::all();
        return view('User.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user= User::create([
            'name'=>$request->type->name,
            'surname'=>$request->type->surname,
            'dni'=>$request->type->dni,
            'dni'=>$request->type->dni,
            'email'=>$request->type->email,
            'password'=>$request->type->password,
        ]);

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user= User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'dni' => 'required',
            'email' => 'required|max:255',
            
        ]);

       $user = User::find($id);
       $user->update($request->all());
       return redirect()->route('user.index');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user= User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index');
    }
}
