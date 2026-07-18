<?php

namespace App\Jobs;

use App\Models\Port;
use App\Models\SyncLog;
use App\Services\API\PortApiService;
use App\Services\Mappers\PortMapper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PortSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $startTime = microtime(true);

        // Create sync log
        $syncLog = SyncLog::create([
            'module' => 'Ports',
            'status' => 'Running',
            'started_at' => now(),
        ]);

        try {
            Log::info('Starting Port Sync Job...');

            $apiService = new PortApiService();
            $mapper = new PortMapper();

            // Fetch ports from API
            $portsData = $apiService->getPorts();

            if (empty($portsData)) {
                throw new \Exception('No port data received from API');
            }

            Log::info('Processing ' . count($portsData) . ' ports...');

            $successCount = 0;
            $errorCount = 0;

            foreach ($portsData as $portData) {
                try {
                    // Map data
                    $mappedData = $mapper->map($portData);

                    if ($mappedData === null) {
                        $errorCount++;
                        continue;
                    }

                    // Upsert port (update if exists, insert if not)
                    Port::updateOrCreate(
                        [
                            'port_name' => $mappedData['port_name'],
                            'latitude' => $mappedData['latitude'],
                            'longitude' => $mappedData['longitude'],
                        ],
                        $mappedData
                    );

                    $successCount++;

                } catch (\Exception $e) {
                    $errorCount++;
                    Log::warning('Error processing port', [
                        'port' => $portData['portName'] ?? 'unknown',
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $duration = round(microtime(true) - $startTime, 2);

            // Update sync log - Success
            $syncLog->update([
                'status' => 'Success',
                'updated_data' => $successCount,
                'duration' => $duration,
                'finished_at' => now(),
                'message' => "Berhasil sinkronisasi {$successCount} pelabuhan. Error: {$errorCount}."
            ]);

            Log::info('Port Sync Job completed', [
                'success' => $successCount,
                'errors' => $errorCount,
                'duration' => $duration
            ]);

        } catch (\Exception $e) {
            $duration = round(microtime(true) - $startTime, 2);

            // Update sync log - Failed
            $syncLog->update([
                'status' => 'Failed',
                'duration' => $duration,
                'finished_at' => now(),
                'message' => 'Error: ' . $e->getMessage()
            ]);

            Log::error('Port Sync Job failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
