<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'id_notification';

    protected $fillable = [
        'id_pengguna',
        'title',
        'message',
        'type',
        'data',
        'url',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    /* =====================
       SCOPES
    ====================== */

    public function scopeUnread($query)
    {
        return $query->where('is_read', 0);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', 1);
    }
}
