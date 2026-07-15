<?php

namespace App\Repositories;

use App\Models\NewsArticle;

class NewsRepository
{
    /**
     * Ambil seluruh berita
     */
    public function all()
    {
        return NewsArticle::with('country')
            ->latest('published_at')
            ->paginate(20);
    }

    /**
     * Simpan atau update berita
     */
    public function updateOrCreate(array $attributes, array $values)
    {
        return NewsArticle::updateOrCreate(
            $attributes,
            $values
        );
    }

    /**
     * Cari berdasarkan negara
     */
    public function byCountry(int $countryId)
    {
        return NewsArticle::where('country_id', $countryId)
            ->latest('published_at')
            ->get();
    }
}