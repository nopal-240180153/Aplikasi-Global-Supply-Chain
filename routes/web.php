<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\EconomyController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\NewsController;
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

    Route::get('/exchange', [ExchangeRateController::class, 'index'])
    ->name('exchange.index');

    Route::post('/sync/exchange-rate', [SyncController::class, 'exchangeRate'])
    ->name('sync.exchange-rate');

    Route::post('/sync/economy', [SyncController::class, 'economy'])
    ->name('sync.economy');

    Route::get('/economy', [EconomyController::class, 'index'])
    ->name('economy.index');

    Route::get('/risk', [RiskController::class, 'index'])
    ->name('risk.index');

    Route::post('/sync/risk', [SyncController::class, 'risk'])
    ->name('sync.risk');

    Route::get('/news', [NewsController::class, 'index'])
    ->name('news.index');
    Route::post('/sync/news', [SyncController::class, 'news'])
    ->name('sync.news');
});

require __DIR__.'/auth.php';