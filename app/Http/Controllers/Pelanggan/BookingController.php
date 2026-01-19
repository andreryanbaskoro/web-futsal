<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use App\Models\PemesananJadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{

    /**
     * Buat pemesanan baru (PENDING).
     *
     * Frontend harus mengirim:
     * - id_lapangan (int)
     * - tanggal (Y-m-d)
     * - slots: array of { jam_mulai: "HH:MM", jam_selesai: "HH:MM", harga: int, durasi_menit: int }
     *
     * Catatan: controller ini TIDAK membuat record jadwal. Jadwal hanya dibuat setelah pembayaran sukses.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_lapangan' => ['required', 'integer'],
            'tanggal'     => ['required', 'date'],
            'slots'       => ['required', 'array', 'min:1'],
            'slots.*.jam_mulai'  => ['required', 'date_format:H:i'],
            'slots.*.jam_selesai' => ['required', 'date_format:H:i'],
            'slots.*.harga' => ['nullable', 'numeric', 'min:0'],
            'slots.*.durasi_menit' => ['nullable', 'integer', 'min:1'],
        ]);

        $idLapangan = $request->id_lapangan;
        $tanggal    = Carbon::parse($request->tanggal)->toDateString();
        $slots      = $request->slots;

        try {
            $pemesanan = DB::transaction(function () use ($idLapangan, $tanggal, $slots, $request) {

                // 1) Validasi konflik per slot terhadap jadwal yang SUDAH ADA (yang sudah dibayar)
                foreach ($slots as $s) {
                    // normalize waktu ke format H:i:s
                    $jamMulai = Carbon::createFromFormat('H:i', $s['jam_mulai'])->format('H:i:s');
                    $jamSelesai = Carbon::createFromFormat('H:i', $s['jam_selesai'])->format('H:i:s');

                    // Overlap check: existing.jam_mulai < new.jam_selesai AND existing.jam_selesai > new.jam_mulai
                    $conflict = Jadwal::where('id_lapangan', $idLapangan)
                        ->whereDate('tanggal', $tanggal)
                        ->where(function ($q) use ($jamMulai, $jamSelesai) {
                            $q->where('jam_mulai', '<', $jamSelesai)
                                ->where('jam_selesai', '>', $jamMulai);
                        })
                        // Pastikan hanya jadwal yang berasosiasi dengan pemesanan DIBAYAR yang memblokir
                        ->whereHas('pemesananJadwal.pemesanan', function ($qq) {
                            $qq->where('status_pemesanan', Pemesanan::DIBAYAR ?? 'dibayar');
                        })
                        ->exists();

                    if ($conflict) {
                        abort(409, "Slot {$s['jam_mulai']} - {$s['jam_selesai']} pada tanggal {$tanggal} sudah terisi.");
                    }
                }

                // 2) Buat pemesanan pending
                $kode = null;
                do {
                    $kode = 'PM' . Str::upper(Str::random(8));
                } while (Pemesanan::where('kode_pemesanan', $kode)->exists());

                $pemesanan = Pemesanan::create([
                    'id_pengguna'      => auth()->id(),
                    'id_lapangan'      => $idLapangan,
                    'kode_pemesanan'   => $kode,
                    'total_bayar'      => 0, // akan diupdate
                    'status_pemesanan' => Pemesanan::PENDING,
                    'expired_at'       => now()->addMinutes(30),
                ]);

                // 3) Simpan detail slot ke tabel pemesanan_jadwal (tanpa id_jadwal).
                // Pastikan kolom jam_mulai, jam_selesai, harga, durasi_menit ada di tabel pemesanan_jadwal
                $total = 0;
                foreach ($slots as $s) {
                    $jamMulai = Carbon::createFromFormat('H:i', $s['jam_mulai'])->format('H:i:s');
                    $jamSelesai = Carbon::createFromFormat('H:i', $s['jam_selesai'])->format('H:i:s');

                    $durasiMenit = $s['durasi_menit'] ?? (function () use ($jamMulai, $jamSelesai) {
                        [$mh, $mm] = array_map('intval', explode(':', substr($jamMulai, 0, 5)));
                        [$sh, $sm] = array_map('intval', explode(':', substr($jamSelesai, 0, 5)));

                        $start = $mh * 60 + $mm;
                        $end   = $sh * 60 + $sm;

                        if ($end <= $start) {
                            $end += 24 * 60;
                        }

                        return $end - $start;
                    })();

                    $harga = isset($s['harga']) ? (int)$s['harga'] : 0;

                    PemesananJadwal::create([
                        'id_pemesanan' => $pemesanan->id_pemesanan,
                        // jangan isi id_jadwal di sini (NULL) — akan diisi saat pembayaran sukses membuat jadwal
                        'id_jadwal'    => null,
                        // simpan slot sementara:
                        'tanggal'      => $tanggal,
                        'jam_mulai'    => $jamMulai,
                        'jam_selesai'  => $jamSelesai,
                        'harga'        => $harga,
                        'durasi_menit' => (int)$durasiMenit,
                    ]);

                    $total += (int)$harga;
                }

                // 4) Update total
                $pemesanan->update([
                    'total_bayar' => $total,
                ]);

                return $pemesanan;
            });

            return response()->json([
                'kode' => $pemesanan->kode_pemesanan,
            ], 201);
        } catch (\Throwable $e) {
            // di production sebaiknya log($e) dan kembalikan pesan generik
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Halaman konfirmasi booking (render data untuk pemesanan dengan kode)
     *
     * Metode ini akan membaca detail pada pemesanan_jadwal.
     * Jika pemesanan_jadwal sudah terhubung ke jadwal (id_jadwal not null) -> gunakan jadwal sebenarnya.
     * Jika belum, gunakan jam_mulai/jam_selesai yang disimpan di pemesanan_jadwal (pending).
     */
    public function bookingConfirm(string $kode)
    {
        $pemesanan = Pemesanan::where('kode_pemesanan', $kode)
            ->where('id_pengguna', auth()->id())
            ->where('status_pemesanan', Pemesanan::PENDING)
            ->where('expired_at', '>', now())
            ->firstOrFail();

        // Ambil detail pemesanan_jadwal, urut berdasarkan jam_mulai (pakai kolom yang ada di tabel)
        $details = PemesananJadwal::with('jadwal.lapangan')
            ->where('id_pemesanan', $pemesanan->id_pemesanan)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        if ($details->isEmpty()) {
            abort(404, 'Detail pemesanan tidak ditemukan.');
        }

        // Ambil lapangan (prioritaskan relation jadwal jika ada, fallback ke relasi pemesanan)
        $first = $details->first();
        $lapangan = $first->jadwal->lapangan ?? ($pemesanan->lapangan ?? null);

        // Ambil tanggal untuk ditampilkan — gunakan nilai tanggal yang disimpan (dari detail)
        $tanggalVal = $first->tanggal; // ambil dari pemesanan_jadwal (PENDING)
        $tanggalDisplay = Carbon::parse($tanggalVal)->translatedFormat('l, d F Y');


        // Merge slot berurutan dengan cara aman — bekerja baik jika slot disimpan di pemesanan_jadwal (pending) atau terhubung ke jadwal
        $mergedSlots = [];
        $current = null;

        foreach ($details as $d) {
            // gunakan nilai dari jadwal jika ada, jika tidak gunakan kolom yang disimpan di pemesanan_jadwal
            $jamMulaiRaw = $d->jadwal->jam_mulai ?? $d->jam_mulai;      // format 'HH:MM:SS' atau 'HH:MM'
            $jamSelesaiRaw = $d->jadwal->jam_selesai ?? $d->jam_selesai;

            // normalisasi ke 'HH:MM'
            $jamMulai = substr($jamMulaiRaw, 0, 5);
            $jamSelesai = substr($jamSelesaiRaw, 0, 5);

            // durasi aman: prefer kolom durasi_menit yang sudah disimpan; jika tidak ada hitung dari jam
            $durasi = (int) ($d->durasi_menit ?? 0);
            if ($durasi <= 0) {
                // hitung manual (aman terhadap lintas tengah malam)
                [$mh, $mm] = array_map('intval', explode(':', substr($jamMulaiRaw, 0, 5)));
                [$sh, $sm] = array_map('intval', explode(':', substr($jamSelesaiRaw, 0, 5)));
                $startMin = $mh * 60 + $mm;
                $endMin = $sh * 60 + $sm;
                if ($endMin <= $startMin) $endMin += 24 * 60;
                $durasi = $endMin - $startMin;
            }

            if (!$current) {
                $current = [
                    'start' => $jamMulai,
                    'end'   => $jamSelesai,
                    'durasi_menit' => $durasi,
                ];
                continue;
            }

            // jika berurutan (jam mulai item sekarang sama dengan jam akhir current) -> gabungkan
            if ($jamMulai === $current['end']) {
                $current['end'] = $jamSelesai;
                $current['durasi_menit'] += $durasi;
            } else {
                $mergedSlots[] = $current;
                $current = [
                    'start' => $jamMulai,
                    'end'   => $jamSelesai,
                    'durasi_menit' => $durasi,
                ];
            }
        }

        if ($current) {
            $mergedSlots[] = $current;
        }

        return view('pelanggan.booking-confirm', [
            'pemesanan'   => $pemesanan,
            'mergedSlots' => $mergedSlots,
            'total'       => $pemesanan->total_bayar,
            'lapangan'    => $lapangan,
            'tanggal'     => $tanggalDisplay,
        ]);
    }

    public function markAsPaid(Pemesanan $pemesanan)
    {
        DB::transaction(function () use ($pemesanan) {

            // 1. Update status pemesanan
            $pemesanan->update([
                'status_pemesanan' => Pemesanan::DIBAYAR,
            ]);

            // 2. Ambil semua detail slot
            $details = PemesananJadwal::where('id_pemesanan', $pemesanan->id_pemesanan)
                ->orderBy('tanggal')
                ->orderBy('jam_mulai')
                ->get();

            foreach ($details as $detail) {

                // 3. Buat jadwal REAL
                $jadwal = Jadwal::create([
                    'id_lapangan' => $pemesanan->id_lapangan,
                    'tanggal'     => $detail->tanggal,
                    'jam_mulai'   => $detail->jam_mulai,
                    'jam_selesai' => $detail->jam_selesai,
                ]);

                // 4. Update pemesanan_jadwal → simpan id_jadwal
                $detail->update([
                    'id_jadwal' => $jadwal->id_jadwal,
                ]);
            }
        });
    }


    /**
     * Riwayat booking user
     */
    public function bookingHistory()
    {
        $bookings = Pemesanan::with([
            // pastikan relasi ini ada; detailJadwal mengembalikan pemesanan_jadwal
            'detailJadwal'
        ])
            ->where('id_pengguna', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pelanggan.booking-history', compact('bookings'));
    }
}
