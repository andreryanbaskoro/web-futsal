<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    // 🔹 Nama tabel
    protected $table = 'pembayaran';

    // 🔹 Primary key
    protected $primaryKey = 'id_pembayaran';

    public $incrementing = true;

    protected $keyType = 'int';

    // 🔹 Kolom yang boleh diisi
    protected $fillable = [
        'id_pemesanan',
        'order_id',
        'snap_token',
        'tipe_pembayaran',
        'status_transaksi',
        'status_fraud',
        'waktu_bayar',
        'response_midtrans',
    ];

    public $timestamps = true;

    // 🔹 Cast JSON & datetime
    protected $casts = [
        'response_midtrans' => 'array',
        'waktu_bayar' => 'datetime',
    ];

    /* =======================
     * RELASI
     * =======================
     */

    // Pembayaran milik satu pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }


    /* =======================
     * HELPER STATUS
     * =======================
     */

    public function isBerhasil()
    {
        return in_array($this->status_transaksi, [
            'capture',
            'settlement'
        ]);
    }

    public function isPending()
    {
        return $this->status_transaksi === 'pending';
    }

    public function isGagal()
    {
        return in_array($this->status_transaksi, [
            'deny',
            'cancel',
            'expire'
        ]);
    }
}
