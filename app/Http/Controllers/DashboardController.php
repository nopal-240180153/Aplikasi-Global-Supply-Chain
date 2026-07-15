<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\WeatherLog;
use App\Models\ExchangeRate;
use App\Models\EconomyData;
use App\Models\SyncLog;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | KPI
        |--------------------------------------------------------------------------
        */

        $totalCountries = Country::count();

        $totalWeather = WeatherLog::count();

        $totalExchange = ExchangeRate::count();

        $totalEconomy = EconomyData::count();

        $totalContinents = Country::whereNotNull('continent')
            ->distinct()
            ->count('continent');

        /*
        |--------------------------------------------------------------------------
        | Global Risk (sementara)
        |--------------------------------------------------------------------------
        */

        $globalRisk = 'Low';

        /*
        |--------------------------------------------------------------------------
        | Leaflet Map
        |--------------------------------------------------------------------------
        */

        $countries = Country::all();

        /*
        |--------------------------------------------------------------------------
        | Chart Negara per Benua
        |--------------------------------------------------------------------------
        */

        $continentChart = Country::selectRaw('continent, COUNT(*) as total')
            ->whereNotNull('continent')
            ->groupBy('continent')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Chart Risk
        |--------------------------------------------------------------------------
        */

        $riskChart = Country::selectRaw('risk_level, COUNT(*) as total')
            ->groupBy('risk_level')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Sync
        |--------------------------------------------------------------------------
        */

        $recentSync = SyncLog::latest('finished_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(

            'totalCountries',

            'totalWeather',

            'totalExchange',

            'totalEconomy',

            'totalContinents',

            'globalRisk',

            'countries',

            'continentChart',

            'riskChart',

            'recentSync'

        ));
    }
}