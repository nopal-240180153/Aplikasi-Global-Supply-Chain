<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EconomyData extends Model
{
    protected $table = 'economy_data';

    protected $fillable = [

        'country_id',
        'year',
        'gdp',
        'inflation',
        'population',
        'exports',
        'imports'

    ];

    protected $casts = [

        'gdp' => 'float',
        'inflation' => 'float',
        'exports' => 'float',
        'imports' => 'float',

    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}