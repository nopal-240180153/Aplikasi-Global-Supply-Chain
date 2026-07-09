<?php

namespace App\Services\Sync;

use App\Models\SyncLog;
use App\Repositories\CountryRepository;
use App\Services\API\CountryApiService;
use App\Services\Mappers\CountryMapper;
use Illuminate\Support\Facades\DB;

class CountrySyncService
{
    protected CountryApiService $apiService;
    protected CountryRepository $repository;
    protected CountryMapper $mapper;

    public function __construct(
        CountryApiService $apiService,
        CountryRepository $repository,
        CountryMapper $mapper
    ) {
        $this->apiService = $apiService;
        $this->repository = $repository;
        $this->mapper = $mapper;
    }

    /**
     * Sinkronisasi seluruh negara
     */
    public function sync(): array
    {
        $startedAt = now();

        $log = SyncLog::create([
            'module' => 'Countries',
            'status' => 'Running',
            'started_at' => $startedAt,
        ]);

        $offset = 0;
        $limit = 25;

        $totalData = 0;
        $updatedData = 0;
        $failedData = 0;

        DB::beginTransaction();

        try {

            do {

                $json = $this->apiService->getCountries($offset);

                $countries = $json['data']['objects'] ?? [];

                foreach ($countries as $country) {

                    $data = $this->mapper->map($country);

                    $this->repository->updateOrCreate($data);

                    $updatedData++;
                }

                $totalData += count($countries);

                $offset += $limit;

                $more = $json['data']['meta']['more'] ?? false;

            } while ($more);

            DB::commit();

            $log->update([
                'status' => 'Success',
                'total_data' => $totalData,
                'updated_data' => $updatedData,
                'failed_data' => 0,
                'duration' => $startedAt->diffInSeconds(now()),
                'finished_at' => now(),
                'message' => 'Sinkronisasi negara berhasil.',
            ]);

            return [
                'success' => true,
                'total' => $totalData,
                'updated' => $updatedData,
                'failed' => 0,
                'message' => 'Sinkronisasi berhasil.',
            ];

        } catch (\Throwable $e) {

            DB::rollBack();

            $failedData++;

            $log->update([
                'status' => 'Failed',
                'failed_data' => $failedData,
                'duration' => $startedAt->diffInSeconds(now()),
                'finished_at' => now(),
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'total' => $totalData,
                'updated' => $updatedData,
                'failed' => $failedData,
                'message' => $e->getMessage(),
            ];
        }
    }
}