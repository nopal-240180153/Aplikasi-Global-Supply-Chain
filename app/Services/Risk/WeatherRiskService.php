<?php

namespace App\Services\Risk;

class WeatherRiskService
{
    /**
     * Hitung skor risiko cuaca.
     */
    public function calculate(
        ?float $temperature,
        ?float $rainfall,
        ?float $windSpeed,
        ?float $stormRisk
    ): float {

        $score = 0;

        /*
        |--------------------------------------------------------------------------
        | Suhu
        |--------------------------------------------------------------------------
        */

        if ($temperature !== null) {

            if ($temperature >= 40) {

                $score += 30;

            } elseif ($temperature >= 35) {

                $score += 20;

            } elseif ($temperature >= 30) {

                $score += 10;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Curah Hujan
        |--------------------------------------------------------------------------
        */

        if ($rainfall !== null) {

            if ($rainfall >= 150) {

                $score += 25;

            } elseif ($rainfall >= 75) {

                $score += 15;

            } elseif ($rainfall >= 30) {

                $score += 5;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Kecepatan Angin
        |--------------------------------------------------------------------------
        */

        if ($windSpeed !== null) {

            if ($windSpeed >= 80) {

                $score += 25;

            } elseif ($windSpeed >= 40) {

                $score += 15;

            } elseif ($windSpeed >= 20) {

                $score += 5;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Risiko Badai
        |--------------------------------------------------------------------------
        */

        if ($stormRisk !== null) {

            $score += $stormRisk;

        }

        /*
        |--------------------------------------------------------------------------
        | Maksimum 100
        |--------------------------------------------------------------------------
        */

        return min($score, 100);
    }
}