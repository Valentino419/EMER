<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\InfractionController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParkingSessionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;;

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

// 1. Mostrar formulario "olvidé mi contraseña"
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

// 2. Enviar email con link de reseteo
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

// 3. Mostrar formulario para crear nueva contraseña (el link del email apunta acá)
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');

// 4. Guardar nueva contraseña en la BD
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');


Route::fallback(function () {
    return view('auth.login');
});




require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
