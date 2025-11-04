<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CarController, DashboardController, InfractionController,
    InspectorController, NotificationController, ParkingSessionController,
    PaymentController, ScheduleController, StreetController,
    UserController, ZoneController
};
use App\Models\Zone;

// ──────────────────────────────────────────────────────────────
//  PUBLIC / DEBUG
// ──────────────────────────────────────────────────────────────
Route::get('/test-mp', fn () => response()->json([
    'MERCADOPAGO_ACCESS_TOKEN' => env('MERCADOPAGO_ACCESS_TOKEN') ? 'OK' : 'FALTA',
    'MERCADOPAGO_PUBLIC_KEY'    => env('MERCADOPAGO_PUBLIC_KEY') ? 'OK' : 'FALTA',
    'APP_KEY'                   => app('config')->get('app.key') ? 'OK' : 'FALTA',
    'APP_ENV'                   => app()->environment(),
    'TIME'                      => now()->format('H:i:s'),
]));

// ──────────────────────────────────────────────────────────────
//  AUTH (login / register / password)
// ──────────────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// home → login
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
//  AUTHENTICATED + ROLE-BASED GROUPS
// ──────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // ────── COMMON (all logged-in users) ──────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Parking
    Route::get('/parking/create', [ParkingSessionController::class, 'create'])->name('parking.create');
    Route::post('/parking', [ParkingSessionController::class, 'store'])->name('parking.store');
    Route::post('/parking/{id}/end', [ParkingSessionController::class, 'end'])->name('parking.end');
    Route::get('/parking/{parkingSession?}', [ParkingSessionController::class, 'show'])->name('parking.show');
    Route::post('/parking/{session}/extend', [ParkingSessionController::class, 'extend'])->name('parking.extend');

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

    // ────── USER (role:user) ──────
    Route::middleware('role:user')->group(function () {

        // Parking
        Route::get('/parking/create', [ParkingSessionController::class, 'create'])
            ->name('parking.create');
        Route::post('/parking', [ParkingSessionController::class, 'store'])
            ->name('parking.store');
        Route::post('/parking/{id}/end', [ParkingSessionController::class, 'end'])
            ->name('parking.end');
        Route::get('/parking/{parkingSession?}', [ParkingSessionController::class, 'show'])
            ->name('parking.show');

        // Payments (user side)
        Route::get('/payment/initiate', [PaymentController::class, 'initiate'])
            ->name('payment.initiate');
        Route::get('/payment/success', [PaymentController::class, 'success'])
            ->name('payment.success');
        Route::get('/payment/failure', [PaymentController::class, 'failure'])
            ->name('payment.failure');
        Route::get('/payment/pending', [PaymentController::class, 'pending'])
            ->name('payment.pending');
        Route::post('/payment/confirm', [PaymentController::class, 'confirm'])
            ->name('payment.confirm');

        // Zone helpers
        Route::match(['get','post'], '/check-zone', [ZoneController::class, 'checkZone']);
        Route::get('/zones/{zone}/rate', fn (Zone $zone) => $zone->only('rate'));

        // Cars – **only index / show / create / store** (no edit / delete)
        Route::resource('cars', CarController::class)
            ->only(['index', 'show', 'create', 'store'])
            ->names([
                'create' => 'cars.create',
            ]);

        // User notifications
        Route::get('/notifications', [NotificationController::class, 'userNotifications'])
            ->name('notifications.user');
    });

    // ────── INSPECTOR (role:inspector) ──────
    Route::middleware('role:inspector')->group(function () {

        // Check active parking (API used by inspectors)
        Route::get('/api/parking/check-active/{carId}', [ParkingSessionController::class, 'checkActive'])
            ->name('parking.check-active');

        // Infractions – **only create / store / index / show**
        Route::resource('infractions', InfractionController::class)
            ->only(['index', 'create', 'store', 'show'])
            ->names([
                'index'  => 'infractions.index',
                'create' => 'infractions.create',
                'store'  => 'infractions.store',
            ]);
    });

    // ────── ADMIN (role:admin) ──────
    Route::middleware('role:admin')->group(function () {

        // Full resources
        Route::resource('schedule', ScheduleController::class);
        Route::resource('street', StreetController::class);
        Route::resource('zones', ZoneController::class);
        Route::resource('zone', ZoneController::class)->names([
            'index'  => 'zone.index',
            'create' => 'zone.create',
            'edit'   => 'zone.edit',
        ]);

        // Users
        Route::resource('users', UserController::class)->names([
            'index'   => 'user.index',
            'create'  => 'user.create',
            'store'   => 'user.store',
            'edit'    => 'user.edit',
            'update'  => 'user.update',
            'destroy' => 'user.destroy',
        ]);

        // Inspectors
        Route::resource('inspectors', InspectorController::class)->names([
            'index'   => 'inspectors.index',
            'create'  => 'inspectors.create',
            'store'   => 'inspectors.store',
            'edit'    => 'inspectors.edit',
            'update'  => 'inspectors.update',
            'destroy' => 'inspectors.destroy',
        ]);

        // Infractions (full CRUD)
        Route::resource('infractions', InfractionController::class)
            ->except(['show']) // show already defined for inspectors
            ->names([
                'edit'    => 'infractions.edit',
                'update'  => 'infractions.update',
                'destroy' => 'infractions.destroy',
            ]);

        // Cars – **full CRUD for admin**
        Route::resource('cars', CarController::class)
            ->only(['edit', 'update', 'destroy'])
            ->names([
                'edit'   => 'cars.edit',
                'update' => 'cars.update',
            ]);

        // Admin notifications
        Route::get('/admin/notifications', [NotificationController::class, 'index'])
            ->name('notifications.index');
        Route::post('/admin/notifications', [NotificationController::class, 'store'])
            ->name('notifications.store');
        Route::post('/admin/notifications/{infraccionId}/send', [NotificationController::class, 'sendInfraccion'])
            ->name('notifications.send');
        Route::post('/admin/notifications/user/{userId}/send', [NotificationController::class, 'sendUserInfracciones'])
            ->name('notifications.sendUser');
    });
});

// ──────────────────────────────────────────────────────────────
//  SETTINGS (keep at the bottom)
// ──────────────────────────────────────────────────────────────
require __DIR__.'/settings.php';