<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use App\Models\Lapangan;
use App\Models\Ulasan;
use App\Models\PemesananJadwal;
use App\Models\Notification;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;



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
        // 1️⃣ Ambil pemesanan TANPA filter status & expired dulu
        $pemesanan = Pemesanan::where('kode_pemesanan', $kode)
            ->where('id_pengguna', auth()->id())
            ->first();

        // ❌ Kode tidak ada / bukan milik user
        if (! $pemesanan) {
            abort(404, 'Pemesanan tidak ditemukan.');
        }

        // ❌ Sudah dibayar → arahkan ke history
        if ($pemesanan->status_pemesanan === Pemesanan::DIBAYAR) {
            return redirect()
                ->route('pelanggan.booking.history')
                ->with('info', 'Pemesanan ini sudah dibayar.');
        }

        // ❌ Dibatalkan / kadaluarsa
        if (in_array($pemesanan->status_pemesanan, [
            Pemesanan::DIBATALKAN,
            Pemesanan::KADALUARSA,
        ])) {
            return redirect()
                ->route('pelanggan.booking.history')
                ->with('error', 'Pemesanan ini sudah tidak aktif.');
        }

        // ❌ Expired berdasarkan waktu
        if ($pemesanan->expired_at && $pemesanan->expired_at <= now()) {
            $pemesanan->update([
                'status_pemesanan' => Pemesanan::KADALUARSA,
            ]);

            return redirect()
                ->route('pelanggan.booking.history')
                ->with('error', 'Waktu pembayaran telah habis.');
        }

        // ✅ Sampai sini PASTI: pending & masih valid
        // ==================================================

        $details = PemesananJadwal::with('jadwal.lapangan')
            ->where('id_pemesanan', $pemesanan->id_pemesanan)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        if ($details->isEmpty()) {
            abort(404, 'Detail pemesanan tidak ditemukan.');
        }

        $first = $details->first();
        $lapangan = $first->jadwal->lapangan ?? $pemesanan->lapangan;

        $tanggalDisplay = Carbon::parse($first->tanggal)
            ->translatedFormat('l, d F Y');

        // 2️⃣ Merge slot berurutan
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
                $endMin   = $sh * 60 + $sm;
                if ($endMin <= $startMin) $endMin += 24 * 60;

                $durasi = $endMin - $startMin;
            }

            if (! $current) {
                $current = [
                    'start' => $jamMulai,
                    'end' => $jamSelesai,
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
                    'end' => $jamSelesai,
                    'durasi_menit' => $durasi,
                ];
            }
        }

        if ($current) {
            $mergedSlots[] = $current;
        }

        // 3️⃣ Render view
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
            'detailJadwal.jadwal.lapangan',
            'lapangan',
            'ulasan' // <- tambah ini
        ])->where('id_pengguna', auth()->user()->id_pengguna)
            ->latest()
            ->get();



        return view('pelanggan.booking-history', compact('bookings'));
    }



    public function bookingHistoryDetail(string $kode)
    {
        $pemesanan = Pemesanan::with('ulasan') // jangan lupa eager-load ulasan
            ->where('kode_pemesanan', $kode)
            ->where('id_pengguna', auth()->id())
            ->firstOrFail();

        $details = PemesananJadwal::with('jadwal.lapangan')
            ->where('id_pemesanan', $pemesanan->id_pemesanan)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        if ($details->isEmpty()) {
            abort(404, 'Detail pemesanan tidak ditemukan');
        }

        $first = $details->first();
        $lapangan = $first->jadwal->lapangan ?? $pemesanan->lapangan;

        $tanggal = Carbon::parse($first->tanggal)
            ->translatedFormat('l, d F Y');

        // merge slot (pakai logic yang sama)
        $mergedSlots = [];
        $current = null;
        foreach ($details as $d) {
            $mulai = substr($d->jadwal->jam_mulai ?? $d->jam_mulai, 0, 5);
            $selesai = substr($d->jadwal->jam_selesai ?? $d->jam_selesai, 0, 5);
            $durasi = (int) $d->durasi_menit;

            if (!$current) {
                $current = [
                    'start' => $mulai,
                    'end' => $selesai,
                    'durasi' => $durasi
                ];
                continue;
            }

            if ($mulai === $current['end']) {
                $current['end'] = $selesai;
                $current['durasi'] += $durasi;
            } else {
                $mergedSlots[] = $current;
                $current = [
                    'start' => $mulai,
                    'end' => $selesai,
                    'durasi' => $durasi
                ];
            }
        }

        if ($current) {
            $mergedSlots[] = $current;
        }

        return view('pelanggan.booking-history-detail', compact(
            'pemesanan',
            'lapangan',
            'tanggal',
            'mergedSlots'
        ));
    }

    public function giveRating(Request $request, $kode)
    {
        $user = Auth::user();

        $pemesanan = Pemesanan::with(['ulasan', 'detailJadwal.jadwal.lapangan', 'lapangan'])
            ->where('kode_pemesanan', $kode)
            ->where('id_pengguna', $user->id_pengguna)
            ->firstOrFail();

        if ($pemesanan->status_pemesanan !== 'dibayar') {
            return response()->json([
                'success' => false,
                'message' => 'Pemesanan belum selesai.'
            ], 403);
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $isUpdate = (bool) $pemesanan->ulasan;

            $lapanganId = $pemesanan->lapangan->id_lapangan
                ?? $pemesanan->detailJadwal->first()->jadwal->lapangan->id_lapangan;

            if ($pemesanan->ulasan) {
                $ulasan = $pemesanan->ulasan;
                $ulasan->rating = $data['rating'];
                $ulasan->komentar = $data['review'] ?? null;
                $ulasan->save();
            } else {
                $ulasan = Ulasan::create([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'id_pengguna'  => $user->id_pengguna,
                    'id_lapangan'  => $lapanganId,
                    'rating'       => $data['rating'],
                    'komentar'     => $data['review'] ?? null,
                ]);
            }

            // refresh rating lapangan
            $lapangan = Lapangan::findOrFail($lapanganId);
            $lapangan->refreshRating(); // pastikan method ini ada dan memperbarui rating & rating_count

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $isUpdate ? 'Rating diperbarui.' : 'Terima kasih atas rating Anda!',
                'avg_rating'   => (float) $lapangan->rating,
                'rating_count' => (int) $lapangan->rating_count,
                'user_rating'  => [
                    'rating' => (int) $ulasan->rating,
                    'komentar' => $ulasan->komentar,
                ],
            ]);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan ulasan.'
            ], 500);
        }
    }
}
