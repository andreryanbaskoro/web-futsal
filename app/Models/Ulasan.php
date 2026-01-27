<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    // Menentukan nama tabel yang digunakan
    protected $table = 'ulasan';

    // Menentukan primary key tabel
    protected $primaryKey = 'id_ulasan';

    // Auto increment, sehingga tidak perlu diset false
    public $incrementing = true;

    // Tipe key menggunakan BIGINT
    protected $keyType = 'int'; // Menggunakan tipe data integer untuk id_ulasan

    // Kolom yang bisa diisi
    protected $fillable = [
        'id_pemesanan',
        'id_pengguna',
        'id_lapangan',
        'rating',
        'komentar',
    ];


    /* =======================
     * RELASI
     * ======================= */

    // Relasi ke model Lapangan (1:N)
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan', 'id_lapangan');
    }

    // Relasi ke model Pengguna (1:N)
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }


    /* =======================
     * EVENT MODEL
     * ======================= */
    protected static function boot()
    {
        parent::boot();

        // 🔥 AUTO UPDATE RATING
        static::created(function ($ulasan) {
            $ulasan->lapangan?->refreshRating();
        });

        static::updated(function ($ulasan) {
            $ulasan->lapangan?->refreshRating();
        });

        static::deleted(function ($ulasan) {
            $ulasan->lapangan?->refreshRating();
        });
    }
}
