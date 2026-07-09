<?php

namespace App\Services\Mappers;

use App\Models\Country;

class WeatherMapper
{
    public function map(Country $country, array $weather): array
    {
        $current = $weather['current'] ?? [];

        return [

            'country_id' => $country->id,

            'temperature' => $current['temperature_2m'] ?? null,

            'rainfall' => 0,

            'wind_speed' => $current['wind_speed_10m'] ?? null,

            'storm_risk' => 0,

            'weather_condition' => $this->weatherDescription(
                $current['weather_code'] ?? 0
            ),

            'recorded_at' => now()

        ];
    }

    private function weatherDescription(int $code): string
    {
        return match (true) {

            $code == 0 => 'Clear',

            in_array($code,[1,2,3]) => 'Cloudy',

            in_array($code,[45,48]) => 'Fog',

            in_array($code,[51,53,55,61,63,65,80,81,82]) => 'Rain',

            in_array($code,[71,73,75,85,86]) => 'Snow',

            in_array($code,[95,96,99]) => 'Thunderstorm',

            default => 'Unknown',

        };
    }
}