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

//Route::get('/streets.create', [InspectorController::class, 'index'])->name('streets.create');

//Route::get('/dashboard.inspector', [InspectorController::class, 'index'])->name('dashboard.inspector');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('payment', PaymentController::class);

Route::resource('schedule', ScheduleController::class);

Route::resource('street', StreetController::class);

Route::resource('zones', ZoneController::class);

Route::post('schedules/check-active', [ScheduleController::class, 'checkActiveSchedule']);

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

Route::get('/check-zone', [ZoneController::class, 'checkZone']);
Route::post('/check-zone', [ZoneController::class, 'checkZone']);

// Rutas para parking sessions (usa ParkingSessionController para create inicial)
Route::get('/parking/create', [ParkingSessionController::class, 'create'])->name('parking.create');
Route::post('/parking', [ParkingSessionController::class, 'store'])->name('parking.store'); // Crea sesión pending

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

// Rutas para notificaciones
Route::get('/notifications', [NotificationController::class, 'userNotifications'])->name('notifications.user')->middleware('auth');
Route::get('/admin/notifications', [NotificationController::class, 'index'])->name('notifications.index')->middleware('auth');
Route::post('/admin/notifications', [NotificationController::class, 'store'])->name('notifications.store')->middleware('auth');
Route::post('/admin/notifications/{infraccionId}/send', [NotificationController::class, 'sendInfraccion'])->name('notifications.send')->middleware('auth');
Route::post('/admin/notifications/user/{userId}/send', [NotificationController::class, 'sendUserInfracciones'])->name('notifications.sendUser')->middleware('auth');

Route::fallback(function () {
    return redirect()->route('login');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';