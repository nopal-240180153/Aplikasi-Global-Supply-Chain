<?php

namespace App\Http\Controllers;

use App\Jobs\CountrySyncJob;
use App\Jobs\WeatherSyncJob;
use App\Jobs\ExchangeRateSyncJob;
use App\Models\SyncLog;
use App\Jobs\EconomySyncJob;

class SyncController extends Controller
{
    /**
     * Halaman Sinkronisasi
     */
    public function index()
    {
        $countrySync = SyncLog::where('module', 'Countries')
            ->latest()
            ->first();

        $weatherSync = SyncLog::where('module', 'Weather')
            ->latest()
            ->first();

        $exchangeRateSync = SyncLog::where('module', 'Exchange Rate')
            ->latest()
            ->first();
        
        $economySync = SyncLog::where('module', 'Economy')
    ->latest()
    ->first();

        return view('admin.sync', compact(
            'countrySync',
            'weatherSync',
            'exchangeRateSync',
            'economySync'
        ));
    }

    /**
     * Sinkronisasi Negara
     */
    public function countries()
    {
        CountrySyncJob::dispatch();

        return redirect()
            ->route('admin.sync')
            ->with(
                'success',
                'Sinkronisasi data negara sedang diproses di background.'
            );
    }

    /**
     * Sinkronisasi Cuaca
     */
    public function weather()
    {
        WeatherSyncJob::dispatch();

        return redirect()
            ->route('admin.sync')
            ->with(
                'success',
                'Sinkronisasi data cuaca sedang diproses di background.'
            );
    }

    /**
     * Sinkronisasi Exchange Rate
     */
    public function exchangeRate()
    {
        ExchangeRateSyncJob::dispatch();

        return redirect()
            ->route('admin.sync')
            ->with(
                'success',
                'Sinkronisasi Exchange Rate sedang diproses di background.'
            );
    }

    /**
     * Sinkronisasi Data Ekonomi
     */
    public function economy()
    {
        EconomySyncJob::dispatch();

        return redirect()
            ->route('admin.sync')
            ->with(
                'success',
                'Sinkronisasi data ekonomi sedang diproses di background.'
            );
    }
}