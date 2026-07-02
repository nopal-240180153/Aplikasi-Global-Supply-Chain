<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherLog extends Model
{
    protected $fillable = [

        'country_id',
        'temperature',
        'rainfall',
        'wind_speed',
        'storm_risk',
        'weather_condition',
        'recorded_at'

    ];

    protected $casts = [

        'temperature'=>'float',
        'rainfall'=>'float',
        'wind_speed'=>'float',
        'storm_risk'=>'float',
        'recorded_at'=>'datetime',

    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}