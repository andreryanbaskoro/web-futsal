<?php

namespace App\Http\Controllers\Landing;

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


class LandingController extends Controller
{
    public function beranda()
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

        return view('landing.beranda', compact(
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


    public function lapangan()
    {
        $lapanganList = Lapangan::aktif()
            ->with(['jamOperasional' => function ($q) {
                $q->orderBy('harga', 'asc');
            }])
            ->get();

        return view('landing.lapangan', compact('lapanganList'));
    }

    public function jadwalLapangan($id_lapangan)
    {
        $lapangan = Lapangan::with('jamOperasional')
            ->findOrFail($id_lapangan);

        return view('landing.jadwal', compact('lapangan'));
    }



    public function jadwal()
    {
        $lapangans = Lapangan::where('status', 'aktif')
            ->orderBy('nama_lapangan')
            ->get();

        return view('landing.jadwal', compact('lapangans'));
    }

    public function slots(Request $request)
    {
        $request->validate([
            'id_lapangan' => 'required|exists:lapangan,id_lapangan',
            'tanggal'     => 'required|date',
        ]);

        $tanggal = Carbon::parse($request->tanggal);

        $hariMap = [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            7 => 'minggu',
        ];

        $hari = $hariMap[$tanggal->dayOfWeekIso];

        /** AMBIL SLOT YANG SUDAH DIBAYAR */
        $bookedSlots = Jadwal::where('id_lapangan', $request->id_lapangan)
            ->whereDate('tanggal', $tanggal)
            ->whereHas('pemesananJadwal.pemesanan', function ($q) {
                $q->where('status_pemesanan', 'dibayar');
            })
            ->get()
            ->map(fn($j) => $j->jam_mulai)
            ->toArray();

        $operasionals = JamOperasional::where('id_lapangan', $request->id_lapangan)
            ->where('hari', $hari)
            ->get();

        $slots = [];
        $now = Carbon::now();

        foreach ($operasionals as $op) {
            $start = Carbon::createFromFormat('H:i:s', $op->jam_buka);
            $end   = Carbon::createFromFormat('H:i:s', $op->jam_tutup);

            while ($start->lt($end)) {
                $jamMulai   = $start->format('H:i:s');
                $jamSelesai = $start->copy()->addMinutes($op->interval_menit)->format('H:i:s');

                $isPast = Carbon::parse(
                    $tanggal->toDateString() . ' ' . $jamMulai
                )->lt($now);

                $slots[] = [
                    'jam_mulai'   => substr($jamMulai, 0, 5),
                    'jam_selesai' => substr($jamSelesai, 0, 5),
                    'jam'         => substr($jamMulai, 0, 5) . ' - ' . substr($jamSelesai, 0, 5),
                    'harga'       => round(($op->harga / 60) * $op->interval_menit, 0),
                    'durasi'      => $op->interval_menit,
                    'booked'      => in_array($jamMulai, $bookedSlots),
                    'past'        => $isPast,
                ];

                $start->addMinutes($op->interval_menit);
            }
        }

        return response()->json($slots);
    }

    public function galeri()
    {
        $galleries = Gallery::active()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('landing.galeri', compact('galleries'));
    }

    public function blog()
    {
        $articles = Article::orderBy('tanggal_post', 'desc')
            ->paginate(6);

        // artikel populer (contoh: terbaru)
        $popularArticles = Article::orderBy('tanggal_post', 'desc')
            ->limit(5)
            ->get();

        // kategori + jumlah
        $categories = Article::select('kategori')
            ->selectRaw('count(*) as total')
            ->groupBy('kategori')
            ->get();

        return view('landing.blog', compact(
            'articles',
            'popularArticles',
            'categories'
        ));
    }


    public function blogDetail($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        // Artikel terkait (kategori sama, kecuali artikel ini)
        $relatedArticles = Article::where('kategori', $article->kategori)
            ->where('id', '!=', $article->id)
            ->orderBy('tanggal_post', 'desc')
            ->limit(3)
            ->get();

        return view('landing.blog-detail', compact(
            'article',
            'relatedArticles'
        ));
    }

    public function kontak()
    {
        return view('landing.kontak');
    }

    public function sendContact(Request $request)
    {
        // 1. Validasi
        $validated = $request->validate([
            'nama'        => 'required|string|max:150',
            'email'       => 'required|email|max:150',
            'no_telepon'  => 'nullable|string|max:20',
            'subjek'      => 'required|string|max:50',
            'pesan'       => 'required|string',
        ]);

        // 2. Simpan ke database
        ContactMessage::create($validated);

        // 3. Redirect + anchor
        return redirect()
            ->to(route('kontak') . '#contact-section')
            ->with('success', 'Pesan berhasil dikirim. Terima kasih!');
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
