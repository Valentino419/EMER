<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\InfractionController;
use App\Http\Controllers\ZoneController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ParkingSessionController;

Route::resource('infractions', InfractionController::class);
Route::resource('cars', CarController::class);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('dashboard', function () {
//         return Inertia::render('dashboard');
//     })->name('dashboard');
// });

Route::resource('users', UserController::class)->names([
    'index' => 'user.index',
    'create' => 'user.create',
    'store' => 'user.store',
    'edit' => 'user.edit',
    'update' => 'user.update',
    'destroy' => 'user.destroy',
]);

Route::resource('zones', ZoneController::class)->names([
    'index' => 'zone.index',
    'create' => 'zone.create',
    'store' => 'zone.store',
    'edit' => 'zone.edit',
    'update' => 'zone.update',
    'destroy' => 'zone.destroy',
]);

Route::get('/parking/create', [ParkingSessionController::class, 'create'])->name('parking.create');
Route::post('/parking', [ParkingSessionController::class, 'store'])->name('parking.store');


Route::fallback(function () {
    return view('app');
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
