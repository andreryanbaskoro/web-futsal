<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananJadwal extends Model
{
    use HasFactory;

    protected $table = 'pemesanan_jadwal';
    protected $primaryKey = 'id_pemesanan_jadwal';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pemesanan',
        'id_jadwal',
        'tanggal',        // ⬅️ TAMBAH
        'jam_mulai',      // ⬅️ TAMBAH
        'jam_selesai',    // ⬅️ TAMBAH
        'harga',
        'durasi_menit',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'jam_mulai'   => 'string',
        'jam_selesai' => 'string',
    ];

    public $timestamps = true;

    /* =======================
     * RELASI
     * ======================= */

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
