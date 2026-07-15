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

    /**
     * @throws \Exception
     */
    public function handle(CountrySyncService $countrySyncService): void
    {
        $result = $countrySyncService->sync();
        
        if (isset($result['success']) && !$result['success']) {
            throw new \Exception($result['message'] ?? 'Sinkronisasi gagal pada CountrySyncService.');
        }
    }
}