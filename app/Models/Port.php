<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    protected $fillable = [

        'country_id',
        'port_name',
        'port_code',
        'city',
        'latitude',
        'longitude',
        'type'

    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}