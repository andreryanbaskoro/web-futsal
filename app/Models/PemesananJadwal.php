<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananJadwal extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'pemesanan_jadwal';

    // Primary key
    protected $primaryKey = 'id_pemesanan_jadwal';

    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang bisa diisi
    protected $fillable = [
        'id_pemesanan',
        'id_jadwal',
        'harga',
        'durasi_menit',
    ];

    public $timestamps = true;

    /* =======================
     * RELASI
     * ======================= */

    /**
     * Relasi ke Pemesanan
     */
    public function pemesanan()
    {
        return $this->belongsTo(
            Pemesanan::class,
            'id_pemesanan',
            'id_pemesanan'
        );
    }

    /**
     * Relasi ke Jadwal
     */
    public function jadwal()
    {
        return $this->belongsTo(
            Jadwal::class,
            'id_jadwal',
            'id_jadwal'
        );
    }
}
