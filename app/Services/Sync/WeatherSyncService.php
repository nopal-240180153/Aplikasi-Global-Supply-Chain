<?php

namespace App\Services\Sync;

use App\Models\Country;
use App\Models\SyncLog;
use App\Repositories\WeatherRepository;
use App\Services\API\WeatherApiService;
use App\Services\Mappers\WeatherMapper;
use Illuminate\Support\Facades\DB;

class WeatherSyncService
{
    protected WeatherApiService $apiService;
    protected WeatherRepository $repository;
    protected WeatherMapper $mapper;

    public function __construct(
        WeatherApiService $apiService,
        WeatherRepository $repository,
        WeatherMapper $mapper
    ) {
        $this->apiService = $apiService;
        $this->repository = $repository;
        $this->mapper = $mapper;
    }

    /**
     * Sinkronisasi data cuaca seluruh negara
     */
    public function sync(): array
    {
        $startedAt = now();

        $log = SyncLog::create([
            'module' => 'Weather',
            'status' => 'Running',
            'started_at' => $startedAt,
        ]);

        $total = 0;
        $updated = 0;
        $failed = 0;

        DB::beginTransaction();

        try {

            Country::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->orderBy('id')
                ->chunk(25, function ($countries) use (&$total, &$updated, &$failed) {

                    foreach ($countries as $country) {

                        try {

                            $weather = $this->apiService->current(
                                $country->latitude,
                                $country->longitude
                            );

                            $data = $this->mapper->map(
                                $country,
                                $weather
                            );

                            $this->repository->updateOrCreate($data);

                            $updated++;

                        } catch (\Throwable $e) {

                            $failed++;

                        }

                        $total++;
                    }
                });

            DB::commit();

            $log->update([

                'status' => 'Success',

                'total_data' => $total,

                'updated_data' => $updated,

                'failed_data' => $failed,

                'duration' => round(
                    microtime(true) - strtotime($startedAt),
                    2
                ),

                'finished_at' => now(),

                'message' => 'Sinkronisasi cuaca berhasil.'

            ]);

            return [

                'success' => true,

                'total' => $total,

                'updated' => $updated,

                'failed' => $failed,

                'message' => 'Sinkronisasi cuaca berhasil.'

            ];

        } catch (\Throwable $e) {

            DB::rollBack();

            $log->update([

                'status' => 'Failed',

                'failed_data' => $failed,

                'duration' => round(
                    microtime(true) - strtotime($startedAt),
                    2
                ),

                'finished_at' => now(),

                'message' => $e->getMessage(),

            ]);

            return [

                'success' => false,

                'message' => $e->getMessage(),

            ];
        }
    }
}