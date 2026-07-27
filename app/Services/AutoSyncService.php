<?php

namespace App\Services;

use App\Models\SyncLog;
use App\Jobs\CountrySyncJob;
use App\Jobs\WeatherSyncJob;
use App\Jobs\ExchangeRateSyncJob;
use App\Jobs\EconomySyncJob;
use App\Jobs\RiskSyncJob;
use App\Jobs\NewsSyncJob;
use App\Jobs\PortSyncJob;
use Carbon\Carbon;

class AutoSyncService
{
    /**
     * Batas waktu kadaluarsa data (TTL) dalam satuan jam untuk setiap modul
     */
    protected static array $ttlHours = [
        'Countries'     => 48,  // 2 hari
        'Weather'       => 2,   // 2 jam
        'News'          => 3,   // 3 jam
        'Exchange Rate' => 6,   // 6 jam
        'Risk'          => 12,  // 12 jam
        'Economy'       => 24,  // 24 jam
        'Ports'         => 48,  // 2 hari
    ];

    /**
     * Memeriksa apakah tabel data modul masih kosong.
     * HANYA jika tabel kosong, jalankan sync agar HTTP request tidak melambat/freeze.
     */
    public static function checkAndSync(string $module): void
    {
        try {
            $isEmpty = match ($module) {
                'Countries'     => \App\Models\Country::count() === 0,
                'Weather'       => \App\Models\WeatherLog::count() === 0,
                'Exchange Rate' => \App\Models\ExchangeRate::count() === 0,
                'Economy'       => \App\Models\EconomyData::count() === 0,
                'Risk'          => \App\Models\RiskScore::count() === 0,
                'News'          => \App\Models\NewsArticle::count() === 0,
                'Ports'         => \App\Models\Port::count() === 0,
                default         => false,
            };

            if ($isEmpty) {
                match ($module) {
                    'Countries'     => CountrySyncJob::dispatchSync(),
                    'Weather'       => WeatherSyncJob::dispatchSync(),
                    'Exchange Rate' => ExchangeRateSyncJob::dispatchSync(),
                    'Economy'       => EconomySyncJob::dispatchSync(),
                    'Risk'          => RiskSyncJob::dispatchSync(),
                    'News'          => NewsSyncJob::dispatchSync(),
                    'Ports'         => PortSyncJob::dispatchSync(),
                    default         => null,
                };
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
