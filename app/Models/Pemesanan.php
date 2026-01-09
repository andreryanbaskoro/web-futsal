<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    // 🔹 Nama tabel
    protected $table = 'pemesanan';

    // 🔹 Primary key
    protected $primaryKey = 'id_pemesanan';

    public $incrementing = true;

    protected $keyType = 'int';

    // 🔹 Kolom yang boleh diisi
    protected $fillable = [
        'id_pengguna',
        'id_lapangan',
        'id_jadwal',
        'kode_pemesanan',
        'total_bayar',
        'status_pemesanan',
    ];

    public $timestamps = true;

    /* =======================
     * RELASI
     * =======================
     */

    // Pemesanan milik satu pengguna
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }

    // Pemesanan milik satu lapangan
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan', 'id_lapangan');
    }

    // Pemesanan milik satu jadwal
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    // Satu pemesanan punya satu pembayaran
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pemesanan', 'id_pemesanan');
    }

    /* =======================
     * SCOPE
     * =======================
     */

    // Pemesanan yang masih aktif
    public function scopeAktif($query)
    {
        return $query->whereIn('status_pemesanan', ['pending', 'dibayar']);
    }

    /* =======================
     * HELPER STATUS
     * =======================
     */

    public function isPending()
    {
        return $this->status_pemesanan === 'pending';
    }

    public function isDibayar()
    {
        return $this->status_pemesanan === 'dibayar';
    }

    public function isDibatalkan()
    {
        return $this->status_pemesanan === 'dibatalkan';
    }

    public function isKadaluarsa()
    {
        return $this->status_pemesanan === 'kadaluarsa';
    }
}
