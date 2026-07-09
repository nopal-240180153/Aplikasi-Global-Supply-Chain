<?php

namespace App\Services\Mappers;

class CountryMapper
{
    public function map(array $country): array
    {
        // Currency
        $currencyCode = null;
        $currencyName = null;

        if (!empty($country['currencies'])) {
            $currency = collect($country['currencies'])->first();

            $currencyCode = $currency['code'] ?? null;
            $currencyName = $currency['name'] ?? null;
        }

        // Language
        $language = null;

        if (!empty($country['languages'])) {
            $language = collect($country['languages'])
                ->pluck('name')
                ->implode(', ');
        }

        // Coordinate
        $latitude = $country['coordinates']['lat'] ?? null;
        $longitude = $country['coordinates']['lng'] ?? null;

        return [

            'uuid' => $country['uuid'],

            'name' => $country['names']['common'] ?? null,

            'official_name' => $country['names']['official'] ?? null,

            'iso2' => $country['codes']['alpha_2'] ?: null,

            'iso3' => $country['codes']['alpha_3'] ?: null,

            'region' => $country['region'] ?? null,

            'subregion' => $country['subregion'] ?? null,

            'continent' => $country['continents'][0] ?? null,

            'capital' => $country['capitals'][0]['name'] ?? null,

            'latitude' => $latitude,

            'longitude' => $longitude,

            'population' => $country['population'] ?? 0,

            'currency_code' => $currencyCode,

            'currency_name' => $currencyName,

            'language' => $language,

            'flag' => $country['flag']['url_png'] ?? null,

            'risk_score' => 0,

            'risk_level' => 'Low',

            'last_synced_at' => now(),
        ];
    }
}