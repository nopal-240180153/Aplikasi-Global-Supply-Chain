<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [

        'uuid',

        'name',
        'official_name',

        'iso2',
        'iso3',

        'capital',

        'region',
        'subregion',
        'continent',

        'currency_code',
        'currency_name',

        'language',

        'flag',

        'latitude',
        'longitude',

        'population',

        'risk_score',
        'risk_level',

        'last_synced_at',

    ];

    public function economyData()
    {
        return $this->hasMany(EconomyData::class);
    }

    public function weatherLogs()
    {
        return $this->hasMany(WeatherLog::class);
    }

    public function exchangeRates()
    {
        return $this->hasMany(ExchangeRate::class);
    }
}