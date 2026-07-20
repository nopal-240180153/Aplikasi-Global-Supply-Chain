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
        'title',
        'slug',
        'excerpt',
        'content',
        'image_url',
        'status',
        'category',
        'tags',
        'views',
        'published_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    /**
     * Boot model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    /**
     * Relasi ke User (Penulis)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function user()
    {
        return $this->author();
    }

    /**
     * Scope: hanya artikel yang sudah dipublikasikan
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    /**
     * Scope: Draft articles
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope: Archived articles
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Tambah jumlah views
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Get excerpt or generate from content
     */
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        return Str::limit(strip_tags($this->content), 200);
    }

    /**
     * Get reading time in minutes
     */
    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200); // Average reading speed
        return $minutes;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'published' => 'success',
            'draft' => 'warning',
            'archived' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'published' => 'Diterbitkan',
            'draft' => 'Draft',
            'archived' => 'Diarsipkan',
            default => 'Unknown',
        };
    }
}
