<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamOperasional extends Model
{
    use HasFactory;

    protected $table = 'jam_operasional';

    protected $primaryKey = 'id_operasional';

    // karena BIGINT AUTO_INCREMENT
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_lapangan',
        'hari',
        'jam_buka',
        'jam_tutup',
        'interval_menit',
        'harga',
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

    /* =======================
     * SCOPES
     * =======================
     */

    public function scopeHari($query, $hari)
    {
        return $query->where('hari', strtolower($hari));
    }

    /* =======================
     * HELPER
     * =======================
     */

    public function hitungSlot()
    {
        $jamBuka = strtotime($this->jam_buka);
        $jamTutup = strtotime($this->jam_tutup);
        $intervalDetik = $this->interval_menit * 60;

        return floor(($jamTutup - $jamBuka) / $intervalDetik);
    }
}
