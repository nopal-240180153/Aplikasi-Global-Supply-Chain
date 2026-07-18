<?php

namespace App\Http\Controllers;

use App\Jobs\CountrySyncJob;
use App\Jobs\WeatherSyncJob;
use App\Jobs\ExchangeRateSyncJob;
use App\Jobs\EconomySyncJob;
use App\Jobs\RiskSyncJob;
use App\Jobs\NewsSyncJob;
use App\Jobs\PortSyncJob;
use App\Models\SyncLog;

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

        $riskSync = SyncLog::where('module', 'Risk')
            ->latest()
            ->first();

        $newsSync = SyncLog::where('module', 'News')
            ->latest()
            ->first();

        $portSync = SyncLog::where('module', 'Ports')
            ->latest()
            ->first();

        return view('admin.sync', compact(
            'countrySync',
            'weatherSync',
            'exchangeRateSync',
            'economySync',
            'riskSync',
            'newsSync',
            'portSync'
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

    /**
     * Sinkronisasi Analisis Risiko
     */
    public function risk()
    {
        RiskSyncJob::dispatch();

        return redirect()
            ->route('admin.sync')
            ->with(
                'success',
                'Sinkronisasi Analisis Risiko sedang diproses di background.'
            );
    }

    /**
     * Sinkronisasi Berita
     */
    public function news()
    {
        NewsSyncJob::dispatch();

        return redirect()
            ->route('admin.sync')
            ->with(
                'success',
                'Sinkronisasi berita sedang diproses di background.'
            );
    }

    /**
     * Sinkronisasi Pelabuhan
     */
    public function ports()
    {
        PortSyncJob::dispatch();

        return redirect()
            ->route('admin.sync')
            ->with(
                'success',
                'Sinkronisasi data pelabuhan sedang diproses di background.'
            );
    }
}