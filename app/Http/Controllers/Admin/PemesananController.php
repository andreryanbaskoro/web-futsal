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
            'detailJadwal.jadwal',  // Pastikan relasi 'jadwal' dipanggil di sini
        ])
            ->orderBy('created_at', 'desc')
            ->get();

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
