<?php

namespace App\Services;

use App\Models\Country;

class CountryService
{
    /**
     * Ambil seluruh negara dari database
     */
    public function getAllCountries()
    {
        return Country::orderBy('name')->get();
    }

    /**
     * Ambil negara berdasarkan UUID
     */
    public function findByUuid(string $uuid)
    {
        return Country::where('uuid', $uuid)->first();
    }

    /**
     * Total negara
     */
    public function totalCountries(): int
    {
        return Country::count();
    }
}