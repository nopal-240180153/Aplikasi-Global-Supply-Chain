<?php

namespace App\Services\Risk;

class ExchangeRiskService
{
    /**
     * Menghitung skor risiko berdasarkan nilai tukar mata uang.
     *
     * Semakin besar nilai tukar terhadap USD,
     * diasumsikan semakin tinggi volatilitasnya.
     */
    public function calculate(?float $exchangeRate): float
    {
        if ($exchangeRate === null) {
            return 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Penilaian Risiko Nilai Tukar
        |--------------------------------------------------------------------------
        */

        if ($exchangeRate < 5) {

            return 5;

        }

        if ($exchangeRate < 50) {

            return 10;

        }

        if ($exchangeRate < 500) {

            return 15;

        }

        if ($exchangeRate < 5000) {

            return 20;

        }

        if ($exchangeRate < 15000) {

            return 30;

        }

        return 40;
    }
}