<?php

namespace App\Http\Controllers;

use App\Repositories\ExchangeRateRepository;

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

    return view(
        'exchange.index',
        compact(
            'exchangeRates',
            'totalExchange',
            'averageRate',
            'currencyCount',
            'lastUpdate',
            'topExchangeRates'
        )
    );
}
}