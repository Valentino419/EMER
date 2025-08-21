<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\InfractionController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ParkingSessionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);


Route::resource('cars', CarController::class);

Route::resource('payment', PaymentController::class);




//Route::get('/', function () {
//    return Inertia::render('welcome');
//})->name('home');


Route::resource('infractions', InfractionController::class)->names([
    'index' => 'infractions.index',
    'create' => 'infractions.create',
    'store' => 'infractions.store',
    'edit' => 'infractions.edit',
    'update' => 'infractions.update',
    'destroy' => 'infractions.destroy',
]);;

Route::resource('inspectors', InspectorController::class)->names([
    'index' => 'inspectors.index',
    'create' => 'inspectors.create',
    'store' => 'inspectors.store',
    'edit' => 'inspectors.edit',
    'update' => 'inspectors.update',
    'destroy' => 'inspectors.destroy',
]);

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
