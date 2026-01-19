<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';

    // BIGINT AUTO_INCREMENT
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_lapangan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
    ];

    /* =======================
     * RELASI
     * ======================= */

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan');
    }


    public function pemesananJadwal()
    {
        return $this->hasOne(PemesananJadwal::class, 'id_jadwal', 'id_jadwal');
    }

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }


    /* =======================
     * HELPER
     * ======================= */

    public function isBooked(): bool
    {
        return $this->pemesananJadwal()->exists();
    }
}
