<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
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
}