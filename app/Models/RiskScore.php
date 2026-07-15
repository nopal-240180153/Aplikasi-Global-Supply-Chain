<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskScore extends Model
{
    protected $table = 'risk_scores';

    protected $fillable = [

        'country_id',

        'weather_score',

        'economy_score',

        'exchange_score',

        'news_score',

        'total_score',

        'risk_level',

        'calculated_at',

    ];

    protected $casts = [

        'weather_score' => 'float',

        'economy_score' => 'float',

        'exchange_score' => 'float',

        'news_score' => 'float',

        'total_score' => 'float',

        'calculated_at' => 'datetime',

    ];

    /**
     * Relasi ke Negara
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}