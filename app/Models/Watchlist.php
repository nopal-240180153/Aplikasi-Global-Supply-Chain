<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Watchlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'country_id',
        'catatan',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Negara
     */
    public function negara()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    
    public function country()
    {
        return $this->negara();
    }
}
