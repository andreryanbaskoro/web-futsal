<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;

class LandingController extends Controller
{
    public function beranda()
    {
        return view('landing.beranda');
    }

    public function lapangan()
    {
        return view('landing.lapangan');
    }

    public function jadwal()
    {
        $lapangans = Lapangan::where('status', 'aktif')->orderBy('nama_lapangan')->get();
        $today = \Carbon\Carbon::now()->toDateString(); // biar date input default jalan
        return view('landing.jadwal', compact('lapangans', 'today'));
    }

    public function galeri()
    {
        return view('landing.galeri');
    }
    public function blog()
    {
        return view('landing.blog');
    }
    public function kontak()
    {
        return view('landing.kontak');
    }
    public function syarat()
    {
        return view('landing.syarat');
    }
    public function faq()
    {
        return view('landing.faq');
    }
    public function tentang()
    {
        return view('landing.tentang');
    }
}
