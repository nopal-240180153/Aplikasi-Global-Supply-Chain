<?php

namespace App\Repositories;

use App\Models\WeatherLog;

class WeatherRepository
{
    /**
     * Simpan atau update data cuaca terbaru
     */
    public function updateOrCreate(array $data): WeatherLog
    {
        return WeatherLog::updateOrCreate(

            [
                'country_id' => $data['country_id'],
            ],

            [
                'temperature'       => $data['temperature'],
                'rainfall'          => $data['rainfall'],
                'wind_speed'        => $data['wind_speed'],
                'storm_risk'        => $data['storm_risk'],
                'weather_condition' => $data['weather_condition'],
                'recorded_at'       => $data['recorded_at'],
            ]

        );
    }

    /**
     * Ambil seluruh data cuaca terbaru
     */
    public function getAll()
    {
        return WeatherLog::with('country')
            ->latest('recorded_at')
            ->get();
    }

    /**
     * Pagination untuk halaman monitoring
     */
    public function paginate(int $perPage = 20)
    {
        return WeatherLog::with('country')
            ->latest('recorded_at')
            ->paginate($perPage);
    }

    /**
     * Total data cuaca
     */
    public function count(): int
    {
        return WeatherLog::count();
    }
}