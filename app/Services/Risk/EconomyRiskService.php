<?php

namespace App\Services\Risk;

class EconomyRiskService
{
    /**
     * Hitung skor risiko ekonomi.
     *
     * Parameter:
     * - Inflasi
     * - GDP
     * - Ekspor
     * - Impor
     */
    public function calculate(
        ?float $inflation,
        ?float $gdp,
        ?float $exports,
        ?float $imports
    ): float {

        $score = 0;

        /*
        |--------------------------------------------------------------------------
        | Inflasi
        |--------------------------------------------------------------------------
        */

        if ($inflation !== null) {

            if ($inflation <= 3) {

                $score += 5;

            } elseif ($inflation <= 6) {

                $score += 15;

            } elseif ($inflation <= 10) {

                $score += 30;

            } else {

                $score += 40;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Neraca Perdagangan
        |--------------------------------------------------------------------------
        */

        if (
            $exports !== null &&
            $imports !== null
        ) {

            if ($exports >= $imports) {

                $score += 5;

            } else {

                $score += 15;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | GDP
        |--------------------------------------------------------------------------
        */

        if ($gdp !== null) {

            if ($gdp >= 1000000000000) {

                $score += 5;

            } elseif ($gdp >= 100000000000) {

                $score += 10;

            } else {

                $score += 20;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Maksimum 100
        |--------------------------------------------------------------------------
        */

        return min($score, 100);
    }
}