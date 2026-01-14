<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'peran',
    ];

    protected $hidden = [
        'password',
    ];

    /* =======================
     * RELASI
     * ======================= */

    // 1 pengguna bisa memberi banyak ulasan
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_pengguna', 'id_pengguna');
    }

    // 1 pengguna bisa punya banyak pemesanan
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_pengguna', 'id_pengguna');
    }
}
