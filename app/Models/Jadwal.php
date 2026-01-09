<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    // 🔹 Nama tabel
    protected $table = 'jadwal';

    // 🔹 Primary key
    protected $primaryKey = 'id_jadwal';

    public $incrementing = true;

    protected $keyType = 'int';

    // 🔹 Kolom yang boleh diisi
    protected $fillable = [
        'id_lapangan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
    ];

    public $timestamps = true;

    /* =======================
     * RELASI
     * =======================
     */

    // Jadwal milik satu lapangan
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan', 'id_lapangan');
    }

    // Satu jadwal hanya boleh satu pemesanan
    public function pemesanan()
    {
        return $this->hasOne(Pemesanan::class, 'id_jadwal', 'id_jadwal');
    }

    /* =======================
     * HELPER
     * =======================
     */

    // Cek apakah jadwal sudah dibooking
    public function isTersedia()
    {
        return !$this->pemesanan()
            ->whereIn('status_pemesanan', ['pending','dibayar'])
            ->exists();
    }

    /* =======================
     * SCOPE
     * =======================
     */

    // Ambil jadwal yang masih tersedia
    public function scopeTersedia($query)
    {
        return $query->whereDoesntHave('pemesanan', function ($q) {
            $q->whereIn('status_pemesanan', ['pending','dibayar']);
        });
    }
}
