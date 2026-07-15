<?php

namespace App\Jobs;

use App\Models\SyncLog;
use App\Services\News\NewsSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewsSyncJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public function handle(NewsSyncService $service): void
    {
        $start = microtime(true);

        $log = SyncLog::create([

            'module' => 'News',

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

                'message' => 'Sinkronisasi berita berhasil.',

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