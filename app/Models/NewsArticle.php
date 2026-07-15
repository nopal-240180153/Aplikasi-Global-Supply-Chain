<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsArticle extends Model
{
    use HasFactory;

    protected $fillable = [

        'country_id',

        'title',

        'source',

        'url',

        'summary',

        'sentiment',

        'sentiment_score',

        'published_at'

    ];

    protected $casts = [

        'published_at' => 'datetime',

        'sentiment_score' => 'decimal:2'

    ];

    /**
     * Relasi ke Negara
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}