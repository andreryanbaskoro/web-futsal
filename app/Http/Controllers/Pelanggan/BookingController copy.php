<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use App\Models\PemesananJadwal;
use App\Models\Notification;
use App\Models\Pengguna;
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
            $pemesanan = DB::transaction(function () use ($idLapangan, $tanggal, $slots) {

                // 1) Validasi konflik per slot terhadap jadwal yang SUDAH ADA (yang sudah dibayar)
                foreach ($slots as $s) {
                    $jamMulai = Carbon::createFromFormat('H:i', $s['jam_mulai'])->format('H:i:s');
                    $jamSelesai = Carbon::createFromFormat('H:i', $s['jam_selesai'])->format('H:i:s');

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

                // 2) Buat pemesanan pending (kode unik)
                $kode = null;
                do {
                    $kode = 'PM' . Str::upper(Str::random(8));
                } while (Pemesanan::where('kode_pemesanan', $kode)->exists());

                $pemesanan = Pemesanan::create([
                    'id_pengguna'      => auth()->id(),
                    'id_lapangan'      => $idLapangan,
                    'kode_pemesanan'   => $kode,
                    'total_bayar'      => 0, // diupdate setelah simpan detail
                    'status_pemesanan' => Pemesanan::PENDING,
                    'expired_at'       => now()->addMinutes(30),
                ]);

                // 3) Simpan detail slot ke tabel pemesanan_jadwal (tanpa id_jadwal)
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
                        'id_jadwal'    => null,
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

            // ---------- Notifications (only pending info) ----------
            // Pastikan menggunakan enum yang valid: 'pemesanan', 'pembayaran', 'jadwal', 'sistem'
            // Notifikasi user (pemesanan pending)
            Notification::create([
                'id_pengguna' => $pemesanan->id_pengguna,
                'title'       => "Pemesanan dibuat: {$pemesanan->kode_pemesanan}",
                'message'     => "Pemesanan {$pemesanan->kode_pemesanan} berhasil dibuat dan menunggu pembayaran.",
                'type'        => 'pemesanan',
                'data'        => ['id_pemesanan' => $pemesanan->id_pemesanan],
                'url'         => "/pelanggan/booking-confirm/{$pemesanan->kode_pemesanan}",
            ]);

            // Notifikasi ke admin (pemesanan pending)
            $adminIds = Pengguna::where('peran', 'admin')->pluck('id_pengguna')->toArray();
            foreach ($adminIds as $adminId) {
                Notification::create([
                    'id_pengguna' => $adminId,
                    'title'       => "Pemesanan baru (pending): {$pemesanan->kode_pemesanan}",
                    'message'     => "Pengguna membuat pemesanan {$pemesanan->kode_pemesanan} (menunggu pembayaran).",
                    'type'        => 'pemesanan',
                    'data'        => ['id_pemesanan' => $pemesanan->id_pemesanan, 'user_id' => $pemesanan->id_pengguna],
                    'url'         => "/admin/pemesanan/{$pemesanan->id_pemesanan}",
                ]);
            }

            return response()->json([
                'kode' => $pemesanan->kode_pemesanan,
            ], 201);
        } catch (\Throwable $e) {
            // production: log($e) dan kembalikan pesan generik
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tandai pemesanan sebagai dibayar:
     * - update status
     * - buat jadwal nyata untuk setiap detail
     * - update pemesanan_jadwal (simpan id_jadwal)
     * - buat notifikasi untuk pengguna (dan admin)
     */
    public function markAsPaid(Pemesanan $pemesanan)
    {
        try {
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

                // 3. Buat jadwal REAL dan update setiap detail
                foreach ($details as $detail) {
                    if (! $detail->id_jadwal) {
                        $jadwal = Jadwal::create([
                            'id_lapangan' => $pemesanan->id_lapangan,
                            'tanggal'     => $detail->tanggal,
                            'jam_mulai'   => $detail->jam_mulai,
                            'jam_selesai' => $detail->jam_selesai,
                        ]);

                        $detail->update([
                            'id_jadwal' => $jadwal->id_jadwal,
                        ]);

                        // juga perbarui object koleksi agar nanti bisa dipakai untuk notifikasi
                        $detail->id_jadwal = $jadwal->id_jadwal;
                    }
                }

                // refresh relation data supaya kita punya detail terbaru
                $pemesanan->load('detailJadwal');

                // 4. Notifikasi untuk pengguna (pembayaran diterima + jadwal)
                $slotsForNotif = $pemesanan->detailJadwal->map(function ($d) {
                    return [
                        'tanggal' => $d->tanggal,
                        'jam_mulai' => $d->jam_mulai,
                        'jam_selesai' => $d->jam_selesai,
                        'id_jadwal' => $d->id_jadwal,
                    ];
                })->toArray();

                Notification::create([
                    'id_pengguna' => $pemesanan->id_pengguna,
                    'title'       => "Pembayaran diterima: {$pemesanan->kode_pemesanan}",
                    'message'     => "Pembayaran untuk pemesanan {$pemesanan->kode_pemesanan} telah diterima. Jadwal Anda sudah dikonfirmasi.",
                    'type'        => 'pembayaran',
                    'data'        => ['id_pemesanan' => $pemesanan->id_pemesanan, 'slots' => $slotsForNotif],
                    'url'         => "/pelanggan/booking-history",
                ]);

                // 5. Notifikasi untuk semua admin (pemesanan dibayar)
                $adminIds = Pengguna::where('peran', 'admin')->pluck('id_pengguna')->toArray();
                foreach ($adminIds as $adminId) {
                    Notification::create([
                        'id_pengguna' => $adminId,
                        'title'       => "Pemesanan dibayar: {$pemesanan->kode_pemesanan}",
                        'message'     => "Pemesanan {$pemesanan->kode_pemesanan} telah dibayar oleh user ID {$pemesanan->id_pengguna}.",
                        'type'        => 'pembayaran',
                        'data'        => ['id_pemesanan' => $pemesanan->id_pemesanan],
                        'url'         => "/admin/pemesanan/{$pemesanan->id_pemesanan}",
                    ]);
                }
            });

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            // production: log($e) dan kembalikan pesan generik
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Halaman konfirmasi booking (render data untuk pemesanan dengan kode)
     */
    public function bookingConfirm(string $kode)
    {
        $pemesanan = Pemesanan::where('kode_pemesanan', $kode)
            ->where('id_pengguna', auth()->id())
            ->where('status_pemesanan', Pemesanan::PENDING)
            ->where('expired_at', '>', now())
            ->firstOrFail();

        $details = PemesananJadwal::with('jadwal.lapangan')
            ->where('id_pemesanan', $pemesanan->id_pemesanan)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        if ($details->isEmpty()) {
            abort(404, 'Detail pemesanan tidak ditemukan.');
        }

        $first = $details->first();
        $lapangan = $first->jadwal->lapangan ?? ($pemesanan->lapangan ?? null);

        $tanggalVal = $first->tanggal;
        $tanggalDisplay = Carbon::parse($tanggalVal)->translatedFormat('l, d F Y');

        // Merge slot berurutan
        $mergedSlots = [];
        $current = null;

        foreach ($details as $d) {
            $jamMulaiRaw = $d->jadwal->jam_mulai ?? $d->jam_mulai;
            $jamSelesaiRaw = $d->jadwal->jam_selesai ?? $d->jam_selesai;

            $jamMulai = substr($jamMulaiRaw, 0, 5);
            $jamSelesai = substr($jamSelesaiRaw, 0, 5);

            $durasi = (int) ($d->durasi_menit ?? 0);
            if ($durasi <= 0) {
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



    /**
     * Riwayat booking user
     */
    public function bookingHistory()
    {
        $bookings = Pemesanan::with([
            'detailJadwal'
        ])
            ->where('id_pengguna', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pelanggan.booking-history', compact('bookings'));
    }
}
