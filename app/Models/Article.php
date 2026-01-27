<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $table = 'articles';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'author',
        'tanggal_post',
        'waktu_baca',
        'featured_image',
        'konten',
        'tags',
    ];

    protected $appends = ['featured_image_url'];


    /* =======================
     * RELASI (Opsional)
     * ======================= */
    // Jika nanti ada tabel related articles
    public function related()
    {
        return $this->belongsToMany(
            Article::class,
            'article_related',
            'article_id',
            'related_article_id'
        );
    }

    /* =======================
     * EVENT MODEL
     * ======================= */
    protected static function boot()
    {
        parent::boot();

        // 🔹 Generate slug otomatis dari judul jika kosong
        static::creating(function ($article) {
            if (empty($article->slug) && !empty($article->judul)) {
                $slug = Str::slug($article->judul);
                $count = self::where('slug', 'like', $slug . '%')->count();
                $article->slug = $count ? $slug . '-' . ($count + 1) : $slug;
            }
        });
    }

    /* =======================
     * CASTS
     * ======================= */
    protected $casts = [
        'tags' => 'array',          // otomatis konversi JSON ke array
        'tanggal_post' => 'date',   // format tanggal
    ];

    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image) {
            return asset('images/no-image.png'); // opsional fallback
        }

        return Str::startsWith($this->featured_image, ['http://', 'https://'])
            ? $this->featured_image
            : asset('storage/' . $this->featured_image);
    }
}
