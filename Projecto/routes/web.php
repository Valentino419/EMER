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

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'role:admin,inspector,user'])
    ->name('dashboard');

// Schedule (admin only)
Route::get('/schedule', [ScheduleController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('schedule.index');
Route::get('/schedule/create', [ScheduleController::class, 'create'])
    ->middleware(['auth', 'role:admin'])
    ->name('schedule.create');
Route::post('/schedule', [ScheduleController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('schedule.store');
Route::get('/schedule/{schedule}', [ScheduleController::class, 'show'])
    ->middleware(['auth', 'role:admin'])
    ->name('schedule.show');
Route::get('/schedule/{schedule}/edit', [ScheduleController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('schedule.edit');
Route::put('/schedule/{schedule}', [ScheduleController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('schedule.update');
Route::delete('/schedule/{schedule}', [ScheduleController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('schedule.destroy');

// Street (admin only)
Route::get('/street', [StreetController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('street.index');
Route::get('/street/create', [StreetController::class, 'create'])
    ->middleware(['auth', 'role:admin'])
    ->name('street.create');
Route::post('/street', [StreetController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('street.store');
Route::get('/street/{street}', [StreetController::class, 'show'])
    ->middleware(['auth', 'role:admin'])
    ->name('street.show');
Route::get('/street/{street}/edit', [StreetController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('street.edit');
Route::put('/street/{street}', [StreetController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('street.update');
Route::delete('/street/{street}', [StreetController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('street.destroy');

// Zones
Route::get('/zones', [ZoneController::class, 'index'])
    ->middleware(['auth', 'role:admin,inspector,user'])
    ->name('zones.index');
Route::get('/zones/create', [ZoneController::class, 'create'])
    ->middleware(['auth', 'role:admin'])
    ->name('zones.create');
Route::post('/zones', [ZoneController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('zones.store');
Route::get('/zones/{zone}', [ZoneController::class, 'show'])
    ->middleware(['auth', 'role:admin,inspector,user'])
    ->name('zones.show');
Route::get('/zones/{zone}/edit', [ZoneController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('zones.edit');
Route::put('/zones/{zone}', [ZoneController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('zones.update');
Route::delete('/zones/{zone}', [ZoneController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('zones.destroy');

// Users
Route::get('/users', [UserController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('user.index');
Route::get('/users/create', [UserController::class, 'create'])
    ->middleware(['auth', 'role:admin'])
    ->name('user.create');
Route::post('/users', [UserController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('user.store');
Route::get('/users/{user}', [UserController::class, 'show'])
    ->middleware(['auth', 'role:admin,user'])
    ->name('user.show');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])
    ->middleware(['auth', 'role:admin,user'])
    ->name('user.edit');
Route::put('/users/{user}', [UserController::class, 'update'])
    ->middleware(['auth', 'role:admin,user'])
    ->name('user.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('user.destroy');

// Cars
Route::get('/cars', [CarController::class, 'index'])
    ->middleware(['auth', 'role:admin,inspector,user'])
    ->name('cars.index');
Route::get('/cars/create', [CarController::class, 'create'])
    ->middleware(['auth', 'role:user'])
    ->name('cars.create');
Route::post('/cars', [CarController::class, 'store'])
    ->middleware(['auth', 'role:user'])
    ->name('cars.store');
Route::get('/cars/{car}', [CarController::class, 'show'])
    ->middleware(['auth', 'role:admin,inspector,user'])
    ->name('cars.show');
Route::get('/cars/{car}/edit', [CarController::class, 'edit'])
    ->middleware(['auth', 'role:user'])
    ->name('cars.edit');
Route::put('/cars/{car}', [CarController::class, 'update'])
    ->middleware(['auth', 'role:user'])
    ->name('cars.update');
Route::delete('/cars/{car}', [CarController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('cars.destroy');

// Infractions
Route::get('/infractions', [InfractionController::class, 'index'])
    ->middleware(['auth', 'role:admin,inspector,user'])
    ->name('infractions.index');
Route::get('/infractions/create', [InfractionController::class, 'create'])
    ->middleware(['auth', 'role:inspector'])
    ->name('infractions.create');
Route::post('/infractions', [InfractionController::class, 'store'])
    ->middleware(['auth', 'role:inspector'])
    ->name('infractions.store');
Route::get('/infractions/{infraction}', [InfractionController::class, 'show'])
    ->middleware(['auth', 'role:admin,inspector,user'])
    ->name('infractions.show');
Route::get('/infractions/{infraction}/edit', [InfractionController::class, 'edit'])
    ->middleware(['auth', 'role:admin,inspector'])
    ->name('infractions.edit');
Route::put('/infractions/{infraction}', [InfractionController::class, 'update'])
    ->middleware(['auth', 'role:admin,inspector'])
    ->name('infractions.update');
Route::delete('/infractions/{infraction}', [InfractionController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('infractions.destroy');

// Inspectors (admin only)
Route::get('/inspectors', [InspectorController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('inspectors.index');
Route::get('/inspectors/create', [InspectorController::class, 'create'])
    ->middleware(['auth', 'role:admin'])
    ->name('inspectors.create');
Route::post('/inspectors', [InspectorController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('inspectors.store');
Route::get('/inspectors/{inspector}', [InspectorController::class, 'show'])
    ->middleware(['auth', 'role:admin'])
    ->name('inspectors.show');
Route::get('/inspectors/{inspector}/edit', [InspectorController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('inspectors.edit');
Route::put('/inspectors/{inspector}', [InspectorController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('inspectors.update');
Route::delete('/inspectors/{inspector}', [InspectorController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('inspectors.destroy');

// Custom user routes
Route::get('/user/logged', [UserController::class, 'logged'])
    ->middleware(['auth', 'role:admin,inspector,user'])
    ->name('user.logged');

// Check zone (GET + POST)
Route::match(['get', 'post'], '/check-zone', [ZoneController::class, 'checkZone'])
    ->middleware(['auth', 'role:admin,inspector,user']);

// Parking sessions
Route::get('/parking/create', [ParkingSessionController::class, 'create'])
    ->middleware(['auth', 'role:user'])
    ->name('parking.create');
Route::post('/parking/{id}/end', [ParkingSessionController::class, 'end'])
    ->middleware(['auth', 'role:user'])
    ->name('parking.end');
Route::post('/parking', [ParkingSessionController::class, 'store'])
    ->middleware(['auth', 'role:user'])
    ->name('parking.store');
Route::get('/parking/{parkingSession?}', [ParkingSessionController::class, 'show'])
    ->middleware(['auth', 'role:admin,inspector,user'])
    ->name('parking.show');

// API parking check
Route::get('/api/parking/check-active/{carId}', [ParkingSessionController::class, 'checkActive'])
    ->middleware(['auth', 'role:admin,inspector'])
    ->name('parking.check-active');

// Payments
Route::post('/payment/initiate', [PaymentController::class, 'initiate'])
    ->middleware(['auth', 'role:user'])
    ->name('payment.initiate');
Route::get('/payment/success', [PaymentController::class, 'success'])
    ->middleware(['auth', 'role:user'])
    ->name('payment.success');
Route::get('/payment/failure', [PaymentController::class, 'failure'])
    ->middleware(['auth', 'role:user'])
    ->name('payment.failure');
Route::get('/payment/pending', [PaymentController::class, 'pending'])
    ->middleware(['auth', 'role:user'])
    ->name('payment.pending');
Route::post('/payment/confirm', [PaymentController::class, 'confirm'])
    ->middleware(['auth', 'role:user'])
    ->name('payment.confirm');

// Mercadopago webhook (no auth, no CSRF)
Route::post('/mercadopago/webhook', [PaymentController::class, 'webhook'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class])
    ->name('mercadopago.webhook');

// Notifications
Route::get('/notifications', [NotificationController::class, 'userNotifications'])
    ->middleware(['auth', 'role:user'])
    ->name('notifications.user');
Route::get('/admin/notifications', [NotificationController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('notifications.index');
Route::post('/admin/notifications', [NotificationController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('notifications.store');
Route::post('/admin/notifications/{infraccionId}/send', [NotificationController::class, 'sendInfraccion'])
    ->middleware(['auth', 'role:admin,inspector'])
    ->name('notifications.send');
Route::post('/admin/notifications/user/{userId}/send', [NotificationController::class, 'sendUserInfracciones'])
    ->middleware(['auth', 'role:admin,inspector'])
    ->name('notifications.sendUser');

// Zone rate (inline closure)
Route::get('/zones/{zone}/rate', function (Zone $zone) {
    return Zone::where('id', $zone->id)->get(['rate']);
})->middleware(['auth', 'role:admin,inspector,user']);

// Fallback
Route::fallback(function () {
    return redirect()->route('login');
});

// Include other route files
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';