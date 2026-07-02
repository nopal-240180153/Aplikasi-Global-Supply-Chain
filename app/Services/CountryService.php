<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CountryService
{
    public function getAllCountries()
    {
        $allCountries = collect();

        $offset = 0;
        $limit = 25;

        do {

            $response = Http::withToken(env('REST_COUNTRIES_API_KEY'))
                ->acceptJson()
                ->get(env('REST_COUNTRIES_BASE_URL') . '/countries/v5', [
                    'offset' => $offset
                ]);

            if (!$response->successful()) {
                abort(500, 'Gagal mengambil data negara.');
            }

            $json = $response->json();

            $countries = collect($json['data']['objects'] ?? []);

            $allCountries = $allCountries->merge($countries);

            $more = $json['data']['meta']['more'] ?? false;

            $offset += $limit;

        } while ($more);

        return $allCountries;
    }
}