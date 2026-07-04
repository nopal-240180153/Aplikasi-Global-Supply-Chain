<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function getCurrentWeather($latitude = -6.2088, $longitude = 106.8456)
    {
        $response = Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'current' => 'temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code',
            'timezone' => 'auto'
        ]);

        if (!$response->successful()) {
            return null;
        }

        return $response->json();
    }
}