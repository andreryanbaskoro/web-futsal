<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with('pemesanan')
            ->orderBy('created_at','desc')
            ->get();

        return view('admin.pembayaran.index', [
            'title' => 'Data Pembayaran',
            'pembayaran' => $pembayaran,
        ]);
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with('pemesanan')->findOrFail($id);

        return view('admin.pembayaran.show', [
            'title' => 'Detail Pembayaran',
            'pembayaran' => $pembayaran,
        ]);
    }
}
