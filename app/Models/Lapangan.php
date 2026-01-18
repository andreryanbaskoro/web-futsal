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
        'rating',
        'rating_count',
    ];

    public $timestamps = true;

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
}
