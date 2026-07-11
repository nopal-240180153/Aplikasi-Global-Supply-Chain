<?php

namespace App\Services\Mappers;

use App\Models\Country;

class ExchangeRateMapper
{
    /**
     * Mapping response Exchange Rate API
     */
    public function map(
        Country $country,
        string $baseCurrency,
        float $exchangeRate
    ): array {

        return [

            'country_id' => $country->id,

            'base_currency' => $baseCurrency,

            'target_currency' => $country->currency_code,

            'exchange_rate' => $exchangeRate,

            'recorded_at' => now(),

        ];
    }
}