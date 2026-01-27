<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $table = 'contact_messages';
    protected $primaryKey = 'id';

    // karena pakai auto increment
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'email',
        'no_telepon',
        'subjek',
        'pesan',
        'status',
    ];

    protected $appends = [
        'subjek_label', // Ini yang sudah ada
        'status_label', // Ini yang sudah ada
        'translated_subjek', // Tambahkan ini
    ];
    /* =======================
     * DEFAULT VALUE
     * ======================= */
    protected $attributes = [
        'status' => 'baru',
    ];


    /* =======================
     * SUBJEK (Indonesia)
     * ======================= */
    public function getTranslatedSubjekAttribute()
    {
        // Ini akan menghasilkan nilai translated_subjek tanpa perlu di database
        return $this->getTranslatedSubjek($this->subjek);
    }


    /* =======================
     * SCOPE (OPSIONAL)
     * ======================= */

    // pesan yang belum dibaca
    public function scopeBaru($query)
    {
        return $query->where('status', 'baru');
    }

    public function scopeDibaca($query)
    {
        return $query->where('status', 'dibaca');
    }

    public function scopeDibalas($query)
    {
        return $query->where('status', 'dibalas');
    }

    public function markAsDibaca()
    {
        // Hanya update kolom yang ada di DB
        $this->update([
            'status' => 'dibaca',
            'updated_at' => now(),
        ]);
    }


    // Fungsi untuk mendapatkan terjemahan subjek
    private function getTranslatedSubjek($subjek)
    {
        $translations = [
            'booking' => 'Pertanyaan Booking',
            'payment' => 'Masalah Pembayaran',
            'facility' => 'Fasilitas',
            'partnership' => 'Kerjasama',
            'other' => 'Lainnya',
        ];

        return $translations[$subjek] ?? $subjek;
    }



    // SUBJEK (Indonesia)
    public function getSubjekLabelAttribute()
    {
        return match ($this->subjek) {
            'booking' => 'Pertanyaan Booking',
            'payment' => 'Masalah Pembayaran',
            'facility' => 'Fasilitas',
            'partnership' => 'Kerjasama',
            'other' => 'Lainnya',
            default => ucfirst($this->subjek),
        };
    }

    // STATUS (Indonesia)
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'baru' => 'Baru',
            'dibaca' => 'Dibaca',
            'dibalas' => 'Dibalas',
            default => ucfirst($this->status),
        };
    }
}
