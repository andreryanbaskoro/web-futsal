<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    // 🔹 Nama tabel
    protected $table = 'lapangan';

    // 🔹 Primary key
    protected $primaryKey = 'id_lapangan';

    // Primary key string, bukan auto-increment integer
    public $incrementing = false;
    protected $keyType = 'string';

    // 🔹 Kolom yang boleh diisi
    protected $fillable = [
        'nama_lapangan',
        'deskripsi',
        'status',
        'dimensi',
        'kapasitas',
        'rating',
        'rating_count',
    ];


    // 🔹 Timestamp aktif
    public $timestamps = true;

    /* =======================
     * RELASI
     * =======================
     */

    // 1 lapangan punya banyak jadwal
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_lapangan', 'id_lapangan');
    }

    // 1 lapangan punya banyak pemesanan
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_lapangan', 'id_lapangan');
    }

    // 1 lapangan punya banyak jam_operasional
    public function jamOperasional()
    {
        return $this->hasMany(JamOperasional::class, 'id_lapangan', 'id_lapangan');
    }

    /* =======================
     * SCOPE (OPSIONAL)
     * =======================
     */

    // Ambil lapangan yang aktif saja
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_lapangan', 'id_lapangan');
    }


    /* =======================
     * EVENT MODEL
     * =======================
     */

    // Generate ID sebelum insert
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lapangan) {
            if (empty($lapangan->id_lapangan)) {
                $last = self::orderBy('id_lapangan', 'desc')->first();

                $lastNumber = $last ? (int) substr($last->id_lapangan, 4) : 0;

                $lapangan->id_lapangan = 'LPG-' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
            }
        });
    }

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
