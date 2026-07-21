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
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\LogisticsController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\ArticleController;

Route::get('/', function () {
    return view('welcome');
});

Route::redirect('/home', '/dashboard');

// Admin Auth Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
Route::post('/admin/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

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

    Route::get('/countries/{id}', [CountryController::class, 'show'])
        ->name('countries.show');

    Route::get('/weather', [App\Http\Controllers\WeatherController::class, 'index'])
        ->name('weather.index');

    Route::get('/api/weather/map-data', [App\Http\Controllers\WeatherController::class, 'getMapData'])
        ->name('api.weather.map-data');

    Route::get('/exchange', [ExchangeRateController::class, 'index'])
        ->name('exchange.index');

    Route::get('/economy', [EconomyController::class, 'index'])
        ->name('economy.index');

    Route::get('/risk', [RiskController::class, 'index'])
        ->name('risk.index');

    Route::get('/news', [NewsController::class, 'index'])
        ->name('news.index');

    Route::get('/ports', [PortController::class, 'index'])
        ->name('ports.index');
    
    Route::get('/api/ports/data', [PortController::class, 'getData'])
        ->name('ports.data');

    // Logistics Simulator
    Route::get('/logistics', [LogisticsController::class, 'index'])
        ->name('logistics.index');
    Route::get('/logistics/ports/{country_id}', [LogisticsController::class, 'getPortsByCountry'])
        ->name('logistics.ports');
    Route::post('/logistics/calculate', [LogisticsController::class, 'calculate'])
        ->name('logistics.calculate');

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

    // Favorite Monitoring List
    Route::get('/favorites', [WatchlistController::class, 'index'])
        ->name('favorites.index');
    Route::post('/favorites/toggle', [WatchlistController::class, 'toggle'])
        ->name('favorites.toggle');

    // Public Articles
    Route::get('/articles', [ArticleController::class, 'index'])
        ->name('articles.index');
    Route::get('/articles/{slug}', [ArticleController::class, 'show'])
        ->name('articles.show');

    // ==========================================
    // ADMIN PORTAL ROUTES
    // ==========================================
    Route::middleware(['is_admin'])->group(function () {
        
        Route::get('/admin/sync', [SyncController::class, 'index'])
            ->name('admin.sync');
            
        Route::post('/sync/countries', [SyncController::class, 'countries'])
            ->name('sync.countries');
        Route::post('/sync/weather', [SyncController::class, 'weather'])
            ->name('sync.weather');
        Route::post('/sync/exchange-rate', [SyncController::class, 'exchangeRate'])
            ->name('sync.exchange-rate');
        Route::post('/sync/economy', [SyncController::class, 'economy'])
            ->name('sync.economy');
        Route::post('/sync/risk', [SyncController::class, 'risk'])
            ->name('sync.risk');
        Route::post('/sync/news', [SyncController::class, 'news'])
            ->name('sync.news');
        Route::post('/sync/ports', [SyncController::class, 'ports'])
            ->name('sync.ports');

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

        // User Management
        Route::get('/admin/users', [UserController::class, 'index'])
            ->name('admin.users.index');
        Route::get('/admin/users/create', [UserController::class, 'create'])
            ->name('admin.users.create');
        Route::post('/admin/users', [UserController::class, 'store'])
            ->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])
            ->name('admin.users.edit');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])
            ->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])
            ->name('admin.users.destroy');
        Route::post('/admin/users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])
            ->name('admin.users.toggle-admin');

        // Article Management
        Route::get('/admin/articles', [AdminArticleController::class, 'index'])
            ->name('admin.articles.index');
        Route::get('/admin/articles/create', [AdminArticleController::class, 'create'])
            ->name('admin.articles.create');
        Route::post('/admin/articles', [AdminArticleController::class, 'store'])
            ->name('admin.articles.store');
        Route::get('/admin/articles/{article}/edit', [AdminArticleController::class, 'edit'])
            ->name('admin.articles.edit');
        Route::put('/admin/articles/{article}', [AdminArticleController::class, 'update'])
            ->name('admin.articles.update');
        Route::delete('/admin/articles/{article}', [AdminArticleController::class, 'destroy'])
            ->name('admin.articles.destroy');
        Route::post('/admin/articles/{article}/publish', [AdminArticleController::class, 'publish'])
            ->name('admin.articles.publish');
        Route::post('/admin/articles/{article}/archive', [AdminArticleController::class, 'archive'])
            ->name('admin.articles.archive');
            
    });
});

require __DIR__.'/auth.php';