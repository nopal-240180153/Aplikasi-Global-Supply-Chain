<?php

namespace App\Http\Controllers;

use App\Jobs\CountrySyncJob;
use App\Jobs\WeatherSyncJob;
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

        return view('admin.sync', compact(
            'countrySync',
            'weatherSync'
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
}