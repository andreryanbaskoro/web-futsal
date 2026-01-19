<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';

    // BIGINT AUTO_INCREMENT
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pengguna',
        'id_lapangan',
        'kode_pemesanan',
        'total_bayar',
        'status_pemesanan',
        'waktu_bayar',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'waktu_bayar' => 'datetime',
    ];

    const PENDING   = 'pending';
    const DIBAYAR   = 'dibayar';
    const DIBATALKAN = 'dibatalkan';
    const KADALUARSA = 'kadaluarsa';


    /* =======================
     * RELASI
     * ======================= */

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan', 'id_lapangan');
    }

    public function detailJadwal()
    {
        return $this->hasMany(PemesananJadwal::class, 'id_pemesanan');
    }
    




    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_pemesanan', 'id_pemesanan');
    }

    /* =======================
     * SCOPE
     * ======================= */

    public function scopeAktif($q)
    {
        return $q->whereIn('status_pemesanan', ['pending', 'dibayar']);
    }

    /* =======================
     * HELPER
     * ======================= */

    public function isPaid(): bool
    {
        return $this->status_pemesanan === self::DIBAYAR; // Use constant here for consistency
    }
}
