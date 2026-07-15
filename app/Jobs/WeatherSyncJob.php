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

    /**
     * @throws \Exception
     */
    public function handle(WeatherSyncService $weatherSyncService): void
    {
        $result = $weatherSyncService->sync();
        
        if (isset($result['success']) && !$result['success']) {
            throw new \Exception($result['message'] ?? 'Sinkronisasi gagal pada WeatherSyncService.');
        }
    }
}