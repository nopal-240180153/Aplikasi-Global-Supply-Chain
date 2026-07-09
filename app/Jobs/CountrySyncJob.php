<?php

namespace App\Jobs;

use App\Services\Sync\CountrySyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CountrySyncJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function handle(CountrySyncService $countrySyncService): void
    {
        $countrySyncService->sync();
    }
}