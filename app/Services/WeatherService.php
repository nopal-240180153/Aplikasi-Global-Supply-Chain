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

        $weather = $response->json();

        $current = $weather['current'];

        $score = 0;

        if ($current['temperature_2m'] > 35) {
            $score += 20;
        }

        if ($current['wind_speed_10m'] > 40) {
            $score += 40;
        }

        if (in_array($current['weather_code'], [
            95,96,99,
            65,67,
            82
        ])) {
            $score += 40;
        }

        if ($score <= 30) {
            $risk = 'Rendah';
        } elseif ($score <= 60) {
            $risk = 'Sedang';
        } else {
            $risk = 'Tinggi';
        }

        $weather['risk_score'] = $score;
        $weather['risk_level'] = $risk;

        return $weather;
    }
}