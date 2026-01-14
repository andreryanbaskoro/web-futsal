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
    public function index(Request $request)
    {
        $query = Lapangan::with('jamOperasional');

        // default: hanya lapangan aktif
        if ($request->filled('status')) {
            // jika ingin semua status, kirim status=all
            if ($request->status !== 'all') {
                $query->where('status', $request->status);
            }
        } else {
            $query->where('status', 'aktif');
        }


        // simple sorting (kolom seperti `harga`, `rating` tidak ada di model default,
        // jadi kita handle dengan fallback jika kolom tidak tersedia)
        $sort = $request->get('sort', 'popular');
        switch ($sort) {
            case 'price-low':
                if (Schema::hasColumn('lapangan', 'harga')) {
                    $query->orderBy('harga', 'asc');
                }
                break;
            case 'price-high':
                if (Schema::hasColumn('lapangan', 'harga')) {
                    $query->orderBy('harga', 'desc');
                }
                break;
            case 'rating':
                if (Schema::hasColumn('lapangan', 'rating')) {
                    $query->orderBy('rating', 'desc');
                }
                break;
            case 'popular':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }


        // paginate — ubah jumlah per halaman sesuai kebutuhan
        $lapangans = $query->paginate(12)->appends($request->query());


        // Supaya view tidak pecah bila beberapa atribut (image, harga, rating) tidak ada di tabel,
        // kita map collection untuk menyediakan fallback values.
        $lapangans->getCollection()->transform(function ($lap) {

            // ambil jam operasional termurah (interval terkecil)
            $operasional = $lap->jamOperasional
                ->sortBy('interval_menit')
                ->first();

            return (object) [
                'id_lapangan'   => $lap->id_lapangan,
                'nama_lapangan' => $lap->nama_lapangan,
                'deskripsi'     => $lap->deskripsi,
                'status'        => $lap->status,

                // fallback UI
                'image' => $lap->image
                    ?? 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=1200&q=80',

                // 🔥 HARGA PER MENIT
                'harga' => $operasional->harga ?? 0,
                'interval_menit' => $operasional->interval_menit ?? 60,

                // badge
                'status_label' => $lap->status === 'aktif' ? 'Tersedia' : 'Hampir Penuh',
                'status_badge' => $lap->status === 'aktif' ? 'success' : 'warning',

                // dummy UI
                'dimensi' => $lap->dimensi,
                'kapasitas' => $lap->kapasitas,
                'rating' => $lap->rating,
                'rating_count' => $lap->rating_count,
            ];
        });



        return view('pelanggan.lapangan', compact('lapangans'));
    }
}
