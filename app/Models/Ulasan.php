<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasan';
    protected $primaryKey = 'id_ulasan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_lapangan',
        'id_pengguna',
        'rating',
        'komentar',
    ];

    /* =======================
     * RELASI
     * ======================= */
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan', 'id_lapangan');
    }

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

        // 🔹 Generate ID: ULS-LPG-01-0001
        static::creating(function ($ulasan) {
            $lapangan = $ulasan->id_lapangan;

            $last = self::where('id_lapangan', $lapangan)
                ->orderBy('id_ulasan', 'desc')
                ->first();

            $lastNumber = $last
                ? (int) substr($last->id_ulasan, -4)
                : 0;

            $ulasan->id_ulasan =
                'ULS-' . $lapangan . '-' .
                str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        });

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
