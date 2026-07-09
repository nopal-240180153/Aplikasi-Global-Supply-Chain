<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    protected $fillable = [

        'module',

        'status',

        'total_data',

        'updated_data',

        'failed_data',

        'duration',

        'message',

        'started_at',

        'finished_at',

    ];

    protected $casts = [

        'started_at' => 'datetime',

        'finished_at' => 'datetime',

    ];
}