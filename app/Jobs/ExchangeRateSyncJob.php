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

    public function handle(
        ExchangeRateSyncService $exchangeRateSyncService
    ): void
    {
        $exchangeRateSyncService->sync();
    }
}