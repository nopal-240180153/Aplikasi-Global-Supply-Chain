<?php

namespace App\Services\API;

use Illuminate\Support\Facades\Http;

class WeatherApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.open_meteo.url');
    }

    public function current(float $latitude, float $longitude): array
    {
        $response = Http::timeout(10)
            ->connectTimeout(5)
            ->acceptJson()
            ->get($this->baseUrl . '/forecast', [

                'latitude' => $latitude,
                'longitude' => $longitude,

                'current' => implode(',', [
                    'temperature_2m',
                    'relative_humidity_2m',
                    'wind_speed_10m',
                    'weather_code'
                ])

            ]);

        if (!$response->successful()) {
            throw new \Exception('OpenMeteo Error');
        }

        return $response->json();
    }
}