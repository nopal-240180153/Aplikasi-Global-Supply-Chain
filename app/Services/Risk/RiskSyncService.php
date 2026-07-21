<?php

namespace App\Services\Risk;

use App\Models\Country;
use App\Models\EconomyData;
use App\Models\ExchangeRate;
use App\Models\WeatherLog;
use App\Models\NewsArticle;
use App\Repositories\RiskRepository;
use Carbon\Carbon;

class RiskSyncService
{
    protected RiskRepository $repository;

    protected RiskCalculationService $calculator;

    public function __construct(
        RiskRepository $repository,
        RiskCalculationService $calculator
    ) {
        $this->repository = $repository;
        $this->calculator = $calculator;
    }

    /**
     * Sinkronisasi seluruh analisis risiko.
     */
    public function sync(): int
    {
        $total = 0;

        $countries = Country::all();

        foreach ($countries as $country) {

            /*
            |--------------------------------------------------------------------------
            | Data Cuaca
            |--------------------------------------------------------------------------
            */

            $weather = WeatherLog::where('country_id', $country->id)
                ->latest('recorded_at')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Data Ekonomi
            |--------------------------------------------------------------------------
            */

            $economy = EconomyData::where('country_id', $country->id)
                ->latest('year')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Data Nilai Tukar
            |--------------------------------------------------------------------------
            */

            $exchange = ExchangeRate::where('country_id', $country->id)
                ->latest('recorded_at')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Hitung News Score (Normalized to 0-100)
            |--------------------------------------------------------------------------
            | Logic:
            | - Sentiment Score range: -10 to +10 (dari lexicon analysis)
            | - Negative sentiment = High Risk (score tinggi)
            | - Positive sentiment = Low Risk (score rendah)
            | - Neutral = Medium Risk
            |--------------------------------------------------------------------------
            */

            $avgSentiment = NewsArticle::where('country_id', $country->id)
                ->avg('sentiment_score');

            if ($avgSentiment === null) {
                // No news data → assume medium risk
                $newsScore = 50;
            } else {
                // Normalize sentiment to risk score (0-100)
                // Formula: newsScore = 50 - (avgSentiment * 5)
                // Examples:
                // - avgSentiment = -10 (very negative) → newsScore = 100 (high risk)
                // - avgSentiment = -5 (negative) → newsScore = 75
                // - avgSentiment = 0 (neutral) → newsScore = 50 (medium risk)
                // - avgSentiment = +5 (positive) → newsScore = 25
                // - avgSentiment = +10 (very positive) → newsScore = 0 (low risk)
                
                $newsScore = 50 - ($avgSentiment * 5);
                
                // Clamp between 0 and 100
                $newsScore = max(0, min(100, $newsScore));
            }

            /*
            |--------------------------------------------------------------------------
            | Payload
            |--------------------------------------------------------------------------
            */

            $payload = [

                // Weather
                'temperature'   => $weather?->temperature,
                'rainfall'      => $weather?->rainfall,
                'wind_speed'    => $weather?->wind_speed,
                'storm_risk'    => $weather?->storm_risk,

                // Economy
                'inflation'     => $economy?->inflation,
                'gdp'           => $economy?->gdp,
                'exports'       => $economy?->exports,
                'imports'       => $economy?->imports,

                // Exchange
                'exchange_rate' => $exchange?->exchange_rate,

                // News
                'news_score'    => $newsScore,

            ];

            /*
            |--------------------------------------------------------------------------
            | Hitung Risiko
            |--------------------------------------------------------------------------
            */

            $risk = $this->calculator->calculate($payload);

            /*
            |--------------------------------------------------------------------------
            | Simpan
            |--------------------------------------------------------------------------
            */

            $this->repository->updateOrCreate(

                [

                    'country_id' => $country->id

                ],

                [

                    'weather_score'  => $risk['weather_score'],

                    'economy_score'  => $risk['economy_score'],

                    'exchange_score' => $risk['exchange_score'],

                    'news_score'     => $risk['news_score'],

                    'total_score'    => $risk['total_score'],

                    'risk_level'     => $risk['risk_level'],

                    'calculated_at'  => Carbon::now(),

                ]

            );

            $total++;

        }

        return $total;
    }
}