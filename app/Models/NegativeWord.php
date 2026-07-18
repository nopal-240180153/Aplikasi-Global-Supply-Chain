<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NegativeWord extends Model
{
    use HasFactory;

    protected $fillable = [
        'word',
        'weight',
        'category',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
    ];

    /**
     * Get all words as array
     */
    public static function getAllWords(): array
    {
        return static::pluck('word')->toArray();
    }

    /**
     * Get words with weights
     */
    public static function getWordsWithWeights(): array
    {
        return static::pluck('weight', 'word')->toArray();
    }
}
