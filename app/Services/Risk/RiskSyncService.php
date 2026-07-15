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
            | Hitung News Score
            |--------------------------------------------------------------------------
            */

            $newsScore = NewsArticle::where('country_id', $country->id)
                ->avg('sentiment_score');

            if ($newsScore === null) {
                $newsScore = 0;
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