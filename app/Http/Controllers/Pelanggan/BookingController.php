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

class BookingController extends Controller
{
    /**
     * Buat pemesanan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_ids' => 'required|array|min:1',
        ]);

        try {
            $pemesanan = DB::transaction(function () use ($request) {

                // 1️⃣ Lock jadwal rows
                $jadwals = Jadwal::with('lapangan')
                    ->whereIn('id_jadwal', $request->jadwal_ids)
                    ->lockForUpdate()
                    ->get();

                if ($jadwals->count() !== count($request->jadwal_ids)) {
                    abort(404, 'Sebagian jadwal tidak ditemukan');
                }

                // 2️⃣ CEK: apakah ada yang sudah dipesan
                $alreadyBooked = PemesananJadwal::whereIn('id_jadwal', $request->jadwal_ids)
                    ->exists();

                if ($alreadyBooked) {
                    abort(409, 'Sebagian slot sudah dipesan');
                }

                $lapangan = $jadwals->first()->lapangan;

                // generate kode_pemesanan unik
                $kode = null;
                do {
                    $kode = 'PM' . Str::upper(Str::random(8)); // contoh: PMA1B2C3D
                } while (Pemesanan::where('kode_pemesanan', $kode)->exists());

                // 3️⃣ Buat pemesanan (sertakan kode_pemesanan)
                $pemesanan = Pemesanan::create([
                    'id_pengguna'      => auth()->id(),
                    'id_lapangan'      => $lapangan->id_lapangan,
                    'kode_pemesanan'   => $kode,
                    'total_bayar'      => 0,
                    'status_pemesanan' => Pemesanan::PENDING,
                    'expired_at'       => now()->addMinutes(30),
                ]);

                // 4️⃣ Simpan detail jadwal
                $total = 0;

                foreach ($jadwals as $jadwal) {

                    $durasiMenit = Carbon::parse($jadwal->jam_mulai)
                        ->diffInMinutes(Carbon::parse($jadwal->jam_selesai));

                    $hari = strtolower(
                        Carbon::parse($jadwal->tanggal)->translatedFormat('l')
                    );

                    $hargaPerJam = DB::table('jam_operasional')
                        ->where('id_lapangan', $jadwal->id_lapangan)
                        ->where('hari', $hari)
                        ->value('harga') ?? 0;

                    $hargaSlot = ($durasiMenit / 60) * $hargaPerJam;

                    PemesananJadwal::create([
                        'id_pemesanan' => $pemesanan->id_pemesanan,
                        'id_jadwal'    => $jadwal->id_jadwal,
                        'harga'        => (int) $hargaSlot,
                        'durasi_menit' => $durasiMenit,
                    ]);

                    $total += (int) $hargaSlot;
                }

                // 5️⃣ Update total bayar
                $pemesanan->update([
                    'total_bayar' => $total
                ]);

                return $pemesanan;
            });

            // kembalikan 201 dengan kode yang valid
            return response()->json([
                'kode' => $pemesanan->kode_pemesanan
            ], 201);
        } catch (\Throwable $e) {
            // supaya mudah debugging, tetap kembalikan pesan error (atau logkan terlebih dahulu di production)
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Halaman konfirmasi booking
     */
    public function bookingConfirm(string $kode)
    {
        $pemesanan = Pemesanan::where('kode_pemesanan', $kode)
            ->where('id_pengguna', auth()->id())
            ->where('status_pemesanan', Pemesanan::PENDING)
            ->where('expired_at', '>', now())
            ->firstOrFail();


        $jadwals = PemesananJadwal::with('jadwal.lapangan')
            ->where('id_pemesanan', $pemesanan->id_pemesanan)
            ->get();

        $lapangan = $jadwals->first()->jadwal->lapangan ?? null;
        $tanggal  = Carbon::parse(
            $jadwals->first()->jadwal->tanggal
        )->translatedFormat('l, d F Y');


        /** Merge slot berurutan + hitung durasi aman */
        $slots = $jadwals->sortBy('jadwal.jam_mulai');
        $mergedSlots = [];
        $current = null;

        foreach ($slots as $s) {
            $tanggal = $s->jadwal->tanggal;

            $start = Carbon::parse($tanggal . ' ' . $s->jadwal->jam_mulai);
            $end   = Carbon::parse($tanggal . ' ' . $s->jadwal->jam_selesai);

            // 🔥 Jika jam selesai < jam mulai → lewat tengah malam
            if ($end->lessThanOrEqualTo($start)) {
                $end->addDay();
            }

            $durasi = $start->diffInMinutes($end);

            if (!$current) {
                $current = [
                    'start' => $start,
                    'end'   => $end,
                    'durasi_menit' => $durasi,
                ];
            } elseif ($start->equalTo($current['end'])) {
                $current['end'] = $end;
                $current['durasi_menit'] += $durasi;
            } else {
                $mergedSlots[] = $current;
                $current = [
                    'start' => $start,
                    'end'   => $end,
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
            'tanggal'     => $tanggal,
        ]);
    }

    /**
     * Riwayat booking user
     */
    public function bookingHistory()
    {
        $bookings = Pemesanan::with([
            'detailJadwal.jadwal.lapangan'
        ])
            ->where('id_pengguna', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pelanggan.booking-history', compact('bookings'));
    }
}
