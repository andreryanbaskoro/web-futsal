<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Jadwal extends Model
{
    use HasFactory;

    // 🔹 Nama tabel
    protected $table = 'jadwal';

    // 🔹 Primary key
    protected $primaryKey = 'id_jadwal';

    public $incrementing = false; // Set to false since we're using a custom ID
    protected $keyType = 'string'; // Make sure the key type is string

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

    public function pemesananJadwal()
    {
        return $this->hasOne(
            PemesananJadwal::class,
            'id_jadwal',
            'id_jadwal'
        );
    }


    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan', 'id_lapangan');
    }

    public function pemesanan()
    {
        return $this->hasOne(Pemesanan::class, 'id_jadwal', 'id_jadwal');
    }

    /* =======================
     * HELPER
     * =======================
     */

    public function isTersedia()
    {
        return !$this->pemesananJadwal()
            ->whereHas('pemesanan', function ($q) {
                $q->whereIn('status_pemesanan', ['pending', 'dibayar']);
            })
            ->exists();
    }


    /* =======================
     * SCOPE
     * =======================
     */

    public function scopeTersedia($query)
    {
        return $query->whereDoesntHave('pemesanan', function ($q) {
            $q->whereIn('status_pemesanan', ['pending', 'dibayar']);
        });
    }

    /* =======================
     * EVENT MODEL
     * =======================
     */

    // Generate ID before insert
    protected static function booted()
    {
        static::creating(function ($jadwal) {

            if ($jadwal->id_jadwal) return;

            // 1️⃣ Pakai tanggal jadwal (BUKAN now)
            $datePart = Carbon::parse($jadwal->tanggal)->format('Ymd');

            // 2️⃣ Ambil lapangan
            $lapangan = Lapangan::findOrFail($jadwal->id_lapangan);
            $lapanganCode = $lapangan->id_lapangan; // LPG-01

            // 3️⃣ Cari sequence terakhir untuk tanggal & lapangan ini
            $last = self::where('id_lapangan', $jadwal->id_lapangan)
                ->whereDate('tanggal', $jadwal->tanggal)
                ->where('id_jadwal', 'like', "{$datePart}-{$lapanganCode}-%")
                ->orderBy('id_jadwal', 'desc')
                ->lockForUpdate()
                ->first();

            $lastSequence = $last
                ? (int) substr($last->id_jadwal, -3)
                : 0;

            // 4️⃣ Next sequence
            $nextSequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);

            // 5️⃣ Final ID
            $jadwal->id_jadwal =
                "{$datePart}-{$lapanganCode}-{$nextSequence}";
        });
    }
}
