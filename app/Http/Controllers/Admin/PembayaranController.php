<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pemesanan;

class PembayaranController extends Controller
{

    public function index()
    {
        $data = Pembayaran::with(['pemesanan.pengguna'])
            ->whereIn('status_transaksi', ['capture', 'settlement'])
            ->orderBy('waktu_bayar', 'desc')
            ->get()
            ->map(function ($pembayaran) {

                $pemesanan = $pembayaran->pemesanan;

                return [
                    'id_pemesanan'     => $pemesanan->id_pemesanan,
                    'kode_pemesanan'   => $pemesanan->kode_pemesanan,
                    'nama_pengguna'    => $pemesanan->pengguna->nama ?? '-',
                    'total_bayar'      => $pemesanan->total_bayar,
                    'status_transaksi' => $pembayaran->status_transaksi,
                    'tipe_pembayaran'  => $pembayaran->tipe_pembayaran,
                    'waktu_bayar'      => optional($pembayaran->waktu_bayar)?->format('d-m-Y H:i'),
                    'id_pembayaran'    => $pembayaran->id_pembayaran,
                ];
            });

        return view('admin.pembayaran.index', [
            'title' => 'Data Pembayaran',
            'pembayaran' => $data,
        ]);
    }


    // public function index()
    // {
    //     $data = Pemesanan::with(['pengguna', 'pembayaran'])
    //         ->orderBy('created_at', 'desc')
    //         ->get()
    //         ->map(function ($pemesanan) {

    //             // ambil pembayaran terakhir (jika ada)
    //             $pembayaran = $pemesanan->pembayaran->last();

    //             return [
    //                 'id_pemesanan'     => $pemesanan->id_pemesanan,
    //                 'kode_pemesanan'   => $pemesanan->kode_pemesanan,
    //                 'nama_pengguna'    => $pemesanan->pengguna->nama ?? '-',
    //                 'total_bayar'      => $pemesanan->total_bayar,
    //                 'status_transaksi' => $pembayaran->status_transaksi ?? 'belum_bayar',
    //                 'tipe_pembayaran'  => $pembayaran->tipe_pembayaran ?? '-',
    //                 'waktu_bayar' => optional($pembayaran?->waktu_bayar)?->format('d-m-Y H:i'),
    //                 'id_pembayaran'    => $pembayaran->id_pembayaran ?? null,
    //             ];
    //         });

    //     return view('admin.pembayaran.index', [
    //         'title' => 'Data Pembayaran',
    //         'pembayaran' => $data,
    //     ]);
    // }



    public function show($id)
    {
        $pembayaran = Pembayaran::with('pemesanan')->findOrFail($id);

        return view('admin.pembayaran.show', [
            'title' => 'Detail Pembayaran',
            'pembayaran' => $pembayaran,
        ]);
    }
}
