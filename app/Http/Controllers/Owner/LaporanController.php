<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Jadwal;

class LaporanController extends Controller
{
    public function jadwal()
    {
        $jadwals = Jadwal::with('lapangan')->orderBy('tanggal')->get();
        return view('owner.laporan.jadwal', compact('jadwals'));
    }

    public function pemesanan()
    {
        $pemesanan = Pemesanan::with(['pengguna','lapangan','jadwal'])->get();
        return view('owner.laporan.pemesanan', compact('pemesanan'));
    }

    public function transaksi()
    {
        // ringkasan transaksi
        return view('owner.laporan.transaksi', [
            'title' => 'Laporan Transaksi'
        ]);
    }
}
