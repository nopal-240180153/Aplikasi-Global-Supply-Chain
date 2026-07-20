<?php

namespace App\Http\Controllers;

use App\Repositories\ExchangeRateRepository;
use Illuminate\Support\Facades\DB;

class ExchangeRateController extends Controller
{
    protected ExchangeRateRepository $repository;

    public function __construct(
        ExchangeRateRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Halaman Monitoring Exchange Rate
     */
   public function index()
{
    $query = \App\Models\ExchangeRate::with('country')
        ->orderByDesc('recorded_at');

    if (request()->filled('search')) {
        $search = request('search');
        $query->whereHas('country', function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        });
    }

    if (request()->filled('currency')) {
        $query->where('target_currency', request('currency'));
    }

    $exchangeRates = $query->paginate(50)->withQueryString();

    $totalExchange = \App\Models\ExchangeRate::count();

    $averageRate = \App\Models\ExchangeRate::avg('exchange_rate');

    $currencyCount = \App\Models\ExchangeRate::distinct('target_currency')->count();

    $lastUpdate = \App\Models\ExchangeRate::max('recorded_at');

    $topExchangeRates = $this->repository->topExchangeRates();

    // Data untuk grafik baru
    $currencyDistribution = \App\Models\ExchangeRate::select('target_currency', \DB::raw('count(*) as count'))
        ->groupBy('target_currency')
        ->orderByDesc('count')
        ->take(10)
        ->get();

    $exchangeRateRanges = \App\Models\ExchangeRate::select(
        \DB::raw('CASE 
            WHEN exchange_rate < 1 THEN "< 1"
            WHEN exchange_rate < 10 THEN "1-10"
            WHEN exchange_rate < 100 THEN "10-100"
            WHEN exchange_rate < 1000 THEN "100-1000"
            ELSE "> 1000"
        END as rate_range'),
        \DB::raw('count(*) as count')
    )
    ->groupBy('rate_range')
    ->get();

    return view(
        'exchange.index',
        compact(
            'exchangeRates',
            'totalExchange',
            'averageRate',
            'currencyCount',
            'lastUpdate',
            'topExchangeRates',
            'currencyDistribution',
            'exchangeRateRanges'
        )
    );
}
}