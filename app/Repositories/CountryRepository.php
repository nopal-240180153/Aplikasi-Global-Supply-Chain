<?php

namespace App\Repositories;

use App\Models\Country;

class CountryRepository
{
    /**
     * Simpan atau update data negara.
     */
    public function updateOrCreate(array $data): Country
    {
        return Country::updateOrCreate(

            [
                'uuid' => $data['uuid']
            ],

            $data

        );
    }

    /**
     * Jumlah seluruh negara.
     */
    public function count(): int
    {
        return Country::count();
    }

    /**
     * Ambil semua negara.
     */
    public function all()
    {
        return Country::all();
    }

    /**
     * Ambil negara berdasarkan UUID.
     */
    public function findByUuid(string $uuid): ?Country
    {
        return Country::where('uuid', $uuid)->first();
    }
}