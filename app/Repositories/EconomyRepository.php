<?php

namespace App\Repositories;

use App\Models\EconomyData;

class EconomyRepository
{
    /**
     * Pagination data ekonomi
     */
    public function paginate(
        $perPage = 20,
        ?string $search = null,
        ?string $region = null
    ) {
        return EconomyData::with('country')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('country', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($region, function ($query) use ($region) {
                $query->whereHas('country', function ($q) use ($region) {
                    $q->where('region', $region);
                });
            })
            ->orderBy('year', 'desc')
            ->orderBy('country_id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Ambil semua data
     */
    public function getAll()
    {
        return EconomyData::with('country')
            ->orderBy('year', 'desc')
            ->get();
    }

    /**
     * Total data
     */
    public function count()
    {
        return EconomyData::count();
    }

    /**
     * Update atau Create data ekonomi
     */
    public function updateOrCreate(array $data)
    {
        return EconomyData::updateOrCreate(
            [
                'country_id' => $data['country_id'],
                'year'       => $data['year'],
            ],
            $data
        );
    }
}