<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Pembayaran;

class LaporanController extends Controller
{
    public function index()
    {
        // contoh ringkasan sederhana
        $total_pemesanan = Pemesanan::count();
        $total_transaksi = Pembayaran::whereIn('status_transaksi', ['capture','settlement'])->count();

        return view('admin.laporan.index', [
            'title' => 'Laporan',
            'total_pemesanan' => $total_pemesanan,
            'total_transaksi' => $total_transaksi,
        ]);
    }
}
