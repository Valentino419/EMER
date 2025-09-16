<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Zone;
use App\Models\Street;

Route::get('/zones/{zone}/streets', function (Zone $zone) {
    return Street::where('zone_id', $zone->id)->get(['id', 'name', 'zone_id']);
});

Route::get('/zones/{zone}/rate', function (Zone $zone) {
    return response()->json(['rate' => $zone->rate]);
});
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
