<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\InfractionController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParkingSessionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\StreetController;


// Debug de configuración (SIEMPRE PÚBLICO)
Route::get('/test-mp', function () {
    return response()->json([
        'MERCADOPAGO_ACCESS_TOKEN' => env('MERCADOPAGO_ACCESS_TOKEN') ? 'OK (' . strlen(env('MERCADOPAGO_ACCESS_TOKEN')) . ')' : 'FALTA',
        'MERCADOPAGO_PUBLIC_KEY'    => env('MERCADOPAGO_PUBLIC_KEY') ? 'OK' : 'FALTA',
        'APP_KEY'                   => app('config')->get('app.key') ? 'OK' : 'FALTA',
        'APP_ENV'                   => app()->environment(),
        'TIME'                      => now()->format('H:i:s'),
    ]);
});

// Login / Registro / Password
require __DIR__ . '/auth.php';

// Página principal (redirecciona a login si no hay sesión)
Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Parking
    Route::get('/parking/create', [ParkingSessionController::class, 'create'])->name('parking.create');
    Route::post('/parking', [ParkingSessionController::class, 'store'])->name('parking.store');
    Route::post('/parking/{id}/end', [ParkingSessionController::class, 'end'])->name('parking.end');
    Route::get('/parking/{parkingSession?}', [ParkingSessionController::class, 'show'])->name('parking.show');

    // API para verificar estacionamiento activo
    Route::get('/api/parking/check-active/{carId}', [ParkingSessionController::class, 'checkActive'])
        ->name('parking.check-active');

    // Pago
    Route::get('/payment/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failure', [PaymentController::class, 'failure'])->name('payment.failure');
    Route::get('/payment/pending', [PaymentController::class, 'pending'])->name('payment.pending');

    // Zonas y Calles
    Route::get('/check-zone', [ZoneController::class, 'checkZone']);
    Route::post('/check-zone', [ZoneController::class, 'checkZone']);

    // Recursos
    Route::resource('schedule', ScheduleController::class);
    Route::resource('street', StreetController::class);
    Route::resource('zones', ZoneController::class);
    Route::resource('zone', ZoneController::class)->names([
        'index' => 'zone.index',
        'create' => 'zone.create',
        'edit' => 'zone.edit',
    ]);

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

    Route::get('/user/logged', [UserController::class, 'logged'])->name('user.logged');

    // Notificaciones
    Route::get('/notifications', [NotificationController::class, 'userNotifications'])
        ->name('notifications.user');
    Route::get('/admin/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/admin/notifications', [NotificationController::class, 'store'])
        ->name('notifications.store');
    Route::post('/admin/notifications/{infraccionId}/send', [NotificationController::class, 'sendInfraccion'])
        ->name('notifications.send');
    Route::post('/admin/notifications/user/{userId}/send', [NotificationController::class, 'sendUserInfracciones'])
        ->name('notifications.sendUser');
});

Route::post('/webhook/mercadopago', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('webhook.mercadopago');

//Route::fallback(function () {
//  return redirect()->route('login');
//});


require __DIR__ . '/settings.php';
