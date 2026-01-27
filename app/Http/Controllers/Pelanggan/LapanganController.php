<?php


namespace App\Http\Controllers\Pelanggan;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lapangan;


class LapanganController extends Controller
{
    /**
     * Tampilkan daftar lapangan untuk halaman pelanggan.
     * Mendukung filter `status` dan `sort` via query string.
     */
    public function index()
    {
        $lapanganList = Lapangan::aktif()
            ->with(['jamOperasional' => function ($q) {
                $q->orderBy('harga', 'asc');
            }])
            ->get();

        return view('pelanggan.lapangan', compact('lapanganList'));
    }
}
