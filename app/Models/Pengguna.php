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

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'peran',
        'verification_code',
        'email_verified_at',
        'verification_sent_at',
        'remember_token',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verification_sent_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    protected $attributes = [
        'peran' => 'pelanggan',
        'status' => 'active',
    ];

    /* =======================
     * ROLE HELPER
     * ======================= */

    public const ADMIN = 'admin';
    public const OWNER = 'owner';
    public const PELANGGAN = 'pelanggan';

    public function isAdmin(): bool
    {
        return $this->peran === self::ADMIN;
    }

    public function isOwner(): bool
    {
        return $this->peran === self::OWNER;
    }

    public function isPelanggan(): bool
    {
        return $this->peran === self::PELANGGAN;
    }

    /* =======================
     * RELASI
     * ======================= */

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_pengguna', 'id_pengguna');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_pengguna', 'id_pengguna');
    }
}
