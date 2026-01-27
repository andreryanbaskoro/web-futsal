<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $table = 'lapangan';
    protected $primaryKey = 'id_lapangan';

    // BIGINT AUTO_INCREMENT
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_lapangan',
        'deskripsi',
        'status',
        'dimensi',
        'kapasitas',
        'image_type',
        'image',
        'rating',
        'rating_count',
    ];


    public $timestamps = true;

    protected $appends = ['image_url'];


    /* =======================
     * RELASI
     * ======================= */

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_lapangan', 'id_lapangan');
    }

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'id_lapangan', 'id_lapangan');
    }

    public function jamOperasional()
    {
        return $this->hasMany(JamOperasional::class, 'id_lapangan', 'id_lapangan');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_lapangan', 'id_lapangan');
    }

    /* =======================
     * SCOPE
     * ======================= */

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /* =======================
     * HELPER
     * ======================= */

    public function refreshRating()
    {
        $data = $this->ulasan()
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total')
            ->first();

        $this->update([
            'rating' => round($data->avg_rating ?? 0, 1),
            'rating_count' => $data->total ?? 0,
        ]);
    }

    public function getImageUrlAttribute()
    {
        // Kalau image kosong
        if (empty($this->image)) {
            return $this->defaultImage();
        }

        // Kalau image dari URL
        if ($this->image_type === 'url') {
            return $this->image;
        }

        // Kalau image hasil upload
        if ($this->image_type === 'upload') {
            return asset('storage/' . $this->image);
        }

        return $this->defaultImage();
    }

    protected function defaultImage()
    {
        return 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=400&q=80';
    }
}
