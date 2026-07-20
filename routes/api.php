<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CountryApiController;
use App\Http\Controllers\Api\RiskApiController;
use App\Http\Controllers\Api\PortApiController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\ExchangeRateApiController;

// API Version 1
Route::prefix('v1')->group(function () {

    // Countries API
    Route::prefix('countries')->group(function () {
        Route::get('/', [CountryApiController::class, 'index']);
        Route::get('/{id}', [CountryApiController::class, 'show']);
    });

    // Risk Scores API
    Route::prefix('risk')->group(function () {
        Route::get('/', [RiskApiController::class, 'index']);
        Route::get('/{id}', [RiskApiController::class, 'show']);
    });

    // Ports API
    Route::prefix('ports')->group(function () {
        Route::get('/', [PortApiController::class, 'index']);
        Route::get('/{id}', [PortApiController::class, 'show']);
    });

    // News API
    Route::prefix('news')->group(function () {
        Route::get('/', [NewsApiController::class, 'index']);
        Route::get('/{id}', [NewsApiController::class, 'show']);
    });

    // Exchange Rates / Currency API
    Route::prefix('currency')->group(function () {
        Route::get('/', [ExchangeRateApiController::class, 'index']);
        Route::get('/latest', [ExchangeRateApiController::class, 'latest']);
        Route::get('/{id}', [ExchangeRateApiController::class, 'show']);
    });

});

// Legacy routes (without version prefix) - untuk backward compatibility
Route::prefix('countries')->group(function () {
    Route::get('/', [CountryApiController::class, 'index']);
    Route::get('/{id}', [CountryApiController::class, 'show']);
});

Route::get('/risk', [RiskApiController::class, 'index']);
Route::get('/ports', [PortApiController::class, 'index']);
Route::get('/news', [NewsApiController::class, 'index']);
Route::get('/currency', [ExchangeRateApiController::class, 'index']);
