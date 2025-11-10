<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InfractionController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ParkingSessionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StreetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use App\Models\Zone;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────────────────────
//  PUBLIC / DEBUG
// ──────────────────────────────────────────────────────────────
Route::get('/test-mp', fn () => response()->json([
    'MERCADOPAGO_ACCESS_TOKEN' => env('MERCADOPAGO_ACCESS_TOKEN') ? 'OK' : 'FALTA',
    'MERCADOPAGO_PUBLIC_KEY' => env('MERCADOPAGO_PUBLIC_KEY') ? 'OK' : 'FALTA',
    'APP_KEY' => app('config')->get('app.key') ? 'OK' : 'FALTA',
    'APP_ENV' => app()->environment(),
    'TIME' => now()->format('H:i:s'),
]));

// ──────────────────────────────────────────────────────────────
//  AUTH (login / register / password)
// ──────────────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// Home → login
Route::get('/', fn () => redirect()->route('login'));

// ──────────────────────────────────────────────────────────────
//  WEBHOOKS (no CSRF)
// ──────────────────────────────────────────────────────────────
Route::post('/webhook/mercadopago', [PaymentController::class, 'webhook'])
    ->name('webhook.mercadopago');

Route::post('/mercadopago/webhook', [PaymentController::class, 'webhook'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class])
    ->name('mercadopago.webhook');

// ──────────────────────────────────────────────────────────────
//  AUTHENTICATED ROUTES (solo auth + verified, SIN ROLES)
// ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ────── Parking ──────
    Route::get('/parking/create', [ParkingSessionController::class, 'create'])->name('parking.create');
    Route::post('/parking', [ParkingSessionController::class, 'store'])->name('parking.store');
    Route::post('/parking/{id}/end', [ParkingSessionController::class, 'end'])->name('parking.end');
    Route::get('/parking/{parkingSession?}', [ParkingSessionController::class, 'show'])->name('parking.show');
    Route::post('/parking/{session}/extend', [ParkingSessionController::class, 'extend'])->name('parking.extend');
    
    // API para verificar estacionamiento activo
    Route::get('/api/parking/check-active/{carId}', [ParkingSessionController::class, 'checkActive'])
        ->name('parking.check-active');

    // ────── Pagos ──────
    Route::get('/payment/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failure', [PaymentController::class, 'failure'])->name('payment.failure');
    Route::get('/payment/pending', [PaymentController::class, 'pending'])->name('payment.pending');
    Route::post('/payment/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');
    
    // ────── Zonas y Calles ──────
    Route::match(['get', 'post'], '/check-zone', [ZoneController::class, 'checkZone']);
    Route::get('/zones/{zone}/rate', fn (Zone $zone) => $zone->only('rate'));

    // ────── Recursos Completos (disponibles para todos los autenticados) ──────
    Route::resource('parking',ParkingSessionController::class);
    Route::resource('schedule', ScheduleController::class);
    Route::resource('street', StreetController::class);
    Route::resource('zones', ZoneController::class);
    Route::resource('cars', CarController::class)->names([
        'create' => 'cars.create',
        'edit'   => 'cars.edit',
        'update' => 'cars.update',
        
    ]);

    Route::resource('infractions', InfractionController::class)->names([
        'index'   => 'infractions.index',
        'create'  => 'infractions.create',
        'store'   => 'infractions.store',
        'edit'    => 'infractions.edit',
        'update'  => 'infractions.update',
        'destroy' => 'infractions.destroy',
    ]);

    Route::resource('inspectors', InspectorController::class)->names([
        'index'   => 'inspectors.index',
        'create'  => 'inspectors.create',
        'store'   => 'inspectors.store',
        'edit'    => 'inspectors.edit',
        'update'  => 'inspectors.update',
        'destroy' => 'inspectors.destroy',
    ]);

    Route::resource('users', UserController::class)->names([
        'index'   => 'user.index',
        'create'  => 'user.create',
        'store'   => 'user.store',
        'edit'    => 'user.edit',
        'update'  => 'user.update',
        'destroy' => 'user.destroy',
        'show'=>'user.show',
    ]);

    Route::get('/user/logged', [UserController::class, 'logged'])->name('user.logged');

    // ────── Notificaciones ──────
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

// ──────────────────────────────────────────────────────────────
//  VERIFICACIÓN DE EMAIL
// ──────────────────────────────────────────────────────────────
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// ──────────────────────────────────────────────────────────────
//  SETTINGS & EXTRAS
// ──────────────────────────────────────────────────────────────
require __DIR__.'/settings.php';
