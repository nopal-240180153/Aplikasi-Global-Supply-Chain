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

    /**
     * @throws \Exception
     */
    public function handle(
        EconomySyncService $economySyncService
    ): void {
        $result = $economySyncService->sync();

        if (isset($result['success']) && !$result['success']) {
            throw new \Exception($result['message'] ?? 'Sinkronisasi gagal pada EconomySyncService.');
        }

    }
}