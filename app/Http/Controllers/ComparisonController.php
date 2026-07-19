<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\EconomyData;
use App\Models\ExchangeRate;
use App\Models\RiskScore;
use App\Models\WeatherLog;
use App\Models\NewsArticle;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    /**
     * Halaman perbandingan dua negara
     */
    public function index()
    {
        $countries = Country::orderBy('name')->get();
        return view('comparison.index', compact('countries'));
    }

    /**
     * API: ambil data perbandingan dua negara
     */
    public function compare(Request $request)
    {
        $request->validate([
            'country_a' => 'required|exists:countries,id',
            'country_b' => 'required|exists:countries,id',
        ]);

        $idA = $request->input('country_a');
        $idB = $request->input('country_b');

        $countryA = Country::find($idA);
        $countryB = Country::find($idB);

        return response()->json([
            'success' => true,
            'data' => [
                'country_a' => $this->buildProfile($countryA),
                'country_b' => $this->buildProfile($countryB),
                'gdp_trend'       => $this->buildGdpTrend($idA, $idB),
                'inflation_trend' => $this->buildInflationTrend($idA, $idB),
                'exchange_trend'  => $this->buildExchangeTrend($idA, $idB),
                'risk_trend'      => $this->buildRiskTrend($idA, $idB),
            ],
        ]);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function buildProfile(Country $c): array
    {
        $economy  = EconomyData::where('country_id', $c->id)->orderByDesc('year')->first();
        $exchange = ExchangeRate::where('country_id', $c->id)->latest('recorded_at')->first();
        $weather  = WeatherLog::where('country_id', $c->id)->latest('recorded_at')->first();
        $risk     = RiskScore::where('country_id', $c->id)->latest('calculated_at')->first();
        $newsCount = NewsArticle::where('country_id', $c->id)->count();

        return [
            'id'             => $c->id,
            'name'           => $c->name,
            'flag'           => $c->flag,
            'continent'      => $c->continent ?? '-',
            'capital'        => $c->capital ?? '-',
            'currency_code'  => $c->currency_code ?? '-',
            'latitude'       => (float) ($c->latitude  ?? 0),
            'longitude'      => (float) ($c->longitude ?? 0),

            // Economy
            'gdp'            => $economy?->gdp            ? number_format($economy->gdp / 1e9, 2) . ' B' : '-',
            'gdp_raw'        => $economy?->gdp            ?? 0,
            'inflation'      => $economy?->inflation       ? number_format($economy->inflation, 2) . '%'  : '-',
            'inflation_raw'  => $economy?->inflation       ?? 0,
            'gdp_year'       => $economy?->year            ?? '-',

            // Exchange
            'exchange_rate'  => $exchange?->exchange_rate  ? number_format($exchange->exchange_rate, 4) : '-',
            'exchange_raw'   => $exchange?->exchange_rate  ?? 0,
            'base_currency'  => $exchange?->base_currency  ?? '-',
            'target_currency'=> $exchange?->target_currency ?? '-',

            // Weather
            'temperature'    => $weather?->temperature     ? number_format($weather->temperature, 1) . '°C' : '-',
            'temp_raw'       => $weather?->temperature     ?? 0,
            'humidity'       => $weather?->humidity        ? number_format($weather->humidity, 1) . '%' : '-',
            'wind_speed'     => $weather?->wind_speed      ? number_format($weather->wind_speed, 1) . ' km/h' : '-',
            'weather_condition' => $weather?->condition    ?? '-',

            // Risk
            'weather_score'  => $risk ? round($risk->weather_score, 2)  : 0,
            'economy_score'  => $risk ? round($risk->economy_score, 2)  : 0,
            'exchange_score' => $risk ? round($risk->exchange_score, 2) : 0,
            'news_score'     => $risk ? round($risk->news_score, 2)     : 0,
            'total_score'    => $risk ? round($risk->total_score, 2)    : 0,
            'risk_level'     => $risk?->risk_level                      ?? '-',

            // News
            'news_count'     => $newsCount,
        ];
    }

    private function buildGdpTrend(int $idA, int $idB): array
    {
        $years = EconomyData::whereIn('country_id', [$idA, $idB])
            ->whereNotNull('gdp')
            ->orderBy('year')
            ->pluck('year')
            ->unique()
            ->values();

        $dataA = $this->getYearlyValues($idA, 'gdp', $years);
        $dataB = $this->getYearlyValues($idB, 'gdp', $years);

        return [
            'labels'   => $years,
            'datasets' => [
                ['label' => Country::find($idA)?->name, 'data' => $dataA, 'borderColor' => 'rgba(37,99,235,1)',  'backgroundColor' => 'rgba(37,99,235,0.12)', 'fill' => true,  'tension' => 0.4, 'pointRadius' => 4],
                ['label' => Country::find($idB)?->name, 'data' => $dataB, 'borderColor' => 'rgba(220,38,38,1)',  'backgroundColor' => 'rgba(220,38,38,0.12)',  'fill' => true,  'tension' => 0.4, 'pointRadius' => 4],
            ],
        ];
    }

    private function buildInflationTrend(int $idA, int $idB): array
    {
        $years = EconomyData::whereIn('country_id', [$idA, $idB])
            ->whereNotNull('inflation')
            ->orderBy('year')
            ->pluck('year')
            ->unique()
            ->values();

        $dataA = $this->getYearlyValues($idA, 'inflation', $years);
        $dataB = $this->getYearlyValues($idB, 'inflation', $years);

        return [
            'labels'   => $years,
            'datasets' => [
                ['label' => Country::find($idA)?->name, 'data' => $dataA, 'borderColor' => 'rgba(37,99,235,1)',  'backgroundColor' => 'rgba(37,99,235,0.08)', 'fill' => false, 'tension' => 0.4, 'pointRadius' => 4],
                ['label' => Country::find($idB)?->name, 'data' => $dataB, 'borderColor' => 'rgba(220,38,38,1)',  'backgroundColor' => 'rgba(220,38,38,0.08)',  'fill' => false, 'tension' => 0.4, 'pointRadius' => 4],
            ],
        ];
    }

    private function buildExchangeTrend(int $idA, int $idB): array
    {
        $periods = ExchangeRate::whereIn('country_id', [$idA, $idB])
            ->whereNotNull('exchange_rate')
            ->selectRaw("DATE_FORMAT(recorded_at, '%Y-%m') as period")
            ->groupByRaw("DATE_FORMAT(recorded_at, '%Y-%m')")
            ->orderBy('period')
            ->pluck('period');

        $dataA = $this->getMonthlyExchange($idA, $periods);
        $dataB = $this->getMonthlyExchange($idB, $periods);

        return [
            'labels'   => $periods,
            'datasets' => [
                ['label' => Country::find($idA)?->name, 'data' => $dataA, 'borderColor' => 'rgba(37,99,235,1)',  'backgroundColor' => 'rgba(37,99,235,0.08)', 'fill' => false, 'tension' => 0.4, 'pointRadius' => 2],
                ['label' => Country::find($idB)?->name, 'data' => $dataB, 'borderColor' => 'rgba(220,38,38,1)',  'backgroundColor' => 'rgba(220,38,38,0.08)',  'fill' => false, 'tension' => 0.4, 'pointRadius' => 2],
            ],
        ];
    }

    private function buildRiskTrend(int $idA, int $idB): array
    {
        $nameA = Country::find($idA)?->name;
        $nameB = Country::find($idB)?->name;

        $periods = RiskScore::whereIn('country_id', [$idA, $idB])
            ->selectRaw("DATE_FORMAT(calculated_at, '%Y-%m') as period")
            ->groupByRaw("DATE_FORMAT(calculated_at, '%Y-%m')")
            ->orderBy('period')
            ->pluck('period');

        $dataA = $this->getMonthlyRisk($idA, $periods);
        $dataB = $this->getMonthlyRisk($idB, $periods);

        return [
            'labels'   => $periods,
            'datasets' => [
                ['label' => $nameA . ' - Total Risk', 'data' => $dataA, 'borderColor' => 'rgba(37,99,235,1)',  'backgroundColor' => 'rgba(37,99,235,0.1)', 'fill' => true, 'tension' => 0.4, 'pointRadius' => 3],
                ['label' => $nameB . ' - Total Risk', 'data' => $dataB, 'borderColor' => 'rgba(220,38,38,1)',  'backgroundColor' => 'rgba(220,38,38,0.1)',  'fill' => true, 'tension' => 0.4, 'pointRadius' => 3],
            ],
        ];
    }

    private function getYearlyValues(int $countryId, string $field, $years): array
    {
        $rows = EconomyData::where('country_id', $countryId)
            ->whereIn('year', $years)
            ->whereNotNull($field)
            ->pluck($field, 'year');

        return $years->map(fn($y) => $rows[$y] ? round($rows[$y], 2) : null)->toArray();
    }

    private function getMonthlyExchange(int $countryId, $periods): array
    {
        $rows = ExchangeRate::where('country_id', $countryId)
            ->selectRaw("DATE_FORMAT(recorded_at, '%Y-%m') as period, AVG(exchange_rate) as rate")
            ->groupByRaw("DATE_FORMAT(recorded_at, '%Y-%m')")
            ->pluck('rate', 'period');

        return $periods->map(fn($p) => isset($rows[$p]) ? round($rows[$p], 4) : null)->toArray();
    }

    private function getMonthlyRisk(int $countryId, $periods): array
    {
        $rows = RiskScore::where('country_id', $countryId)
            ->selectRaw("DATE_FORMAT(calculated_at, '%Y-%m') as period, AVG(total_score) as total")
            ->groupByRaw("DATE_FORMAT(calculated_at, '%Y-%m')")
            ->pluck('total', 'period');

        return $periods->map(fn($p) => isset($rows[$p]) ? round($rows[$p], 2) : null)->toArray();
    }
}
