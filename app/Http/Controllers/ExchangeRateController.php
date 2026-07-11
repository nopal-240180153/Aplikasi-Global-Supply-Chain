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
    $exchangeRates = $this->repository->paginate(50);

    $totalExchange = $this->repository->count();

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