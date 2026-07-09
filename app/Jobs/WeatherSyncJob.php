<?php

namespace App\Jobs;

use App\Services\Sync\WeatherSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class WeatherSyncJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function handle(WeatherSyncService $weatherSyncService): void
    {
        $weatherSyncService->sync();
    }
}