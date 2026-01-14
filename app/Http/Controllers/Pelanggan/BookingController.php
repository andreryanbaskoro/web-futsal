<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use App\Models\PemesananJadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Buat pemesanan baru (dipanggil via POST)
     */
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_ids' => 'required|array|min:1',
        ]);

        try {
            $pemesanan = DB::transaction(function () use ($request) {
                // Ambil jadwal & lapangan
                $jadwals = Jadwal::with('lapangan')
                    ->whereIn('id_jadwal', $request->jadwal_ids)
                    ->lockForUpdate()
                    ->get();

                if ($jadwals->isEmpty()) {
                    abort(400, 'Slot tidak valid');
                }

                $lapangan = $jadwals->first()->lapangan;

                // Gunakan tanggal jadwal pertama untuk ID pemesanan
                $jadwalTanggal = Carbon::parse($jadwals->first()->tanggal)->format('Ymd');

                // Cari booking terakhir di tanggal itu
                $last = Pemesanan::where('id_pemesanan', 'like', "FTS-{$jadwalTanggal}-%")
                    ->orderBy('id_pemesanan', 'desc')
                    ->first();

                $lastSeq = 0;
                if ($last) {
                    $lastSeq = (int) substr($last->id_pemesanan, -3);
                }
                $sequence = str_pad($lastSeq + 1, 3, '0', STR_PAD_LEFT);

                $idPemesanan = "FTS-{$jadwalTanggal}-{$sequence}";

                // Buat pemesanan
                $pemesanan = Pemesanan::create([
                    'id_pemesanan'      => $idPemesanan,
                    'kode_pemesanan'    => $idPemesanan,
                    'id_pengguna'       => auth()->id() ?? 1,
                    'id_lapangan'       => $lapangan->id_lapangan,
                    'id_jadwal'         => $jadwals->first()->id_jadwal,
                    'total_bayar'       => 0,
                    'status_pemesanan'  => 'pending',
                    'expired_at'        => now()->addMinutes(30),
                ]);

                // Buat detail jadwal
                foreach ($jadwals as $jadwal) {
                    $durasiMenit = Carbon::parse($jadwal->jam_mulai)
                        ->diffInMinutes(Carbon::parse($jadwal->jam_selesai));

                    $hari = strtolower(Carbon::parse($jadwal->tanggal)->translatedFormat('l'));

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
                }

                // Hitung total bayar
                $total = PemesananJadwal::where('id_pemesanan', $pemesanan->id_pemesanan)
                    ->sum('harga');

                $pemesanan->update(['total_bayar' => $total]);

                return $pemesanan;
            });

            // Kembalikan kode pemesanan untuk redirect JS
            return response()->json([
                'kode' => $pemesanan->kode_pemesanan
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Tampilkan halaman konfirmasi booking
     */
    public function bookingConfirm(string $kode)
    {
        $pemesanan = Pemesanan::where('kode_pemesanan', $kode)
            ->where('status_pemesanan', 'pending')
            ->firstOrFail();

        // Ambil PemesananJadwal dengan relasi jadwal & lapangan
        $jadwals = PemesananJadwal::with('jadwal.lapangan')
            ->where('id_pemesanan', $pemesanan->id_pemesanan)
            ->get();

        // Simpan lapangan & tanggal
        $lapangan = $jadwals->first()->jadwal->lapangan ?? null;
        $tanggal = Carbon::parse($jadwals->first()->jadwal->tanggal)->translatedFormat('l, d F Y');

        // Urutkan slot berdasarkan jam_mulai
        $slots = $jadwals->sortBy('jadwal.jam_mulai');

        // Gabungkan slot berurutan
        $mergedSlots = [];
        $current = null;
        foreach ($slots as $s) {
            $jamMulai = $s->jadwal->jam_mulai;
            $jamSelesai = $s->jadwal->jam_selesai;

            if (!$current) {
                $current = [
                    'start' => $jamMulai,
                    'end' => $jamSelesai,
                    'durasi_menit' => $s->durasi_menit,
                ];
            } else {
                if ($jamMulai === $current['end']) {
                    // Berurutan → gabungkan
                    $current['end'] = $jamSelesai;
                    $current['durasi_menit'] += $s->durasi_menit;
                } else {
                    // Tidak berurutan → simpan current dan mulai baru
                    $mergedSlots[] = $current;
                    $current = [
                        'start' => $jamMulai,
                        'end' => $jamSelesai,
                        'durasi_menit' => $s->durasi_menit,
                    ];
                }
            }
        }
        if ($current) $mergedSlots[] = $current;

        $total = $pemesanan->total_bayar;

        return view('pelanggan.booking-confirm', compact(
            'pemesanan',
            'mergedSlots', // gunakan ini di Blade
            'total',
            'lapangan',
            'tanggal'
        ));
    }



    /**
     * Halaman pembayaran
     */
    public function payment($kode)
    {
        $pemesanan = Pemesanan::where('kode_pemesanan', $kode)
            ->where('status_pemesanan', 'pending')
            ->firstOrFail();

        $jadwals = PemesananJadwal::with('jadwal.lapangan')
            ->where('id_pemesanan', $pemesanan->id_pemesanan)
            ->get();

        $lapangan = $jadwals->first()->jadwal->lapangan ?? null;
        $tanggal = Carbon::parse($jadwals->first()->jadwal->tanggal)->translatedFormat('l, d F Y');

        // Gabungkan slot berurutan seperti di bookingConfirm
        $slots = $jadwals->sortBy('jadwal.jam_mulai');
        $mergedSlots = [];
        $current = null;
        foreach ($slots as $s) {
            $jamMulai = $s->jadwal->jam_mulai;
            $jamSelesai = $s->jadwal->jam_selesai;

            if (!$current) {
                $current = [
                    'start' => $jamMulai,
                    'end' => $jamSelesai,
                    'durasi_menit' => $s->durasi_menit,
                ];
            } else {
                if ($jamMulai === $current['end']) {
                    $current['end'] = $jamSelesai;
                    $current['durasi_menit'] += $s->durasi_menit;
                } else {
                    $mergedSlots[] = $current;
                    $current = [
                        'start' => $jamMulai,
                        'end' => $jamSelesai,
                        'durasi_menit' => $s->durasi_menit,
                    ];
                }
            }
        }
        if ($current) $mergedSlots[] = $current;

        return view('pelanggan.pembayaran', compact(
            'pemesanan',
            'mergedSlots',
            'lapangan',
            'tanggal'
        ));
    }


    /**
     * Riwayat booking
     */
    public function bookingHistory()
    {
        $bookings = Pemesanan::orderBy('created_at', 'desc')->get();
        return view('pelanggan.booking-history', compact('bookings'));
    }
}
