<?php

namespace App\Jobs;

use App\Models\SyncLog;
use App\Services\Risk\RiskSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RiskSyncJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public function handle(RiskSyncService $service): void
    {
        $start = microtime(true);

        $log = SyncLog::create([

            'module' => 'Risk',

            'status' => 'Running',

            'started_at' => now(),

        ]);

        try {

            $total = $service->sync();

            $log->update([

                'status' => 'Success',

                'total_data' => $total,

                'updated_data' => $total,

                'failed_data' => 0,

                'duration' => round(
                    microtime(true) - $start,
                    2
                ),

                'message' => 'Sinkronisasi analisis risiko berhasil.',

                'finished_at' => now(),

            ]);

        } catch (\Throwable $e) {

            $log->update([

                'status' => 'Failed',

                'failed_data' => 1,

                'duration' => round(
                    microtime(true) - $start,
                    2
                ),

                'message' => $e->getMessage(),

                'finished_at' => now(),

            ]);

            throw $e;

        }
    }
}