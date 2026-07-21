<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap(5);
        
        // Force HTTPS when behind ngrok or reverse proxy
        if (config('app.env') === 'production' || 
            request()->header('X-Forwarded-Proto') === 'https' ||
            str_contains(config('app.url'), 'https://')) {
            \URL::forceScheme('https');
        }
    }
}
