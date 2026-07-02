<?php

namespace App\Http\Controllers;

use App\Services\CountryService;

class CountryController extends Controller
{
    protected CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function index()
    {
        $countries = $this->countryService->getAllCountries();

        return view('countries.index', compact('countries'));
    }
}