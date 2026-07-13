<?php

namespace App\Jobs;

use App\Services\Sync\EconomySyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class EconomySyncJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function handle(
        EconomySyncService $economySyncService
    ): void {

        $economySyncService->sync();

    }
}