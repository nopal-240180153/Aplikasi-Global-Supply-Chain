<?php

namespace App\Http\Controllers;

use App\Services\CountryService;

class DashboardController extends Controller
{
    protected CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function index()
    {
        $countries = $this->countryService->getAllCountries();

        $totalCountries = $countries->count();

        return view('dashboard.index', compact('totalCountries'));
    }
}