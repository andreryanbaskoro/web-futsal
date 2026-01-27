<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Gallery extends Model
{
    /* =======================
     * KONFIGURASI DASAR
     * ======================= */ 
    protected $table = 'galleries';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    /* =======================
     * MASS ASSIGNMENT
     * ======================= */
    protected $fillable = [
        'title',
        'description',
        'category', //lapangan | fasilitas | aktivitas | event
        'image',
        'is_active',
    ];

    /* =======================
     * CASTS
     * ======================= */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /* =======================
     * QUERY SCOPE (OPSIONAL)
     * ======================= */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /* =======================
     * ACCESSOR (OPSIONAL)
     * ======================= */
    public function getImageUrlAttribute()
    {
        // Jika image sudah URL
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        // Jika dari storage
        return asset('storage/' . $this->image);
    }
}
