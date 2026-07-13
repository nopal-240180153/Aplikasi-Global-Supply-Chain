<?php

namespace App\Services\Sync;

use App\Models\Country;
use App\Models\SyncLog;
use App\Repositories\EconomyRepository;
use App\Services\API\EconomyApiService;
use App\Services\Mappers\EconomyMapper;

class EconomySyncService
{
    protected EconomyApiService $apiService;
    protected EconomyRepository $repository;
    protected EconomyMapper $mapper;

    public function __construct(
        EconomyApiService $apiService,
        EconomyRepository $repository,
        EconomyMapper $mapper
    )
    {
        $this->apiService = $apiService;
        $this->repository = $repository;
        $this->mapper = $mapper;
    }

    public function sync(int $year = 2024): array
{
    $startedAt = now();

    $log = SyncLog::create([

        'module' => 'Economy',

        'status' => 'Running',

        'started_at' => $startedAt,

    ]);

    $total = 0;

    $updated = 0;

    $failed = 0;

    try {

        /*
        |--------------------------------------------------------------------------
        | Ambil seluruh data indikator dari World Bank
        |--------------------------------------------------------------------------
        */

        $gdp = $this->apiService->indicatorAllCountries(
            'NY.GDP.MKTP.CD',
            $year
        );

        $inflation = $this->apiService->indicatorAllCountries(
            'FP.CPI.TOTL.ZG',
            $year
        );

        $population = $this->apiService->indicatorAllCountries(
            'SP.POP.TOTL',
            $year
        );

        $exports = $this->apiService->indicatorAllCountries(
            'NE.EXP.GNFS.CD',
            $year
        );

        $imports = $this->apiService->indicatorAllCountries(
            'NE.IMP.GNFS.CD',
            $year
        );
    /*
|--------------------------------------------------------------------------
| Ubah response World Bank menjadi array berdasarkan ISO3
|--------------------------------------------------------------------------
*/

$datasets = [

    'gdp'        => $this->indexByIso3($gdp),

    'inflation' => $this->indexByIso3($inflation),

    'population'=> $this->indexByIso3($population),

    'exports'   => $this->indexByIso3($exports),

    'imports'   => $this->indexByIso3($imports),

];

/*
|--------------------------------------------------------------------------
| Sinkronisasi ke database
|--------------------------------------------------------------------------
*/

Country::whereNotNull('iso3')
    ->chunk(100, function ($countries) use (
        &$total,
        &$updated,
        &$failed,
        $datasets,
        $year
    ) {

        foreach ($countries as $country) {

            $total++;

            $iso3 = strtoupper($country->iso3);

            $data = $this->mapper->map(

                $country,

                $year,

                $datasets['gdp'][$iso3] ?? null,

                $datasets['inflation'][$iso3] ?? null,

                $datasets['population'][$iso3] ?? null,

                $datasets['exports'][$iso3] ?? null,

                $datasets['imports'][$iso3] ?? null,

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

    'message' => 'Sinkronisasi data ekonomi berhasil.',

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

            'message' => $e->getMessage(),

        ]);

        return [

            'success' => false,

            'message' => $e->getMessage(),

        ];

    }
}
/**
 * Mengubah response World Bank menjadi array berdasarkan ISO3
 */
private function indexByIso3(array $response): array
{
    $result = [];

    if (!isset($response[1])) {
        return $result;
    }

    foreach ($response[1] as $row) {

        if (
            empty($row['countryiso3code']) ||
            $row['countryiso3code'] === ''
        ) {
            continue;
        }

        $result[strtoupper($row['countryiso3code'])] = $row['value'];

    }

    return $result;
}
}