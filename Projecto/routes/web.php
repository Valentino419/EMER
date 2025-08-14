<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\InfractionController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ParkingSessionController;
use App\Http\Controllers\NotificationController;



Route::get('notifications/{notification}/delete', [NotificationController::class, 'delete'])->name('notifications.delete');
Route::resource('notifications', NotificationController::class)->except(['edit', 'update', 'show']);


Route::resource('inspectors', InspectorController::class);

Route::resource('infractions', InfractionController::class);

Route::resource('cars', CarController::class);

// Rutas protegidas por autenticación y roles
// Route::middleware(['auth'])->group(function () {
//     // Rutas para el recurso Payment (solo inspectores y admins)
//     Route::middleware(['role:inspector,admin'])->group(function () {
//     });
// });
Route::resource('payment', PaymentController::class)->only([
        'index', 'store', 'show', 'edit', 'update', 'destroy'
]);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');


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

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
