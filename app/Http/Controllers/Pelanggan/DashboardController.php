<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Jadwal;
use App\Models\Gallery;
use App\Models\Article;
use App\Models\JamOperasional;
use Carbon\Carbon;
use App\Models\ContactMessage;
use App\Models\Pemesanan;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // HERO STATS
        $totalLapangan = Lapangan::where('status', 'aktif')->count();

        $bookingBulanIni = Pemesanan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $ratingRataRata = round(
            Lapangan::where('status', 'aktif')->avg('rating') ?? 0,
            1
        );

        // === FIELDS SECTION ===
        $lapanganList = Lapangan::where('status', 'aktif')
            ->with(['jamOperasional' => function ($q) {
                $q->orderBy('harga', 'asc');
            }])
            ->take(3)
            ->get();

        // === GALLERY SECTION ===
        $totalGallery = Gallery::active()->count();   // total foto aktif

        $galleryList = Gallery::active()
            ->orderBy('id', 'desc')
            ->take(4) // tampil di beranda
            ->get();

        $galleryRemaining = max($totalGallery - $galleryList->count(), 0);

        // === BLOG SECTION ===
        $articleList = Article::orderBy('tanggal_post', 'desc')
            ->take(3)
            ->get();

        $totalArticle = Article::count();
        $articleRemaining = max($totalArticle - $articleList->count(), 0);

        return view('pelanggan.beranda', compact(
            'totalLapangan',
            'bookingBulanIni',
            'ratingRataRata',
            'lapanganList',
            'galleryList',
            'galleryRemaining',
            'articleList',
            'articleRemaining'
        ));
    }
}
