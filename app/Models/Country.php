<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [

        'name',
        'official_name',
        'iso2',
        'iso3',
        'capital',
        'region',
        'subregion',
        'currency_code',
        'currency_name',
        'flag',
        'latitude',
        'longitude',
        'population'

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