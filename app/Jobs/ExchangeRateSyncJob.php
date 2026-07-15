<?php

namespace App\Jobs;

use App\Services\Sync\ExchangeRateSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExchangeRateSyncJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    /**
     * @throws \Exception
     */
    public function handle(
        ExchangeRateSyncService $exchangeRateSyncService
    ): void
    {
        $result = $exchangeRateSyncService->sync();
        
        if (isset($result['success']) && !$result['success']) {
            throw new \Exception($result['message'] ?? 'Sinkronisasi gagal pada ExchangeRateSyncService.');
        }
    }
}