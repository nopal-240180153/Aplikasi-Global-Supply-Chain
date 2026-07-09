<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\SyncController;

Route::redirect('/', '/dashboard');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
    
    Route::get('/countries', [CountryController::class,'index'])
    ->name('countries.index');

    Route::get('/weather', [App\Http\Controllers\WeatherController::class, 'index'])
    ->name('weather.index');

    Route::post('/sync/countries', [SyncController::class, 'countries'])
    ->name('sync.countries');

    Route::get('/admin/sync', [SyncController::class, 'index'])
    ->name('admin.sync');

    Route::get('/weather', [App\Http\Controllers\WeatherController::class, 'index'])
    ->name('weather.index');

    Route::post('/sync/weather', [SyncController::class, 'weather'])
    ->name('sync.weather');
});

require __DIR__.'/auth.php';