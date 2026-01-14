<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pemesanan extends Model
{
    use HasFactory;

    // 🔹 Nama tabel
    protected $table = 'pemesanan';

    // 🔹 Primary key
    protected $primaryKey = 'id_pemesanan';

    public $incrementing = false; // karena kita generate string sendiri
    protected $keyType = 'string';

    // 🔹 Kolom yang boleh diisi
    protected $fillable = [
        'id_pengguna',
        'id_lapangan',
        'id_jadwal',
        'kode_pemesanan',
        'total_bayar',
        'status_pemesanan',
        'expired_at', // ✅ WAJIB
    ];

    protected $casts = [
        'expired_at' => 'datetime', // ✅ WAJIB
    ];


    public $timestamps = true;

    /* =======================
     * RELASI
     * ======================= */

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan', 'id_lapangan');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pemesanan', 'id_pemesanan');
    }

    /* =======================
     * SCOPE
     * ======================= */

    public function scopeAktif($query)
    {
        return $query->whereIn('status_pemesanan', ['pending', 'dibayar']);
    }

    /* =======================
     * HELPER STATUS
     * ======================= */

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

    public function jadwals()
    {
        return $this->hasMany(
            PemesananJadwal::class,
            'id_pemesanan',
            'id_pemesanan'
        );
    }


    /* =======================
     * EVENT MODEL
     * ======================= */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pemesanan) {

            if (!empty($pemesanan->id_pemesanan)) {
                return;
            }

            // Format tanggal: YYYYMMDD
            $datePart = Carbon::now()->format('Ymd');

            // Cari booking terakhir di TANGGAL YANG SAMA
            $last = self::where('id_pemesanan', 'like', "FTS-{$datePart}-%")
                ->orderBy('id_pemesanan', 'desc')
                ->first();

            $lastSeq = 0;
            if ($last) {
                // ambil 3 digit terakhir
                $lastSeq = (int) substr($last->id_pemesanan, -3);
            }

            $sequence = str_pad($lastSeq + 1, 3, '0', STR_PAD_LEFT);

            $pemesanan->id_pemesanan = "FTS-{$datePart}-{$sequence}";
            $pemesanan->kode_pemesanan = $pemesanan->id_pemesanan;
        });
    }
}
