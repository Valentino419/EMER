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
    ->middleware(['auth'])
    ->name('dashboard');

// Schedule (admin only)
Route::get('/schedule', [ScheduleController::class, 'index'])
    ->middleware(['auth'])
    ->name('schedule.index');
Route::get('/schedule/create', [ScheduleController::class, 'create'])
    ->middleware(['auth'])
    ->name('schedule.create');
Route::post('/schedule', [ScheduleController::class, 'store'])
    ->middleware(['auth'])
    ->name('schedule.store');
Route::get('/schedule/{schedule}', [ScheduleController::class, 'show'])
    ->middleware(['auth'])
    ->name('schedule.show');
Route::get('/schedule/{schedule}/edit', [ScheduleController::class, 'edit'])
    ->middleware(['auth'])
    ->name('schedule.edit');
Route::put('/schedule/{schedule}', [ScheduleController::class, 'update'])
    ->middleware(['auth'])
    ->name('schedule.update');
Route::delete('/schedule/{schedule}', [ScheduleController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('schedule.destroy');

// Street (admin only)
Route::get('/street', [StreetController::class, 'index'])
    ->middleware(['auth'])
    ->name('street.index');
Route::get('/street/create', [StreetController::class, 'create'])
    ->middleware(['auth'])
    ->name('street.create');
Route::post('/street', [StreetController::class, 'store'])
    ->middleware(['auth'])
    ->name('street.store');
Route::get('/street/{street}', [StreetController::class, 'show'])
    ->middleware(['auth'])
    ->name('street.show');
Route::get('/street/{street}/edit', [StreetController::class, 'edit'])
    ->middleware(['auth'])
    ->name('street.edit');
Route::put('/street/{street}', [StreetController::class, 'update'])
    ->middleware(['auth'])
    ->name('street.update');
Route::delete('/street/{street}', [StreetController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('street.destroy');

// Zones
Route::get('/zones', [ZoneController::class, 'index'])
    ->middleware(['auth'])
    ->name('zones.index');
Route::get('/zones/create', [ZoneController::class, 'create'])
    ->middleware(['auth'])
    ->name('zones.create');
Route::post('/zones', [ZoneController::class, 'store'])
    ->middleware(['auth'])
    ->name('zones.store');
Route::get('/zones/{zone}', [ZoneController::class, 'show'])
    ->middleware(['auth'])
    ->name('zones.show');
Route::get('/zones/{zone}/edit', [ZoneController::class, 'edit'])
    ->middleware(['auth'])
    ->name('zones.edit');
Route::put('/zones/{zone}', [ZoneController::class, 'update'])
    ->middleware(['auth'])
    ->name('zones.update');
Route::delete('/zones/{zone}', [ZoneController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('zones.destroy');

// Users
Route::get('/users', [UserController::class, 'index'])
    ->middleware(['auth'])
    ->name('user.index');
Route::get('/users/create', [UserController::class, 'create'])
    ->middleware(['auth'])
    ->name('user.create');
Route::post('/users', [UserController::class, 'store'])
    ->middleware(['auth'])
    ->name('user.store');
Route::get('/users/{user}', [UserController::class, 'show'])
    ->middleware(['auth'])
    ->name('user.show');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])
    ->middleware(['auth'])
    ->name('user.edit');
Route::put('/users/{user}', [UserController::class, 'update'])
    ->middleware(['auth'])
    ->name('user.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('user.destroy');

// Cars
Route::get('/cars', [CarController::class, 'index'])
    ->middleware(['auth'])
    ->name('cars.index');
Route::get('/cars/create', [CarController::class, 'create'])
    ->middleware(['auth'])
    ->name('cars.create');
Route::post('/cars', [CarController::class, 'store'])
    ->middleware(['auth'])
    ->name('cars.store');
Route::get('/cars/{car}', [CarController::class, 'show'])
    ->middleware(['auth'])
    ->name('cars.show');
Route::get('/cars/{car}/edit', [CarController::class, 'edit'])
    ->middleware(['auth'])
    ->name('cars.edit');
Route::put('/cars/{car}', [CarController::class, 'update'])
    ->middleware(['auth'])
    ->name('cars.update');
Route::delete('/cars/{car}', [CarController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('cars.destroy');

// Infractions
Route::get('/infractions', [InfractionController::class, 'index'])
    ->middleware(['auth'])
    ->name('infractions.index');
Route::get('/infractions/create', [InfractionController::class, 'create'])
    ->middleware(['auth'])
    ->name('infractions.create');
Route::post('/infractions', [InfractionController::class, 'store'])
    ->middleware(['auth'])
    ->name('infractions.store');
Route::get('/infractions/{infraction}', [InfractionController::class, 'show'])
    ->middleware(['auth'])
    ->name('infractions.show');
Route::get('/infractions/{infraction}/edit', [InfractionController::class, 'edit'])
    ->middleware(['auth'])
    ->name('infractions.edit');
Route::put('/infractions/{infraction}', [InfractionController::class, 'update'])
    ->middleware(['auth'])
    ->name('infractions.update');
Route::delete('/infractions/{infraction}', [InfractionController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('infractions.destroy');
git 
// Inspectors (admin only)
Route::get('/inspectors', [InspectorController::class, 'index'])
    ->middleware(['auth'])
    ->name('inspectors.index');
Route::get('/inspectors/create', [InspectorController::class, 'create'])
    ->middleware(['auth'])
    ->name('inspectors.create');
Route::post('/inspectors', [InspectorController::class, 'store'])
    ->middleware(['auth'])
    ->name('inspectors.store');
Route::get('/inspectors/{inspector}', [InspectorController::class, 'show'])
    ->middleware(['auth'])
    ->name('inspectors.show');
Route::get('/inspectors/{inspector}/edit', [InspectorController::class, 'edit'])
    ->middleware(['auth'])
    ->name('inspectors.edit');
Route::put('/inspectors/{inspector}', [InspectorController::class, 'update'])
    ->middleware(['auth'])
    ->name('inspectors.update');
Route::delete('/inspectors/{inspector}', [InspectorController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('inspectors.destroy');

// Custom user routes
Route::get('/user/logged', [UserController::class, 'logged'])
    ->middleware(['auth'])
    ->name('user.logged');

// Check zone (GET + POST)
Route::match(['get', 'post'], '/check-zone', [ZoneController::class, 'checkZone'])
    ->middleware(['auth']);

// Parking sessions
Route::get('/parking', [ParkingSessionController::class, 'index'])
    ->middleware(['auth'])
    ->name('parking.index');
Route::get('/parking/create', [ParkingSessionController::class, 'create'])
    ->middleware(['auth'])
    ->name('parking.create');
Route::post('/parking/{id}/end', [ParkingSessionController::class, 'end'])
    ->middleware(['auth'])
    ->name('parking.end');
Route::post('/parking', [ParkingSessionController::class, 'store'])
    ->middleware(['auth'])
    ->name('parking.store');
Route::get('/parking/{parkingSession?}', [ParkingSessionController::class, 'show'])
    ->middleware(['auth'])
    ->name('parking.show');
Route::post('/parking/{session}/extend', [ParkingSessionController::class, 'extend'])
    ->name('parking.extend');
Route::get('/parking/zones/{zoneId}/rate', [ParkingSessionController::class, 'getZoneRate']);
Route::delete('/parking/{parkingSession}', [ParkingSessionController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('parking.destroy');

Route::put('/parking/{parkingSession}', [ParkingSessionController::class, 'update'])
    ->middleware(['auth'])
    ->name('parking.update');
// API parking check
Route::get('/api/parking/check-active/{carId}', [ParkingSessionController::class, 'checkActive'])
    ->middleware(['auth'])
    ->name('parking.check-active');
Route::get('/parking/streets/{zoneId}', [ParkingSessionController::class, 'getStreetsByZone']);

// Payments
Route::post('/payment/initiate', [PaymentController::class, 'initiate'])
    ->middleware(['auth'])
    ->name('payment.initiate');
Route::get('/payment/success', [PaymentController::class, 'success'])
    ->middleware(['auth'])
    ->name('payment.success');
Route::get('/payment/failure', [PaymentController::class, 'failure'])
    ->middleware(['auth'])
    ->name('payment.failure');
Route::get('/payment/pending', [PaymentController::class, 'pending'])
    ->middleware(['auth'])
    ->name('payment.pending');
Route::post('/payment/confirm', [PaymentController::class, 'confirm'])
    ->middleware(['auth'])
    ->name('payment.confirm');

// Mercadopago webhook (no auth, no CSRF)
Route::post('/mercadopago/webhook', [PaymentController::class, 'webhook'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class])
    ->name('mercadopago.webhook');

// Notifications
Route::get('/notifications', [NotificationController::class, 'userNotifications'])
    ->middleware(['auth'])
    ->name('notifications.user');
Route::get('/admin/notifications', [NotificationController::class, 'index'])
    ->middleware(['auth'])
    ->name('notifications.index');
Route::post('/admin/notifications', [NotificationController::class, 'store'])
    ->middleware(['auth'])
    ->name('notifications.store');
Route::post('/admin/notifications/{infraccionId}/send', [NotificationController::class, 'sendInfraccion'])
    ->middleware(['auth'])
    ->name('notifications.send');
Route::post('/admin/notifications/user/{userId}/send', [NotificationController::class, 'sendUserInfracciones'])
    ->middleware(['auth'])
    ->name('notifications.sendUser');

// Zone rate (inline closure)
Route::get('/zones/{zone}/rate', function (Zone $zone) {
    return Zone::where('id', $zone->id)->get(['rate']);
})->middleware(['auth']);

// Fallback
Route::fallback(function () {
    return redirect()->route('login');
});

// Include other route files
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
