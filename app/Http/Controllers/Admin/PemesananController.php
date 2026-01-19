<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index()
    {
        $pemesanan = Pemesanan::with([
            'pengguna',
            'lapangan',
            'detailJadwal'
        ])->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $jadwalText = [];
                $jadwalRaw  = [];

                foreach ($item->detailJadwal as $dj) {
                    $tanggal = \Carbon\Carbon::parse($dj->tanggal)->format('d M Y');
                    $jam     = substr($dj->jam_mulai, 0, 5) . ' - ' . substr($dj->jam_selesai, 0, 5);

                    $jadwalText[] = "$tanggal $jam";
                    $jadwalRaw[] = [
                        'tanggal' => $tanggal,
                        'jam' => $jam
                    ];
                }

                $item->detail_jadwal = implode(', ', $jadwalText); // untuk search & sort
                $item->detail_jadwal_raw = $jadwalRaw; // untuk display

                return $item;
            });

        return view('admin.pemesanan.index', [
            'title' => 'Data Pemesanan',
            'pemesanan' => $pemesanan,
        ]);
    }



    public function show($id)
    {
        // Memuat detail pemesanan beserta pengguna, lapangan, detailJadwal (termasuk jadwal) dan pembayaran
        $pemesanan = Pemesanan::with([
            'pengguna',
            'lapangan',
            'detailJadwal.jadwal', // pastikan ini memanggil relasi jadwal dari PemesananJadwal
            'pembayaran'
        ])
            ->findOrFail($id);

        return view('admin.pemesanan.show', [
            'title' => 'Detail Pemesanan',
            'pemesanan' => $pemesanan,
        ]);
    }
}
