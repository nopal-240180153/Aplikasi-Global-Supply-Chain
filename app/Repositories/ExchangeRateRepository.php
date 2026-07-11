<?php

namespace App\Repositories;

use App\Models\ExchangeRate;

class ExchangeRateRepository
{
    /**
     * Simpan atau update kurs mata uang
     */
    public function updateOrCreate(array $data): ExchangeRate
    {
        return ExchangeRate::updateOrCreate(

            [
                'country_id'      => $data['country_id'],
                'base_currency'   => $data['base_currency'],
                'target_currency' => $data['target_currency'],
            ],

            [
                'exchange_rate' => $data['exchange_rate'],
                'recorded_at'   => $data['recorded_at'],
            ]

        );
    }

    /**
     * Semua data kurs
     */
    public function getAll()
    {
        return ExchangeRate::with('country')
            ->latest('recorded_at')
            ->get();
    }

    /**
     * Pagination
     */
    public function paginate($perPage = 20)
    {
        return ExchangeRate::with('country')
            ->latest('recorded_at')
            ->paginate($perPage);
    }
    /**
 * Top 10 Exchange Rate
 */
public function topExchangeRates(int $limit = 10)
{
    return ExchangeRate::with('country')
        ->orderByDesc('exchange_rate')
        ->take($limit)
        ->get();
}
    /**
     * Total data
     */
    public function count()
    {
        return ExchangeRate::count();
    }
}