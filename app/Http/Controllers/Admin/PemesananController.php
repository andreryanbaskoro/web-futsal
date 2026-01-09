<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index()
    {
        $pemesanan = Pemesanan::with(['pengguna','lapangan','jadwal'])
            ->orderBy('created_at','desc')
            ->get();

        return view('admin.pemesanan.index', [
            'title' => 'Data Pemesanan',
            'pemesanan' => $pemesanan,
        ]);
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::with(['pengguna','lapangan','jadwal','pembayaran'])
            ->findOrFail($id);

        return view('admin.pemesanan.show', [
            'title' => 'Detail Pemesanan',
            'pemesanan' => $pemesanan,
        ]);
    }

    // Verifikasi / ubah status pemesanan (mis: dari pending -> dibayar)
    public function verifikasi(Request $request, $id)
    {
        $p = Pemesanan::findOrFail($id);

        $request->validate([
            'status_pemesanan' => 'required|in:pending,dibayar,dibatalkan,kadaluarsa'
        ]);

        $p->status_pemesanan = $request->status_pemesanan;
        $p->save();

        return redirect()->route('admin.pemesanan.show', $id)
            ->with('success', 'Status pemesanan berhasil diperbarui');
    }
}
