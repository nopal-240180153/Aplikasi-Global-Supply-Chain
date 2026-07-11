<?php

namespace App\Services\Sync;

use App\Models\Country;
use App\Models\SyncLog;
use App\Repositories\ExchangeRateRepository;
use App\Services\API\ExchangeRateApiService;
use App\Services\Mappers\ExchangeRateMapper;

class ExchangeRateSyncService
{
    protected ExchangeRateApiService $apiService;
    protected ExchangeRateRepository $repository;
    protected ExchangeRateMapper $mapper;

    public function __construct(
        ExchangeRateApiService $apiService,
        ExchangeRateRepository $repository,
        ExchangeRateMapper $mapper
    )
    {
        $this->apiService = $apiService;
        $this->repository = $repository;
        $this->mapper = $mapper;
    }

    public function sync(): array
    {
        $startedAt = now();

        $log = SyncLog::create([
            'module' => 'Exchange Rate',
            'status' => 'Running',
            'started_at' => $startedAt,
        ]);

        $total = 0;
        $updated = 0;
        $failed = 0;

        try {

            $response = $this->apiService->latest('USD');

            $rates = $response['conversion_rates'] ?? [];

            Country::chunk(100, function ($countries) use (
                &$total,
                &$updated,
                &$failed,
                $rates
            ) {

                foreach ($countries as $country) {

                    $total++;

                    if (
                        empty($country->currency_code) ||
                        !isset($rates[$country->currency_code])
                    ) {

                        $failed++;

                        continue;

                    }

                    $data = $this->mapper->map(
                        $country,
                        'USD',
                        $rates[$country->currency_code]
                    );

                    $this->repository->updateOrCreate($data);

                    $updated++;
                }

            });

            $log->update([

                'status' => 'Success',

                'total_data' => $total,

                'updated_data' => $updated,

                'failed_data' => $failed,

                'finished_at' => now(),

                'duration' => now()->diffInSeconds($startedAt),

                'message' => 'Sinkronisasi Exchange Rate berhasil.'

            ]);

            return [

                'success' => true,

                'updated' => $updated,

                'failed' => $failed,

            ];

        } catch (\Throwable $e) {

            $log->update([

                'status' => 'Failed',

                'finished_at' => now(),

                'duration' => now()->diffInSeconds($startedAt),

                'message' => $e->getMessage()

            ]);

            return [

                'success' => false,

                'message' => $e->getMessage()

            ];

        }
    }
}