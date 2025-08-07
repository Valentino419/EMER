<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\InfractionController;
use App\Http\Controllers\InspectorController;

Route::resource('inspectors', InspectorController::class);

Route::resource('infractions', InfractionController::class);

Route::resource('cars', CarController::class);

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

Route::resource('users', UserController::class)->names([
    'index' => 'user.index',
    'create' => 'user.create',
    'store' => 'user.store',
    'edit' => 'user.edit',
    'update' => 'user.update',
    'destroy' => 'user.destroy',
]);

Route::fallback(function () {
    return view('app');
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
