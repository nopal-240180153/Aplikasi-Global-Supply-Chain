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
use App\Http\Controllers\LexiconController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\VisualizationController;
use App\Http\Controllers\ComparisonController;

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

    // Port Sync
    Route::post('/sync/ports', [SyncController::class, 'ports'])
    ->name('sync.ports');

    // Port Location Dashboard
    Route::get('/ports', [PortController::class, 'index'])
    ->name('ports.index');
    
    Route::get('/api/ports/data', [PortController::class, 'getData'])
    ->name('ports.data');

    // Lexicon Management (Sentiment Analysis)
    Route::get('/admin/lexicon', [LexiconController::class, 'index'])
    ->name('admin.lexicon');
    
    Route::post('/admin/lexicon/positive', [LexiconController::class, 'storePositive'])
    ->name('admin.lexicon.positive.store');
    
    Route::delete('/admin/lexicon/positive/{positiveWord}', [LexiconController::class, 'destroyPositive'])
    ->name('admin.lexicon.positive.destroy');
    
    Route::post('/admin/lexicon/negative', [LexiconController::class, 'storeNegative'])
    ->name('admin.lexicon.negative.store');
    
    Route::delete('/admin/lexicon/negative/{negativeWord}', [LexiconController::class, 'destroyNegative'])
    ->name('admin.lexicon.negative.destroy');

    // Data Visualization Dashboard
    Route::get('/visualizations', [VisualizationController::class, 'index'])
    ->name('visualizations.index');
    
    // API endpoints untuk chart data
    Route::prefix('api/visualizations')->group(function () {
        Route::get('/gdp', [VisualizationController::class, 'getGdpData'])
        ->name('api.visualizations.gdp');
        
        Route::get('/inflation', [VisualizationController::class, 'getInflationData'])
        ->name('api.visualizations.inflation');
        
        Route::get('/exchange-rates', [VisualizationController::class, 'getExchangeRateData'])
        ->name('api.visualizations.exchange-rates');
        
        Route::get('/risk-scores', [VisualizationController::class, 'getRiskScoreData'])
        ->name('api.visualizations.risk-scores');
        
        // New endpoints
        Route::get('/risk-distribution', [VisualizationController::class, 'getRiskDistribution'])
        ->name('api.visualizations.risk-distribution');
        
        Route::get('/top-risk-countries', [VisualizationController::class, 'getTopRiskCountries'])
        ->name('api.visualizations.top-risk-countries');
        
        Route::get('/weather', [VisualizationController::class, 'getWeatherData'])
        ->name('api.visualizations.weather');
        
        Route::get('/economy', [VisualizationController::class, 'getEconomyData'])
        ->name('api.visualizations.economy');
        
        Route::get('/news-distribution', [VisualizationController::class, 'getNewsDistribution'])
        ->name('api.visualizations.news-distribution');
        
        Route::get('/risk-composition', [VisualizationController::class, 'getRiskComposition'])
        ->name('api.visualizations.risk-composition');
        
        Route::get('/continent-distribution', [VisualizationController::class, 'getContinentDistribution'])
        ->name('api.visualizations.continent-distribution');
        
        Route::get('/ports', [VisualizationController::class, 'getPortData'])
        ->name('api.visualizations.ports');
        
        Route::get('/summary-stats', [VisualizationController::class, 'getSummaryStats'])
        ->name('api.visualizations.summary-stats');
        
        Route::get('/map-data', [VisualizationController::class, 'getMapData'])
        ->name('api.visualizations.map-data');
        
        Route::get('/summary-table', [VisualizationController::class, 'getSummaryTable'])
        ->name('api.visualizations.summary-table');
        
        Route::get('/trend-data', [VisualizationController::class, 'getTrendData'])
        ->name('api.visualizations.trend-data');
        
        Route::get('/trend-summary', [VisualizationController::class, 'getTrendSummaryTable'])
        ->name('api.visualizations.trend-summary');
    });

    // Country Comparison Engine
    Route::get('/comparison', [ComparisonController::class, 'index'])
        ->name('comparison.index');
    Route::get('/api/comparison', [ComparisonController::class, 'compare'])
        ->name('api.comparison');
});

require __DIR__.'/auth.php';