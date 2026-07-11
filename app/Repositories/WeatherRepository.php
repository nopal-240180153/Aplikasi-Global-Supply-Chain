<?php

namespace App\Repositories;

use App\Models\WeatherLog;

class WeatherRepository
{
    /**
     * Simpan atau update data cuaca
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
     * Data monitoring dengan search & filter
     */
    public function paginate(
        int $perPage = 20,
        ?string $search = null,
        ?string $continent = null,
        ?string $condition = null
    ) {

        return WeatherLog::with('country')

            ->when($search, function ($query) use ($search) {

                $query->whereHas('country', function ($q) use ($search) {

                    $q->where('name', 'like', "%{$search}%");

                });

            })

            ->when($continent, function ($query) use ($continent) {

                $query->whereHas('country', function ($q) use ($continent) {

                    $q->where('continent', $continent);

                });

            })

            ->when($condition, function ($query) use ($condition) {

                $query->where('weather_condition', $condition);

            })

            ->latest('recorded_at')

            ->paginate($perPage)

            ->withQueryString();
    }

    /**
     * Total data
     */
    public function count(): int
    {
        return WeatherLog::count();
    }

    /**
     * Rata-rata suhu
     */
    public function averageTemperature(): float
    {
        return round(
            WeatherLog::avg('temperature') ?? 0,
            1
        );
    }

    /**
     * Jumlah negara berisiko
     */
    public function highRiskCount(): int
    {
        return WeatherLog::where('storm_risk', '>', 50)
            ->count();
    }

    /**
     * Update terakhir
     */
    public function latestUpdate()
    {
        return WeatherLog::latest('recorded_at')
            ->value('recorded_at');
    }
}