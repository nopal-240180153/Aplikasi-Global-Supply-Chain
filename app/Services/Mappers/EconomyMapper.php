<?php

namespace App\Services\Mappers;

use App\Models\Country;

class EconomyMapper
{
    /**
     * Mapping data ekonomi World Bank
     */
    public function map(
        Country $country,
        int $year,
        ?float $gdp,
        ?float $inflation,
        ?int $population,
        ?float $exports,
        ?float $imports
    ): array {

        return [

            'country_id' => $country->id,

            'year' => $year,

            'gdp' => $gdp,

            'inflation' => $inflation,

            'population' => $population,

            'exports' => $exports,

            'imports' => $imports,

        ];
    }
}