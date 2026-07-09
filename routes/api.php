<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CountryApiController;

Route::prefix('countries')->group(function () {

    Route::get('/', [CountryApiController::class, 'index']);

    Route::get('/{id}', [CountryApiController::class, 'show']);

});