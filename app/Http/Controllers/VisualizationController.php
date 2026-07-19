<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\EconomyData;
use App\Models\ExchangeRate;
use App\Models\RiskScore;
use App\Models\WeatherLog;
use App\Models\NewsArticle;
use App\Models\Port;
use App\Models\SyncLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisualizationController extends Controller
{
    /**
     * Display visualization dashboard
     */
    public function index()
    {
        // Get all countries for filter
        $countries = Country::orderBy('name')->get();

        return view('visualizations.index', compact('countries'));
    }

    /**
     * Get GDP trend data for chart
     */
    public function getGdpData(Request $request)
    {
        try {
            $viewMode = $request->input('view_mode', 'continent'); // continent or country
            $startDate = $request->input('start_date', Carbon::now()->subDays(30));
            $endDate = $request->input('end_date', Carbon::now());
            $aggregation = $request->input('aggregation', 'daily');

            \Log::info('getGdpData called', [
                'view_mode' => $viewMode,
                'countries' => $request->input('countries'),
                'continents' => $request->input('continents')
            ]);

            if ($viewMode === 'continent') {
                return $this->getGdpDataByContinent($request, $startDate, $endDate, $aggregation);
            } else {
                return $this->getGdpDataByCountry($request, $startDate, $endDate, $aggregation);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data GDP: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getGdpDataByContinent($request, $startDate, $endDate, $aggregation)
    {
        $continents = $request->input('continents', []);

        // If no continents specified, get all continents that have data
        if (empty($continents)) {
            $continents = Country::join('economy_data', 'countries.id', '=', 'economy_data.country_id')
                ->whereNotNull('economy_data.gdp')
                ->whereNotNull('countries.continent')
                ->distinct()
                ->pluck('countries.continent')
                ->toArray();
        }

        if (empty($continents)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data GDP'
            ], 400);
        }

        // Get latest GDP data per continent (top 10 countries per continent)
        $query = EconomyData::join('countries', 'economy_data.country_id', '=', 'countries.id')
            ->whereIn('countries.continent', $continents)
            ->whereNotNull('economy_data.gdp')
            ->orderBy('economy_data.created_at', 'desc');

        // Apply time aggregation
        if ($aggregation === 'weekly') {
            $data = $query->selectRaw('
                countries.continent,
                YEAR(economy_data.created_at) as year,
                WEEK(economy_data.created_at) as week,
                SUM(economy_data.gdp) as gdp,
                MIN(economy_data.created_at) as date
            ')
            ->groupBy('countries.continent', DB::raw('YEAR(economy_data.created_at)'), DB::raw('WEEK(economy_data.created_at)'))
            ->orderBy('date')
            ->get();
        } elseif ($aggregation === 'monthly') {
            $data = $query->selectRaw('
                countries.continent,
                YEAR(economy_data.created_at) as year,
                MONTH(economy_data.created_at) as month,
                SUM(economy_data.gdp) as gdp,
                MIN(economy_data.created_at) as date
            ')
            ->groupBy('countries.continent', DB::raw('YEAR(economy_data.created_at)'), DB::raw('MONTH(economy_data.created_at)'))
            ->orderBy('date')
            ->get();
        } else {
            // Get latest GDP per country, then group by continent
            $data = $query->selectRaw('
                countries.continent,
                countries.name as country_name,
                economy_data.gdp,
                economy_data.created_at
            ')
            ->get()
            ->groupBy('continent')
            ->map(function ($group) {
                return $group->take(10); // Top 10 per continent
            })
            ->flatten();
        }

        // Format data for Chart.js - show top countries by GDP
        $labels = $data->pluck('country_name')->take(15)->values();
        $gdpValues = $data->pluck('gdp')->take(15)->values();

        $colors = [
            'rgba(59, 130, 246, 0.8)',
            'rgba(16, 185, 129, 0.8)',
            'rgba(249, 115, 22, 0.8)',
            'rgba(139, 92, 246, 0.8)',
            'rgba(236, 72, 153, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(20, 184, 166, 0.8)',
            'rgba(239, 68, 68, 0.8)',
            'rgba(99, 102, 241, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(132, 204, 22, 0.8)',
            'rgba(251, 146, 60, 0.8)',
            'rgba(14, 165, 233, 0.8)',
            'rgba(244, 63, 94, 0.8)',
            'rgba(34, 197, 94, 0.8)'
        ];

        $datasets = [[
            'label' => 'GDP (USD)',
            'data' => $gdpValues->toArray(),
            'backgroundColor' => array_slice($colors, 0, count($labels)),
            'borderColor' => array_map(fn($c) => str_replace('0.8', '1', $c), array_slice($colors, 0, count($labels))),
            'borderWidth' => 2
        ]];

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'datasets' => $datasets
            ]
        ]);
    }

    private function getGdpDataByCountry($request, $startDate, $endDate, $aggregation)
    {
        $countryIds = $request->input('countries', []);
        
        \Log::info('getGdpDataByCountry called', [
            'countryIds' => $countryIds,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'aggregation' => $aggregation
        ]);

        // If no countries specified, get top 15 countries by GDP
        if (empty($countryIds)) {
            $countryIds = EconomyData::whereNotNull('gdp')
                ->orderBy('gdp', 'desc')
                ->take(15)
                ->distinct()
                ->pluck('country_id')
                ->toArray();
            
            \Log::info('No countries specified, fetched top 15:', ['count' => count($countryIds)]);
        }

        if (empty($countryIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data GDP'
            ], 400);
        }

        $query = EconomyData::whereIn('country_id', $countryIds)
            ->whereNotNull('gdp')
            ->orderBy('gdp', 'desc');

        // Apply aggregation
        if ($aggregation === 'weekly') {
            $data = $query->selectRaw('
                country_id,
                YEAR(created_at) as year,
                WEEK(created_at) as week,
                AVG(gdp) as gdp,
                MIN(created_at) as date
            ')
            ->groupBy('country_id', DB::raw('YEAR(created_at)'), DB::raw('WEEK(created_at)'))
            ->orderBy('date')
            ->get();
        } elseif ($aggregation === 'monthly') {
            $data = $query->selectRaw('
                country_id,
                YEAR(created_at) as year,
                MONTH(created_at) as month,
                AVG(gdp) as gdp,
                MIN(created_at) as date
            ')
            ->groupBy('country_id', DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('date')
            ->get();
        } else {
            $data = $query->with('country:id,name')->orderBy('created_at')->get();
        }

        // Load countries for aggregated data
        if ($aggregation !== 'daily') {
            $countryIds = $data->pluck('country_id')->unique();
            $countries = Country::whereIn('id', $countryIds)->pluck('name', 'id');
        }

        // Format data for Chart.js - show top countries by GDP
        $labels = $data->pluck('country.name')->take(15)->values();
        $gdpValues = $data->pluck('gdp')->take(15)->values();

        $colors = [
            'rgba(59, 130, 246, 0.8)',
            'rgba(16, 185, 129, 0.8)',
            'rgba(249, 115, 22, 0.8)',
            'rgba(139, 92, 246, 0.8)',
            'rgba(236, 72, 153, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(20, 184, 166, 0.8)',
            'rgba(239, 68, 68, 0.8)',
            'rgba(99, 102, 241, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(132, 204, 22, 0.8)',
            'rgba(251, 146, 60, 0.8)',
            'rgba(14, 165, 233, 0.8)',
            'rgba(244, 63, 94, 0.8)',
            'rgba(34, 197, 94, 0.8)'
        ];

        $datasets = [[
            'label' => 'GDP (USD)',
            'data' => $gdpValues->toArray(),
            'backgroundColor' => array_slice($colors, 0, count($labels)),
            'borderColor' => array_map(fn($c) => str_replace('0.8', '1', $c), array_slice($colors, 0, count($labels))),
            'borderWidth' => 2
        ]];

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'datasets' => $datasets
            ]
        ]);
    }

    /**
     * Get Inflation Rate data for chart
     */
    public function getInflationData(Request $request)
    {
        try {
            $viewMode = $request->input('view_mode', 'continent');
            $startDate = $request->input('start_date', Carbon::now()->subDays(30));
            $endDate = $request->input('end_date', Carbon::now());
            $aggregation = $request->input('aggregation', 'daily');

            if ($viewMode === 'continent') {
                $continents = $request->input('continents', []);
                
                if (empty($continents)) {
                    $continents = Country::join('economy_data', 'countries.id', '=', 'economy_data.country_id')
                        ->whereNotNull('economy_data.inflation')
                        ->whereNotNull('countries.continent')
                        ->distinct()
                        ->pluck('countries.continent')
                        ->toArray();
                }

                if (empty($continents)) {
                    return response()->json(['success' => false, 'message' => 'Tidak ada data inflasi'], 400);
                }

                $query = EconomyData::join('countries', 'economy_data.country_id', '=', 'countries.id')
                    ->whereIn('countries.continent', $continents)
                    ->whereNotNull('economy_data.inflation')
                    ->selectRaw('countries.continent, AVG(economy_data.inflation) as inflation')
                    ->groupBy('countries.continent');

                $data = $query->get();
                $labels = $data->pluck('continent')->toArray();
                $values = $data->pluck('inflation')->toArray();
            } else {
                // Country mode - show top 15 countries by inflation
                $countryIds = $request->input('countries', []);
                
                if (empty($countryIds)) {
                    $countryIds = EconomyData::whereNotNull('inflation')
                        ->orderBy('inflation', 'desc')
                        ->take(15)
                        ->distinct()
                        ->pluck('country_id')
                        ->toArray();
                }

                if (empty($countryIds)) {
                    return response()->json(['success' => false, 'message' => 'Tidak ada data inflasi'], 400);
                }

                $data = EconomyData::whereIn('country_id', $countryIds)
                    ->whereNotNull('inflation')
                    ->with('country:id,name')
                    ->orderBy('inflation', 'desc')
                    ->take(15)
                    ->get()
                    ->groupBy('country_id')
                    ->map(fn($records) => $records->last());

                $labels = $data->pluck('country.name')->toArray();
                $values = $data->pluck('inflation')->toArray();
            }

            $colors = [
                'rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)', 'rgba(249, 115, 22, 0.8)',
                'rgba(139, 92, 246, 0.8)', 'rgba(236, 72, 153, 0.8)', 'rgba(245, 158, 11, 0.8)',
                'rgba(20, 184, 166, 0.8)', 'rgba(239, 68, 68, 0.8)', 'rgba(99, 102, 241, 0.8)',
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'Laju Inflasi (%)',
                            'data' => $values,
                            'backgroundColor' => array_slice($colors, 0, count($values)),
                            'borderColor' => array_map(fn($c) => str_replace('0.8', '1', $c), array_slice($colors, 0, count($values))),
                            'borderWidth' => 2
                        ]
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data inflasi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get Exchange Rate data for chart
     */
    public function getExchangeRateData(Request $request)
    {
        try {
            $baseCurrency = $request->input('base_currency', 'USD');
            $targetCurrencies = $request->input('target_currencies', ['IDR', 'EUR', 'JPY', 'GBP', 'SGD']);
            $startDate = $request->input('start_date', Carbon::now()->subDays(30));
            $endDate = $request->input('end_date', Carbon::now());
            $aggregation = $request->input('aggregation', 'daily');

            // Get available target currencies in database
            $availableCurrencies = ExchangeRate::where('base_currency', $baseCurrency)
                ->whereIn('target_currency', $targetCurrencies)
                ->distinct()
                ->pluck('target_currency')
                ->toArray();

            // If no currencies match, try any available currencies
            if (empty($availableCurrencies)) {
                $availableCurrencies = ExchangeRate::where('base_currency', $baseCurrency)
                    ->distinct()
                    ->pluck('target_currency')
                    ->take(5)
                    ->toArray();
            }

            if (empty($availableCurrencies)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'labels' => [],
                        'datasets' => []
                    ],
                    'message' => 'Tidak ada data nilai tukar tersedia'
                ]);
            }

            $query = ExchangeRate::where('base_currency', $baseCurrency)
                ->whereIn('target_currency', $availableCurrencies);

            // Apply aggregation
            if ($aggregation === 'weekly') {
                $data = $query->selectRaw('
                    base_currency,
                    target_currency,
                    YEAR(recorded_at) as year,
                    WEEK(recorded_at) as week,
                    AVG(exchange_rate) as rate,
                    MIN(recorded_at) as date
                ')
                ->groupBy('base_currency', 'target_currency', DB::raw('YEAR(recorded_at)'), DB::raw('WEEK(recorded_at)'))
                ->orderBy('date')
                ->get();
            } elseif ($aggregation === 'monthly') {
                $data = $query->selectRaw('
                    base_currency,
                    target_currency,
                    YEAR(recorded_at) as year,
                    MONTH(recorded_at) as month,
                    AVG(exchange_rate) as rate,
                    MIN(recorded_at) as date
                ')
                ->groupBy('base_currency', 'target_currency', DB::raw('YEAR(recorded_at)'), DB::raw('MONTH(recorded_at)'))
                ->orderBy('date')
                ->get();
            } else {
                // Get latest rate per currency pair
                $data = $query->selectRaw('base_currency, target_currency, exchange_rate as rate, recorded_at')
                    ->orderBy('recorded_at', 'desc')
                    ->get()
                    ->groupBy('target_currency')
                    ->map(fn($group) => $group->first())
                    ->values();
            }

            if ($data->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'labels' => [],
                        'datasets' => []
                    ],
                    'message' => 'Tidak ada data nilai tukar untuk periode ini'
                ]);
            }

            $dateField = ($aggregation === 'daily') ? 'recorded_at' : 'date';
            $labels = $data->pluck($dateField)
                ->map(fn($date) => Carbon::parse($date)->format('d M Y'))
                ->unique()
                ->values();

            $colors = [
                'rgb(59, 130, 246)',
                'rgb(16, 185, 129)',
                'rgb(249, 115, 22)',
                'rgb(139, 92, 246)',
                'rgb(236, 72, 153)',
            ];

            $datasets = [];
            $groupedData = $data->groupBy('target_currency');
            $colorIndex = 0;

            foreach ($groupedData as $currency => $records) {
                $datasets[] = [
                    'label' => "$baseCurrency/$currency",
                    'data' => $records->pluck('rate')->toArray(),
                    'borderColor' => $colors[$colorIndex % count($colors)],
                    'backgroundColor' => $colors[$colorIndex % count($colors)] . '20',
                    'tension' => 0.4,
                    'fill' => false
                ];

                $colorIndex++;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'datasets' => $datasets
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data nilai tukar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Risk Score data for chart
     */
    public function getRiskScoreData(Request $request)
    {
        try {
            $viewMode = $request->input('view_mode', 'continent');
            $startDate = $request->input('start_date', Carbon::now()->subDays(30));
            $endDate = $request->input('end_date', Carbon::now());
            $aggregation = $request->input('aggregation', 'daily');

            if ($viewMode === 'continent') {
                return $this->getRiskDataByContinent($request, $startDate, $endDate, $aggregation);
            } else {
                return $this->getRiskDataByCountry($request, $startDate, $endDate, $aggregation);
            }

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data skor risiko: ' . $e->getMessage()], 500);
        }
    }

    private function getRiskDataByContinent($request, $startDate, $endDate, $aggregation)
    {
        $continents = $request->input('continents', []);

        if (empty($continents)) {
            $continents = Country::join('risk_scores', 'countries.id', '=', 'risk_scores.country_id')
                ->whereNotNull('countries.continent')
                ->distinct()
                ->pluck('countries.continent')
                ->toArray();
        }

        if (empty($continents)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data risiko'], 400);
        }

        $query = RiskScore::join('countries', 'risk_scores.country_id', '=', 'countries.id')
            ->whereIn('countries.continent', $continents);

        if ($aggregation === 'weekly') {
            $data = $query->selectRaw('
                countries.continent,
                YEAR(risk_scores.calculated_at) as year,
                WEEK(risk_scores.calculated_at) as week,
                AVG(risk_scores.total_score) as score,
                MIN(risk_scores.calculated_at) as date
            ')
            ->groupBy('countries.continent', DB::raw('YEAR(risk_scores.calculated_at)'), DB::raw('WEEK(risk_scores.calculated_at)'))
            ->orderBy('date')
            ->get();
        } elseif ($aggregation === 'monthly') {
            $data = $query->selectRaw('
                countries.continent,
                YEAR(risk_scores.calculated_at) as year,
                MONTH(risk_scores.calculated_at) as month,
                AVG(risk_scores.total_score) as score,
                MIN(risk_scores.calculated_at) as date
            ')
            ->groupBy('countries.continent', DB::raw('YEAR(risk_scores.calculated_at)'), DB::raw('MONTH(risk_scores.calculated_at)'))
            ->orderBy('date')
            ->get();
        } else {
            // Get latest risk score per country, grouped by continent
            $data = $query->selectRaw('countries.continent, countries.name as country_name, risk_scores.total_score as score, risk_scores.calculated_at')
                ->orderBy('risk_scores.calculated_at', 'desc')
                ->get()
                ->groupBy('continent')
                ->map(function ($group) {
                    return $group->take(10); // Top 10 per continent
                })
                ->flatten();
        }

        // Format data for Chart.js - show top countries by risk score
        $labels = $data->pluck('country_name')->take(15)->values();
        $riskScores = $data->pluck('score')->take(15)->values();

        $colors = [
            'rgba(239, 68, 68, 0.9)',
            'rgba(245, 158, 11, 0.9)',
            'rgba(16, 185, 129, 0.9)',
            'rgba(59, 130, 246, 0.9)',
            'rgba(139, 92, 246, 0.9)',
            'rgba(236, 72, 153, 0.9)',
            'rgba(20, 184, 166, 0.9)',
            'rgba(99, 102, 241, 0.9)',
            'rgba(168, 85, 247, 0.9)',
            'rgba(132, 204, 22, 0.9)',
            'rgba(251, 146, 60, 0.9)',
            'rgba(14, 165, 233, 0.9)',
            'rgba(244, 63, 94, 0.9)',
            'rgba(34, 197, 94, 0.9)',
            'rgba(234, 179, 8, 0.9)'
        ];

        $datasets = [[
            'data' => $riskScores->toArray(),
            'backgroundColor' => array_slice($colors, 0, count($labels)),
            'borderColor' => array_map(fn($c) => str_replace('0.9', '1', $c), array_slice($colors, 0, count($labels))),
            'borderWidth' => 2
        ]];

        return response()->json(['success' => true, 'data' => ['labels' => $labels, 'datasets' => $datasets]]);
    }

    private function getRiskDataByCountry($request, $startDate, $endDate, $aggregation)
    {
        $countryIds = $request->input('countries', []);

        // If no countries specified, get top 15 countries by risk score
        if (empty($countryIds)) {
            $countryIds = RiskScore::orderBy('total_score', 'desc')
                ->take(15)
                ->distinct()
                ->pluck('country_id')
                ->toArray();
        }

        if (empty($countryIds)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data risiko'], 400);
        }

        $query = RiskScore::whereIn('country_id', $countryIds)
            ->orderBy('total_score', 'desc');

        if ($aggregation === 'weekly') {
            $data = $query->selectRaw('country_id, YEAR(calculated_at) as year, WEEK(calculated_at) as week, AVG(total_score) as score, MIN(calculated_at) as date')
                ->groupBy('country_id', DB::raw('YEAR(calculated_at)'), DB::raw('WEEK(calculated_at)'))
                ->orderBy('date')->get();
        } elseif ($aggregation === 'monthly') {
            $data = $query->selectRaw('country_id, YEAR(calculated_at) as year, MONTH(calculated_at) as month, AVG(total_score) as score, MIN(calculated_at) as date')
                ->groupBy('country_id', DB::raw('YEAR(calculated_at)'), DB::raw('MONTH(calculated_at)'))
                ->orderBy('date')->get();
        } else {
            $data = $query->selectRaw('country_id, total_score as score, calculated_at')->with('country:id,name')->take(15)->get();
        }

        if ($aggregation !== 'daily') {
            $countries = Country::whereIn('id', $data->pluck('country_id')->unique())->pluck('name', 'id');
        }

        // Format data for Chart.js - show top countries by risk score
        $labels = $data->pluck('country.name')->take(15)->values();
        $riskScores = $data->pluck('score')->take(15)->values();

        $colors = [
            'rgba(239, 68, 68, 0.9)',
            'rgba(245, 158, 11, 0.9)',
            'rgba(16, 185, 129, 0.9)',
            'rgba(59, 130, 246, 0.9)',
            'rgba(139, 92, 246, 0.9)',
            'rgba(236, 72, 153, 0.9)',
            'rgba(20, 184, 166, 0.9)',
            'rgba(99, 102, 241, 0.9)',
            'rgba(168, 85, 247, 0.9)',
            'rgba(132, 204, 22, 0.9)',
            'rgba(251, 146, 60, 0.9)',
            'rgba(14, 165, 233, 0.9)',
            'rgba(244, 63, 94, 0.9)',
            'rgba(34, 197, 94, 0.9)',
            'rgba(234, 179, 8, 0.9)'
        ];

        $datasets = [[
            'data' => $riskScores->toArray(),
            'backgroundColor' => array_slice($colors, 0, count($labels)),
            'borderColor' => array_map(fn($c) => str_replace('0.9', '1', $c), array_slice($colors, 0, count($labels))),
            'borderWidth' => 2
        ]];

        return response()->json(['success' => true, 'data' => ['labels' => $labels, 'datasets' => $datasets]]);
    }

    /**
     * Get Risk Distribution (Doughnut Chart)
     */
    public function getRiskDistribution(Request $request)
    {
        try {
            $lowRisk = RiskScore::where('total_score', '<', 40)->count();
            $mediumRisk = RiskScore::whereBetween('total_score', [40, 69])->count();
            $highRisk = RiskScore::where('total_score', '>=', 70)->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => ['Risiko Rendah', 'Risiko Sedang', 'Risiko Tinggi'],
                    'datasets' => [[
                        'data' => [$lowRisk, $mediumRisk, $highRisk],
                        'backgroundColor' => [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        'borderColor' => [
                            'rgba(16, 185, 129, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        'borderWidth' => 2
                    ]]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data distribusi risiko: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get Top 10 High Risk Countries (Horizontal Bar)
     */
    public function getTopRiskCountries(Request $request)
    {
        try {
            $data = RiskScore::with('country:id,name')
                ->orderBy('total_score', 'desc')
                ->take(10)
                ->get();

            $labels = $data->pluck('country.name')->toArray();
            $scores = $data->pluck('total_score')->toArray();
            $riskLevels = $data->pluck('risk_level')->toArray();

            $backgroundColor = array_map(function($level) {
                if ($level === 'High' || $level === 'Tinggi') return 'rgba(239, 68, 68, 0.8)';
                if ($level === 'Medium' || $level === 'Sedang') return 'rgba(245, 158, 11, 0.8)';
                return 'rgba(16, 185, 129, 0.8)';
            }, $riskLevels);

            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'datasets' => [[
                        'label' => 'Total Risk Score',
                        'data' => $scores,
                        'backgroundColor' => $backgroundColor,
                        'borderWidth' => 0
                    ]],
                    'riskLevels' => $riskLevels
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data top risiko: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get Global Weather Data
     */
    public function getWeatherData(Request $request)
    {
        try {
            $limit = $request->input('limit', 15);
            $data = WeatherLog::with('country:id,name')
                ->orderBy('recorded_at', 'desc')
                ->take($limit)
                ->get();

            $labels = $data->pluck('country.name')->toArray();
            $temperatures = $data->pluck('temperature')->toArray();
            $rainfall = $data->pluck('rainfall')->toArray();
            $windSpeed = $data->pluck('wind_speed')->toArray();
            $stormRisk = $data->pluck('storm_risk')->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'Temperatur (°C)',
                            'data' => $temperatures,
                            'backgroundColor' => 'rgba(239, 68, 68, 0.8)',
                            'borderColor' => 'rgba(239, 68, 68, 1)',
                            'borderWidth' => 2
                        ],
                        [
                            'label' => 'Curah Hujan (mm)',
                            'data' => $rainfall,
                            'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                            'borderColor' => 'rgba(59, 130, 246, 1)',
                            'borderWidth' => 2
                        ],
                        [
                            'label' => 'Kecepatan Angin (km/h)',
                            'data' => $windSpeed,
                            'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                            'borderColor' => 'rgba(16, 185, 129, 1)',
                            'borderWidth' => 2
                        ],
                        [
                            'label' => 'Storm Risk',
                            'data' => $stormRisk,
                            'backgroundColor' => 'rgba(245, 158, 11, 0.8)',
                            'borderColor' => 'rgba(245, 158, 11, 1)',
                            'borderWidth' => 2
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data cuaca: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get Economy Data (GDP, Inflation, Exports, Imports)
     */
    public function getEconomyData(Request $request)
    {
        try {
            $limit = $request->input('limit', 15);
            $data = EconomyData::with('country:id,name')
                ->orderBy('created_at', 'desc')
                ->take($limit)
                ->get();

            $labels = $data->pluck('country.name')->toArray();
            $gdp = $data->pluck('gdp')->toArray();
            $inflation = $data->pluck('inflation')->toArray();
            $exports = $data->pluck('exports')->toArray();
            $imports = $data->pluck('imports')->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'GDP (USD)',
                            'data' => $gdp,
                            'borderColor' => 'rgba(59, 130, 246, 1)',
                            'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                            'tension' => 0.4,
                            'fill' => false
                        ],
                        [
                            'label' => 'Inflasi (%)',
                            'data' => $inflation,
                            'borderColor' => 'rgba(245, 158, 11, 1)',
                            'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                            'tension' => 0.4,
                            'fill' => false
                        ],
                        [
                            'label' => 'Ekspor (USD)',
                            'data' => $exports,
                            'borderColor' => 'rgba(16, 185, 129, 1)',
                            'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                            'tension' => 0.4,
                            'fill' => false
                        ],
                        [
                            'label' => 'Impor (USD)',
                            'data' => $imports,
                            'borderColor' => 'rgba(239, 68, 68, 1)',
                            'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                            'tension' => 0.4,
                            'fill' => false
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data ekonomi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get News Distribution
     */
    public function getNewsDistribution(Request $request)
    {
        try {
            $newsByCountry = NewsArticle::with('country:id,name')
                ->selectRaw('country_id, COUNT(*) as count')
                ->groupBy('country_id')
                ->orderBy('count', 'desc')
                ->take(10)
                ->get();

            $labels = $newsByCountry->pluck('country.name')->toArray();
            $counts = $newsByCountry->pluck('count')->toArray();

            // Sentiment distribution
            $positive = NewsArticle::where('sentiment', 'positive')->count();
            $neutral = NewsArticle::where('sentiment', 'neutral')->count();
            $negative = NewsArticle::where('sentiment', 'negative')->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'countryDistribution' => [
                        'labels' => $labels,
                        'datasets' => [[
                            'label' => 'Jumlah Berita',
                            'data' => $counts,
                            'backgroundColor' => [
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(249, 115, 22, 0.8)',
                                'rgba(139, 92, 246, 0.8)',
                                'rgba(236, 72, 153, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(20, 184, 166, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(99, 102, 241, 0.8)',
                                'rgba(168, 85, 247, 0.8)'
                            ],
                            'borderWidth' => 0
                        ]]
                    ],
                    'sentimentDistribution' => [
                        'labels' => ['Positif', 'Netral', 'Negatif'],
                        'datasets' => [[
                            'data' => [$positive, $neutral, $negative],
                            'backgroundColor' => [
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(239, 68, 68, 0.8)'
                            ],
                            'borderWidth' => 0
                        ]]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data berita: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get Risk Score Composition (Stacked Bar)
     */
    public function getRiskComposition(Request $request)
    {
        try {
            $limit = $request->input('limit', 15);
            $data = RiskScore::with('country:id,name')
                ->orderBy('total_score', 'desc')
                ->take($limit)
                ->get();

            $labels = $data->pluck('country.name')->toArray();
            $weatherScores = $data->pluck('weather_score')->toArray();
            $economyScores = $data->pluck('economy_score')->toArray();
            $exchangeScores = $data->pluck('exchange_score')->toArray();
            $newsScores = $data->pluck('news_score')->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'Weather Score',
                            'data' => $weatherScores,
                            'backgroundColor' => 'rgba(59, 130, 246, 0.8)'
                        ],
                        [
                            'label' => 'Economy Score',
                            'data' => $economyScores,
                            'backgroundColor' => 'rgba(16, 185, 129, 0.8)'
                        ],
                        [
                            'label' => 'Exchange Score',
                            'data' => $exchangeScores,
                            'backgroundColor' => 'rgba(245, 158, 11, 0.8)'
                        ],
                        [
                            'label' => 'News Score',
                            'data' => $newsScores,
                            'backgroundColor' => 'rgba(239, 68, 68, 0.8)'
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data komposisi risiko: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get Continent Distribution
     */
    public function getContinentDistribution(Request $request)
    {
        try {
            $continents = Country::selectRaw('continent, COUNT(*) as count')
                ->whereNotNull('continent')
                ->groupBy('continent')
                ->orderBy('count', 'desc')
                ->get();

            $labels = $continents->pluck('continent')->toArray();
            $counts = $continents->pluck('count')->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'datasets' => [[
                        'label' => 'Jumlah Negara',
                        'data' => $counts,
                        'backgroundColor' => [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(249, 115, 22, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(236, 72, 153, 0.8)'
                        ],
                        'borderWidth' => 0
                    ]]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data distribusi benua: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get Port Data
     */
    public function getPortData(Request $request)
    {
        try {
            $limit = $request->input('limit', 15);
            $data = Port::with('country:id,name')
                ->orderBy('country_id')
                ->take($limit)
                ->get();

            // Group by country
            $portsByCountry = $data->groupBy('country.name');
            $labels = $portsByCountry->keys()->toArray();
            $counts = $portsByCountry->map(fn($group) => $group->count())->values()->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'datasets' => [[
                        'label' => 'Jumlah Pelabuhan',
                        'data' => $counts,
                        'backgroundColor' => [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(249, 115, 22, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(236, 72, 153, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(20, 184, 166, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(168, 85, 247, 0.8)',
                            'rgba(132, 204, 22, 0.8)',
                            'rgba(251, 146, 60, 0.8)',
                            'rgba(14, 165, 233, 0.8)',
                            'rgba(244, 63, 94, 0.8)',
                            'rgba(34, 197, 94, 0.8)'
                        ],
                        'borderWidth' => 0
                    ]]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data pelabuhan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get Summary Statistics
     */
    public function getSummaryStats(Request $request)
    {
        try {
            $stats = [
                'totalCountries' => Country::count(),
                'totalWeather' => WeatherLog::count(),
                'totalEconomy' => EconomyData::count(),
                'totalExchange' => ExchangeRate::count(),
                'totalNews' => NewsArticle::count(),
                'totalRisk' => RiskScore::count(),
                'totalPorts' => Port::count(),
                'lastSync' => SyncLog::latest()->first()->created_at ?? null
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data ringkasan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get Map Data (for world map)
     */
    public function getMapData(Request $request)
    {
        try {
            $data = RiskScore::with('country:id,name,latitude,longitude')
                ->get()
                ->map(function ($risk) {
                    return [
                        'country' => $risk->country->name,
                        'latitude' => $risk->country->latitude,
                        'longitude' => $risk->country->longitude,
                        'weatherScore' => $risk->weather_score,
                        'economyScore' => $risk->economy_score,
                        'exchangeScore' => $risk->exchange_score,
                        'newsScore' => $risk->news_score,
                        'totalScore' => $risk->total_score,
                        'riskLevel' => $risk->risk_level
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data peta: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get Summary Table Data
     */
    public function getSummaryTable(Request $request)
    {
        try {
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search', '');
            $continent = $request->input('continent', '');
            $riskLevel = $request->input('risk_level', '');

            $query = RiskScore::with('country:id,name,continent');

            if ($search) {
                $query->whereHas('country', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }

            if ($continent) {
                $query->whereHas('country', function($q) use ($continent) {
                    $q->where('continent', $continent);
                });
            }

            if ($riskLevel) {
                $query->where('risk_level', $riskLevel);
            }

            $total = $query->count();
            $data = $query->orderBy('total_score', 'desc')
                ->offset(($page - 1) * $perPage)
                ->limit($perPage)
                ->get();

            $formattedData = $data->map(function ($risk) {
                return [
                    'country' => $risk->country->name,
                    'continent' => $risk->country->continent,
                    'weatherScore' => $risk->weather_score,
                    'economyScore' => $risk->economy_score,
                    'exchangeScore' => $risk->exchange_score,
                    'newsScore' => $risk->news_score,
                    'totalScore' => $risk->total_score,
                    'riskLevel' => $risk->risk_level,
                    'calculatedAt' => $risk->calculated_at ? $risk->calculated_at->format('d M Y H:i') : '-'
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedData,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data tabel: ' . $e->getMessage()], 500);
        }
    }

// This file is only used as a reference for manual appending

    public function getTrendData(Request $request)
    {
        try {
            $countryId = $request->input('country_id');
            $continent = $request->input('continent');
            $yearStart = (int) $request->input('year_start', 2018);
            $yearEnd   = (int) $request->input('year_end', date('Y'));

            $countryIds = null;
            if ($countryId) {
                $countryIds = [$countryId];
            } elseif ($continent) {
                $countryIds = Country::where('continent', $continent)->pluck('id')->toArray();
            }

            // GDP Trend
            $gdpQ = EconomyData::join('countries', 'economy_data.country_id', '=', 'countries.id')
                ->whereNotNull('economy_data.gdp')
                ->whereBetween('economy_data.year', [$yearStart, $yearEnd]);
            if ($countryIds) $gdpQ->whereIn('economy_data.country_id', $countryIds);
            $gdpRaw = $gdpQ->selectRaw('economy_data.year, SUM(economy_data.gdp) as gdp')
                ->groupBy('economy_data.year')->orderBy('economy_data.year')->get();
            $gdpData = [
                'labels'   => $gdpRaw->pluck('year'),
                'datasets' => [['label'=>'GDP (USD)','data'=>$gdpRaw->pluck('gdp'),'borderColor'=>'rgba(59,130,246,1)','backgroundColor'=>'rgba(59,130,246,0.12)','fill'=>true,'tension'=>0.4,'pointRadius'=>4]],
            ];

            // Inflation Trend
            $inflQ = EconomyData::join('countries', 'economy_data.country_id', '=', 'countries.id')
                ->whereNotNull('economy_data.inflation')
                ->whereBetween('economy_data.year', [$yearStart, $yearEnd]);
            if ($countryIds) $inflQ->whereIn('economy_data.country_id', $countryIds);
            $inflRaw = $inflQ->selectRaw('economy_data.year, AVG(economy_data.inflation) as inflation')
                ->groupBy('economy_data.year')->orderBy('economy_data.year')->get();
            $inflData = [
                'labels'   => $inflRaw->pluck('year'),
                'datasets' => [['label'=>'Inflasi (%)','data'=>$inflRaw->pluck('inflation')->map(fn($v)=>round($v,2)),'borderColor'=>'rgba(239,68,68,1)','backgroundColor'=>'rgba(239,68,68,0.12)','fill'=>true,'tension'=>0.4,'pointRadius'=>4]],
            ];

            // Currency Trend
            $exQ = ExchangeRate::join('countries', 'exchange_rates.country_id', '=', 'countries.id')
                ->whereNotNull('exchange_rates.exchange_rate')
                ->whereYear('exchange_rates.recorded_at', '>=', $yearStart)
                ->whereYear('exchange_rates.recorded_at', '<=', $yearEnd);
            if ($countryIds) $exQ->whereIn('exchange_rates.country_id', $countryIds);
            $exRaw = $exQ->selectRaw("DATE_FORMAT(exchange_rates.recorded_at, '%Y-%m') as period, AVG(exchange_rates.exchange_rate) as rate")
                ->groupByRaw("DATE_FORMAT(exchange_rates.recorded_at, '%Y-%m')")->orderBy('period')->get();
            $currData = [
                'labels'   => $exRaw->pluck('period'),
                'datasets' => [['label'=>'Nilai Tukar (avg)','data'=>$exRaw->pluck('rate')->map(fn($v)=>round($v,4)),'borderColor'=>'rgba(16,185,129,1)','backgroundColor'=>'rgba(16,185,129,0.12)','fill'=>true,'tension'=>0.4,'pointRadius'=>3]],
            ];

            // Risk Trend
            $riskQ = RiskScore::join('countries', 'risk_scores.country_id', '=', 'countries.id')
                ->whereYear('risk_scores.calculated_at', '>=', $yearStart)
                ->whereYear('risk_scores.calculated_at', '<=', $yearEnd);
            if ($countryIds) $riskQ->whereIn('risk_scores.country_id', $countryIds);
            $riskRaw = $riskQ->selectRaw("DATE_FORMAT(risk_scores.calculated_at, '%Y-%m') as period, AVG(risk_scores.weather_score) as weather, AVG(risk_scores.economy_score) as economy, AVG(risk_scores.exchange_score) as exchange, AVG(risk_scores.news_score) as news, AVG(risk_scores.total_score) as total")
                ->groupByRaw("DATE_FORMAT(risk_scores.calculated_at, '%Y-%m')")->orderBy('period')->get();
            $r = fn($v) => round($v, 2);
            $riskData = [
                'labels'   => $riskRaw->pluck('period'),
                'datasets' => [
                    ['label'=>'Weather Score','data'=>$riskRaw->pluck('weather')->map($r),'borderColor'=>'rgba(14,165,233,1)','backgroundColor'=>'rgba(14,165,233,0.05)','fill'=>false,'tension'=>0.4,'pointRadius'=>2],
                    ['label'=>'Economy Score','data'=>$riskRaw->pluck('economy')->map($r),'borderColor'=>'rgba(16,185,129,1)','backgroundColor'=>'rgba(16,185,129,0.05)','fill'=>false,'tension'=>0.4,'pointRadius'=>2],
                    ['label'=>'Exchange Score','data'=>$riskRaw->pluck('exchange')->map($r),'borderColor'=>'rgba(139,92,246,1)','backgroundColor'=>'rgba(139,92,246,0.05)','fill'=>false,'tension'=>0.4,'pointRadius'=>2],
                    ['label'=>'News Score','data'=>$riskRaw->pluck('news')->map($r),'borderColor'=>'rgba(249,115,22,1)','backgroundColor'=>'rgba(249,115,22,0.05)','fill'=>false,'tension'=>0.4,'pointRadius'=>2],
                    ['label'=>'Total Risk','data'=>$riskRaw->pluck('total')->map($r),'borderColor'=>'rgba(239,68,68,1)','backgroundColor'=>'rgba(239,68,68,0.12)','fill'=>true,'tension'=>0.4,'pointRadius'=>3,'borderWidth'=>2],
                ],
            ];

            return response()->json(['success'=>true,'data'=>['gdp'=>$gdpData,'inflation'=>$inflData,'currency'=>$currData,'risk'=>$riskData]]);
        } catch (\Exception $e) {
            return response()->json(['success'=>false,'message'=>$e->getMessage()], 500);
        }
    }

    public function getTrendSummaryTable(Request $request)
    {
        try {
            $search    = $request->input('search', '');
            $sortBy    = $request->input('sort_by', 'total_score');
            $sortDir   = $request->input('sort_dir', 'desc');
            $page      = max(1, (int) $request->input('page', 1));
            $perPage   = (int) $request->input('per_page', 15);
            $continent = $request->input('continent');
            $countryId = $request->input('country_id');
            $yearStart = (int) $request->input('year_start', 2018);
            $yearEnd   = (int) $request->input('year_end', date('Y'));

            $allowed = ['country','weather_score','economy_score','exchange_score','news_score','total_score','risk_level'];
            if (!in_array($sortBy, $allowed)) $sortBy = 'total_score';
            if (!in_array($sortDir, ['asc','desc'])) $sortDir = 'desc';

            $query = RiskScore::join('countries', 'risk_scores.country_id', '=', 'countries.id')
                ->select('countries.id as country_id','countries.name as country','countries.continent','risk_scores.weather_score','risk_scores.economy_score','risk_scores.exchange_score','risk_scores.news_score','risk_scores.total_score','risk_scores.risk_level');

            if ($search)    $query->where('countries.name', 'like', "%{$search}%");
            if ($continent) $query->where('countries.continent', $continent);
            if ($countryId) $query->where('countries.id', $countryId);
            $query->orderBy($sortBy === 'country' ? 'countries.name' : $sortBy, $sortDir);

            $total   = $query->count();
            $records = $query->forPage($page, $perPage)->get();

            $result = $records->map(function ($row) use ($yearStart, $yearEnd) {
                $economy  = EconomyData::where('country_id', $row->country_id)
                    ->whereBetween('year', [$yearStart, $yearEnd])->orderByDesc('year')->first();
                $exchange = ExchangeRate::where('country_id', $row->country_id)
                    ->whereYear('recorded_at', '>=', $yearStart)->whereYear('recorded_at', '<=', $yearEnd)
                    ->latest('recorded_at')->first();
                return [
                    'country'        => $row->country,
                    'continent'      => $row->continent ?? '-',
                    'gdp'            => $economy?->gdp       ? number_format($economy->gdp/1e9,2).' B'      : '-',
                    'inflation'      => $economy?->inflation  ? number_format($economy->inflation,2).'%'    : '-',
                    'exchange_rate'  => $exchange?->exchange_rate ? number_format($exchange->exchange_rate,4) : '-',
                    'weather_score'  => round($row->weather_score,2),
                    'economy_score'  => round($row->economy_score,2),
                    'exchange_score' => round($row->exchange_score,2),
                    'news_score'     => round($row->news_score,2),
                    'total_score'    => round($row->total_score,2),
                    'risk_level'     => $row->risk_level,
                ];
            });

            return response()->json(['success'=>true,'data'=>$result,'total'=>$total,'page'=>$page,'per_page'=>$perPage,'last_page'=>(int)ceil($total/$perPage)]);
        } catch (\Exception $e) {
            return response()->json(['success'=>false,'message'=>$e->getMessage()], 500);
        }
    }

}
