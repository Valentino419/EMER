<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ← Esto es importante

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role();
        return view('dashboard-admin', compact('role'));
    }
}
