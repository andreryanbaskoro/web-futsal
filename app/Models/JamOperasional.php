<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamOperasional extends Model
{
    use HasFactory;

    // 🔹 Nama tabel
    protected $table = 'jam_operasional';

    // 🔹 Primary key
    protected $primaryKey = 'id_operasional';

    // Primary key string, bukan auto-increment
    public $incrementing = false;
    protected $keyType = 'string';

    // 🔹 Kolom yang boleh diisi
    protected $fillable = [
        'id_lapangan',
        'hari',
        'jam_buka',
        'jam_tutup',
        'interval_menit',
        'harga',
    ];

    // 🔹 Timestamp aktif
    public $timestamps = true;

    /* =======================
     * RELASI
     * =======================
     */

    // 1 jam_operasional milik 1 lapangan
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan', 'id_lapangan');
    }

    /* =======================
     * SCOPES (opsional)
     * =======================
     */

    // Ambil jadwal operasional untuk hari tertentu
    public function scopeHari($query, $hari)
    {
        return $query->where('hari', strtolower($hari));
    }

    /* =======================
     * FUNGSI BANTUAN
     * =======================
     */

    // Hitung jumlah slot berdasarkan interval menit
    public function hitungSlot()
    {
        $jamBuka = strtotime($this->jam_buka);
        $jamTutup = strtotime($this->jam_tutup);
        $intervalDetik = $this->interval_menit * 60;

        return floor(($jamTutup - $jamBuka) / $intervalDetik);
    }

    /* =======================
     * EVENT MODEL
     * =======================
     */

    // Generate ID otomatis saat create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($operasional) {
            if (empty($operasional->id_operasional)) {
                // Format: LPG-01-SENIN
                $lapanganCode = $operasional->id_lapangan ?? 'LPG-00';
                $hariUpper = strtoupper(substr($operasional->hari, 0, 6)); // SENIN, SELASA, dll
                $operasional->id_operasional = $lapanganCode . '-' . $hariUpper;
            }
        });
    }
}
