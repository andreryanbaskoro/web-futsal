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
        return !$this->pemesanan()
            ->whereIn('status_pemesanan', ['pending', 'dibayar'])
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
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jadwal) {
            if (empty($jadwal->id_jadwal)) {
                // Generate the date part (YYYYMMDD)
                $datePart = Carbon::now()->format('Ymd');

                // Get the latest `id_jadwal` to extract the sequence number
                $lastJadwal = self::where('id_lapangan', $jadwal->id_lapangan)
                    ->orderBy('id_jadwal', 'desc')
                    ->first();

                // Extract sequence number (after the first dash) and increment
                if ($lastJadwal) {
                    $lastSequence = (int) substr($lastJadwal->id_jadwal, 9, 3); // Get the 3 digits sequence
                } else {
                    $lastSequence = 0;
                }

                // Increment sequence number and pad it to 3 digits
                $sequenceNumber = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);

                // Create the final ID (YYYYMMDD-001-LPG-01)
                $lapangan = Lapangan::find($jadwal->id_lapangan);
                $lapanganCode = $lapangan ? $lapangan->id_lapangan : 'LPG-00';

                // Combine everything into the final ID format
                $jadwal->id_jadwal = $datePart . '-' . $sequenceNumber . '-' . $lapanganCode;
            }
        });
    }
}
