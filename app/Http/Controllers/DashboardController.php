<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\WeatherLog;
use App\Models\ExchangeRate;
use App\Models\EconomyData;
use App\Models\NewsArticle;
use Illuminate\Support\Facades\DB;

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
        | Chart Risk - Fixed to use actual risk_scores
        |--------------------------------------------------------------------------
        */

        $riskChart = DB::table('countries')
            ->join('risk_scores', 'countries.id', '=', 'risk_scores.country_id')
            ->selectRaw("
                CASE
                    WHEN risk_scores.total_score >= 30 THEN 'High'
                    WHEN risk_scores.total_score >= 20 THEN 'Medium'
                    ELSE 'Low'
                END as risk_level,
                COUNT(*) as total
            ")
            ->groupBy('risk_level')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Top Risk Countries
        |--------------------------------------------------------------------------
        */

        $topRiskCountries = Country::join('risk_scores', 'countries.id', '=', 'risk_scores.country_id')
            ->select('countries.*', 'risk_scores.total_score as risk_score')
            ->orderByDesc('risk_scores.total_score')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent News
        |--------------------------------------------------------------------------
        */

        $recentNews = NewsArticle::orderByDesc('published_at')
            ->take(4)
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

            'topRiskCountries',

            'recentNews'

        ));
    }
}