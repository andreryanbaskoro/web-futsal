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

    public $incrementing = true;

    protected $keyType = 'int';

    // 🔹 Kolom yang boleh diisi
    protected $fillable = [
        'nama_lapangan',
        'deskripsi',
        'harga_per_jam',
        'status',
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

    /* =======================
     * SCOPE (OPSIONAL)
     * =======================
     */

    // Ambil lapangan yang aktif saja
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
