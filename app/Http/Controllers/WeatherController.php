<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\WeatherLog;
use App\Repositories\WeatherRepository;

class WeatherController extends Controller
{
    protected WeatherRepository $repository;

    public function __construct(WeatherRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $continent = $request->continent;
        $condition = $request->condition;

        $weatherLogs = $this->repository->paginate(
            50,
            $search,
            $continent,
            $condition
        );

        $continents = Country::select('continent')
            ->distinct()
            ->orderBy('continent')
            ->pluck('continent');

        $conditions = [
            'Clear',
            'Cloudy',
            'Rain',
            'Fog',
            'Snow',
            'Thunderstorm',
            'Unknown'
        ];

        return view('weather.index', [

            'weatherLogs' => $weatherLogs,

            'continents' => $continents,

            'conditions' => $conditions,

            'totalCountries' => $this->repository->count(),

            'averageTemperature' => $this->repository->averageTemperature(),

            'highRiskCountries' => $this->repository->highRiskCount(),

            'lastUpdate' => $this->repository->latestUpdate(),

        ]);
    }

    /**
     * Get weather map data for Leaflet visualization
     */
    public function getMapData(Request $request)
    {
        $weatherData = WeatherLog::with('country')
            ->when($request->condition, function ($query, $condition) {
                $query->where('weather_condition', $condition);
            })
            ->when($request->continent, function ($query, $continent) {
                $query->whereHas('country', function ($q) use ($continent) {
                    $q->where('continent', $continent);
                });
            })
            ->get()
            ->map(function ($weather) {
                // Determine marker color based on weather condition
                $color = $this->getWeatherColor($weather->weather_condition);
                $icon = $this->getWeatherIcon($weather->weather_condition);
                
                return [
                    'country_id' => $weather->country_id,
                    'country_name' => $weather->country->name,
                    'latitude' => (float) $weather->country->latitude,
                    'longitude' => (float) $weather->country->longitude,
                    'temperature' => $weather->temperature,
                    'rainfall' => $weather->rainfall,
                    'wind_speed' => $weather->wind_speed,
                    'storm_risk' => $weather->storm_risk,
                    'weather_condition' => $weather->weather_condition,
                    'flag' => $weather->country->flag,
                    'color' => $color,
                    'icon' => $icon,
                    'recorded_at' => $weather->recorded_at->format('d M Y H:i'),
                ];
            });

        return response()->json($weatherData);
    }

    /**
     * Get weather color based on condition
     */
    private function getWeatherColor($condition): string
    {
        return match ($condition) {
            'Clear' => '#4CAF50',       // Green
            'Cloudy' => '#9E9E9E',      // Gray
            'Rain' => '#2196F3',        // Blue
            'Fog' => '#607D8B',         // Blue Gray
            'Snow' => '#E3F2FD',        // Light Blue
            'Thunderstorm' => '#F44336', // Red
            default => '#FFC107',        // Amber for unknown
        };
    }

    /**
     * Get weather icon based on condition
     */
    private function getWeatherIcon($condition): string
    {
        return match ($condition) {
            'Clear' => 'bi-sun-fill',
            'Cloudy' => 'bi-cloud-fill',
            'Rain' => 'bi-cloud-rain-heavy-fill',
            'Fog' => 'bi-cloud-fog-fill',
            'Snow' => 'bi-snow',
            'Thunderstorm' => 'bi-cloud-lightning-rain-fill',
            default => 'bi-question-circle-fill',
        };
    }
}