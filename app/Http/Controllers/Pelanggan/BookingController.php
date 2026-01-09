<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pemesanan;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // tampilkan daftar jadwal tersedia
    public function jadwal()
    {
        $jadwals = Jadwal::with('lapangan')->tersedia()->orderBy('tanggal')->get();
        return view('pelanggan.jadwal.index', [
            'title' => 'Jadwal',
            'jadwals' => $jadwals,
        ]);
    }

    // buat pemesanan (development: terima id_pengguna atau fallback)
    public function pesan(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'id_lapangan' => 'required|exists:lapangan,id_lapangan',
            'total_bayar' => 'required|numeric|min:0',
            // optional: id_pengguna for dev
            'id_pengguna' => 'nullable|exists:pengguna,id_pengguna',
        ]);

        // gunakan auth jika ada, kalau tidak gunakan input atau user pertama
        $id_pengguna = auth()->check()
            ? auth()->user()->id_pengguna
            : ($request->input('id_pengguna') ?? (User::first()->id_pengguna ?? null));

        if (!$id_pengguna) {
            return back()->with('error','Tidak ada pengguna tersedia. Tambahkan pengguna atau sertakan id_pengguna.');
        }

        // cek ketersediaan jadwal
        $exists = Pemesanan::where('id_jadwal', $request->id_jadwal)
            ->whereIn('status_pemesanan', ['pending','dibayar'])
            ->exists();

        if ($exists) {
            return back()->with('error','Maaf, jadwal sudah dibooking.');
        }

        $kode = 'BOOK'.time().rand(100,999);

        $p = Pemesanan::create([
            'id_pengguna' => $id_pengguna,
            'id_lapangan' => $request->id_lapangan,
            'id_jadwal' => $request->id_jadwal,
            'kode_pemesanan' => $kode,
            'total_bayar' => $request->total_bayar,
            'status_pemesanan' => 'pending',
        ]);

        return redirect()->route('pelanggan.pemesanan')->with('success','Pemesanan dibuat. Kode: '.$kode);
    }

    public function pemesanan()
    {
        // development: tampil semua pemesanan, atau khusus pengguna jika auth
        if (auth()->check()) {
            $id = auth()->user()->id_pengguna;
            $pemesanan = Pemesanan::with(['lapangan','jadwal'])->where('id_pengguna',$id)->get();
        } else {
            $pemesanan = Pemesanan::with(['pengguna','lapangan','jadwal'])->orderBy('created_at','desc')->get();
        }

        return view('pelanggan.pemesanan.index', [
            'title' => 'Pemesanan Saya',
            'pemesanan' => $pemesanan,
        ]);
    }

    // tampilan pembayaran untuk 1 pemesanan
    public function pembayaran($id)
    {
        $pemesanan = Pemesanan::with('pembayaran')->findOrFail($id);
        return view('pelanggan.pembayaran.show', [
            'title' => 'Pembayaran',
            'pemesanan' => $pemesanan,
        ]);
    }

    public function riwayat()
    {
        // riwayat pemesanan (bisa sama dengan pemesanan di dev)
        return $this->pemesanan();
    }
}
