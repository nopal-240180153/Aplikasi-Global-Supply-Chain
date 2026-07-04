<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use App\Services\CountryService;

class WeatherController extends Controller
{
    protected WeatherService $weatherService;
    protected CountryService $countryService;

    public function __construct(
        WeatherService $weatherService,
        CountryService $countryService
    ) {
        $this->weatherService = $weatherService;
        $this->countryService = $countryService;
    }

    public function index(Request $request)
    {
        // Ambil seluruh negara
$countries = $this->countryService->getAllCountries();

// Default negara pertama
$selectedCountry = $request->country ?? $countries->first()['uuid'];

        // Cari negara yang dipilih
        $country = $countries->firstWhere('uuid', $selectedCountry);

        // Ambil koordinat negara
        $latitude = $country['coordinates']['lat'] ?? 0;
        $longitude = $country['coordinates']['lng'] ?? 0;

        // Ambil data cuaca
        $weather = $this->weatherService->getCurrentWeather($latitude, $longitude);

        return view('weather.index', compact(
            'weather',
            'countries',
            'country'
        ));
    }
}