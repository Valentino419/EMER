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
use App\Http\Controllers\Auth\NewPasswordController;

Route::resource('payment', PaymentController::class); // Mantiene CRUD para pagos post-sesión

//Route::get('/', function () {
//    return Inertia::render('welcome');
//})->name('home');

Route::resource('cars', CarController::class)->names([
    'create' => 'cars.create',
    'edit' => 'cars.edit',
    'update' => 'cars.update',
]);
Route::resource('infractions', InfractionController::class)->names([
    'index' => 'infractions.index',
    'create' => 'infractions.create',
    'store' => 'infractions.store',
    'edit' => 'infractions.edit',
    'update' => 'infractions.update',
    'destroy' => 'infractions.destroy',
]);

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

Route::get('/check-zone', [ZoneController::class, 'checkZone']);
Route::post('/check-zone', [ZoneController::class, 'checkZone']);

// Rutas para parking sessions (usa ParkingSessionController para create inicial)
Route::get('/parking/create', [ParkingSessionController::class, 'create'])->name('parking.create');
Route::post('/parking', [ParkingSessionController::class, 'store'])->name('parking.store'); // Crea sesión pending

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

Route::fallback(function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // NUEVA: Ruta para iniciar pago Stripe (crea sesión pending si no existe)
    Route::post('/parking/create-payment', [PaymentController::class, 'create'])->name('parking.create-payment');
    Route::post('/parking/confirm', [PaymentController::class, 'confirm'])->name('parking.confirm');
    Route::get('/parking/show', [PaymentController::class, 'show'])->name('parking.show');
    // Ruta para webhook (pública, pero verifica signature)
    Route::post('/stripe/webhook', [PaymentController::class, 'webhook']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';