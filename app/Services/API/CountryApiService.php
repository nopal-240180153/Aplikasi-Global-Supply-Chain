<?php

namespace App\Services\API;

use Illuminate\Support\Facades\Http;

class CountryApiService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.restcountries.url');
        $this->apiKey = config('services.restcountries.key');
    }

    /**
     * Mengambil satu halaman data negara
     */
    public function getCountries(int $offset = 0): array
    {
        $response = Http::timeout(60)
            ->withToken($this->apiKey)
            ->acceptJson()
            ->get($this->baseUrl . '/countries/v5', [
                'offset' => $offset
            ]);

        if (!$response->successful()) {
            throw new \Exception(
                'REST Countries Error : ' . $response->status()
            );
        }

        return $response->json();
    }
}