<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 🔹 Nama tabel
    protected $table = 'pengguna';

    // 🔹 Primary key
    protected $primaryKey = 'id_pengguna';

    public $incrementing = true;

    protected $keyType = 'int';

    // 🔹 Kolom yang boleh diisi
    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'peran',
    ];

    // 🔹 Kolom tersembunyi
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 🔹 Casting
    protected $casts = [
        'password' => 'hashed',
    ];

    /* =======================
     * RELASI
     * =======================
     */

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_pengguna', 'id_pengguna');
    }

    /* =======================
     * HELPER ROLE
     * =======================
     */

    public function isAdmin()
    {
        return $this->peran === 'admin';
    }

    public function isOwner()
    {
        return $this->peran === 'owner';
    }

    public function isPelanggan()
    {
        return $this->peran === 'pelanggan';
    }
}
