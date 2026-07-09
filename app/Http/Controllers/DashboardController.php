<?php

namespace App\Http\Controllers;

use App\Models\Country;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCountries = Country::count();

        $totalContinents = Country::whereNotNull('continent')
            ->distinct()
            ->count('continent');

        $globalRisk = 'Low';

        $countries = Country::all();

        $continentChart = Country::selectRaw('continent, COUNT(*) as total')
            ->whereNotNull('continent')
            ->groupBy('continent')
            ->orderBy('total', 'DESC')
            ->get();

        $riskChart = Country::selectRaw('risk_level, COUNT(*) as total')
            ->groupBy('risk_level')
            ->get();

        return view('dashboard', compact(
            'totalCountries',
            'totalContinents',
            'globalRisk',
            'countries',
            'continentChart',
            'riskChart'
        ));
    }
}