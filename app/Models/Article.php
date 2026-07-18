<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'url_gambar',
        'status',
        'kategori',
        'tags',
        'jumlah_views',
        'tanggal_publikasi',
    ];

    protected $casts = [
        'tags' => 'array',
        'tanggal_publikasi' => 'datetime',
    ];

    /**
     * Boot model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($artikel) {
            if (empty($artikel->slug)) {
                $artikel->slug = Str::slug($artikel->judul);
            }
        });
    }

    /**
     * Relasi ke User (Penulis)
     */
    public function penulis()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function user()
    {
        return $this->penulis();
    }

    /**
     * Scope: hanya artikel yang sudah dipublikasikan
     */
    public function scopeTerbit($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('tanggal_publikasi')
                     ->where('tanggal_publikasi', '<=', now());
    }

    /**
     * Tambah jumlah views
     */
    public function tambahViews()
    {
        $this->increment('jumlah_views');
    }
}
