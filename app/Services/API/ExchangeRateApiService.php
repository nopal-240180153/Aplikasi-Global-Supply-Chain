<?php

namespace App\Services\API;

use Illuminate\Support\Facades\Http;

class ExchangeRateApiService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.exchange_rate.url');
        $this->apiKey  = config('services.exchange_rate.key');
    }

    /**
     * Mengambil nilai tukar berdasarkan mata uang dasar.
     *
     * Contoh:
     * USD -> seluruh mata uang
     */
    public function latest(string $baseCurrency = 'USD'): array
    {
        $response = Http::timeout(10)
            ->connectTimeout(5)
            ->acceptJson()
            ->get(
                "{$this->baseUrl}/{$this->apiKey}/latest/{$baseCurrency}"
            );

        if (!$response->successful()) {

            throw new \Exception(
                'Exchange Rate API Error'
            );

        }

        return $response->json();
    }
}