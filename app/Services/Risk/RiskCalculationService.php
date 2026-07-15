<?php

namespace App\Services\Risk;

class RiskCalculationService
{
    protected WeatherRiskService $weatherService;

    protected EconomyRiskService $economyService;

    protected ExchangeRiskService $exchangeService;

    public function __construct(
        WeatherRiskService $weatherService,
        EconomyRiskService $economyService,
        ExchangeRiskService $exchangeService
    ) {
        $this->weatherService = $weatherService;
        $this->economyService = $economyService;
        $this->exchangeService = $exchangeService;
    }

    /**
     * Hitung Analisis Risiko Supply Chain
     */
    public function calculate(array $data): array
    {
        /*
        |--------------------------------------------------------------------------
        | Weather Score
        |--------------------------------------------------------------------------
        */

        $weatherScore = $this->weatherService->calculate(

            $data['temperature'] ?? null,

            $data['rainfall'] ?? null,

            $data['wind_speed'] ?? null,

            $data['storm_risk'] ?? null

        );

        /*
        |--------------------------------------------------------------------------
        | Economy Score
        |--------------------------------------------------------------------------
        */

        $economyScore = $this->economyService->calculate(

            $data['inflation'] ?? null,

            $data['gdp'] ?? null,

            $data['exports'] ?? null,

            $data['imports'] ?? null

        );

        /*
        |--------------------------------------------------------------------------
        | Exchange Score
        |--------------------------------------------------------------------------
        */

        $exchangeScore = $this->exchangeService->calculate(

            $data['exchange_rate'] ?? null

        );

        /*
        |--------------------------------------------------------------------------
        | News Score
        |--------------------------------------------------------------------------
        */

        $newsScore = $data['news_score'] ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Bobot
        |--------------------------------------------------------------------------
        */

        $totalScore =

            ($weatherScore * 0.30) +

            ($economyScore * 0.30) +

            ($exchangeScore * 0.20) +

            ($newsScore * 0.20);

        /*
        |--------------------------------------------------------------------------
        | Risk Level
        |--------------------------------------------------------------------------
        */

        if ($totalScore < 20) {

            $riskLevel = 'Rendah';

        } elseif ($totalScore < 35) {

            $riskLevel = 'Sedang';

        } else {

            $riskLevel = 'Tinggi';

        }

        return [

            'weather_score' => round($weatherScore, 2),

            'economy_score' => round($economyScore, 2),

            'exchange_score' => round($exchangeScore, 2),

            'news_score' => round($newsScore, 2),

            'total_score' => round($totalScore, 2),

            'risk_level' => $riskLevel,

        ];
    }
}