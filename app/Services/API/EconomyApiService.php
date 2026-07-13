<?php

namespace App\Services\API;

use Illuminate\Support\Facades\Http;

class EconomyApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.world_bank.url');
    }

    /**
     * Ambil indikator untuk 1 negara
     */
    public function indicator(
        string $countryCode,
        string $indicator,
        int $year
    ): array {

        $response = Http::timeout(60)
            ->connectTimeout(20)
            ->retry(3, 1000)
            ->acceptJson()
            ->withHeaders([
                'User-Agent' => 'Laravel Global Supply Chain Monitoring',
            ])
            ->get(
                "{$this->baseUrl}/country/{$countryCode}/indicator/{$indicator}",
                [
                    'format'   => 'json',
                    'per_page' => 1,
                    'date'     => $year,
                ]
            );

        if (!$response->successful()) {

            throw new \Exception('World Bank API Error');

        }

        return $response->json();
    }

    /**
     * Ambil indikator untuk seluruh negara
     */
    public function indicatorAllCountries(
        string $indicator,
        int $year
    ): array {

        $response = Http::timeout(60)
            ->connectTimeout(20)
            ->retry(3, 1000)
            ->acceptJson()
            ->withHeaders([
                'User-Agent' => 'Laravel Global Supply Chain Monitoring',
            ])
            ->get(
                "{$this->baseUrl}/country/all/indicator/{$indicator}",
                [
                    'format'   => 'json',
                    'per_page' => 400,
                    'date'     => $year,
                ]
            );

        if (!$response->successful()) {

            throw new \Exception('World Bank API Error');

        }

        return $response->json();
    }
}