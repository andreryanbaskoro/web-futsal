<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lapangan;
use App\Models\JamOperasional;
use App\Models\Jadwal;
use App\Models\Pemesanan;
use App\Models\PemesananJadwal;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::where('status', 'aktif')
            ->orderBy('nama_lapangan')
            ->get();

        return view('pelanggan.jadwal', compact('lapangans'));
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

        // Ambil id_jadwal yang sudah dipesan (menggunakan relasi model PemesananJadwal)
        $bookedJadwalIds = PemesananJadwal::whereHas('pemesanan', function ($q) use ($request) {
            $q->where('id_lapangan', $request->id_lapangan)
                ->whereNotIn('status_pemesanan', ['dibatalkan', 'kadaluarsa']); // sesuaikan nama status jika berbeda
        })
            ->whereHas('jadwal', function ($q) use ($tanggal) {
                $q->whereDate('tanggal', $tanggal->toDateString());
            })
            ->distinct()
            ->pluck('id_jadwal')
            ->toArray();

        $operasionals = JamOperasional::where('id_lapangan', $request->id_lapangan)
            ->where('hari', $hari)
            ->get();

        if ($operasionals->isEmpty()) {
            return response()->json([]);
        }

        $slots = [];
        $now = Carbon::now(); // waktu saat ini untuk perbandingan

        foreach ($operasionals as $op) {
            $start = Carbon::createFromFormat('H:i:s', $op->jam_buka);
            $end   = Carbon::createFromFormat('H:i:s', $op->jam_tutup);

            while ($start->lt($end)) {
                $jamMulai   = $start->format('H:i:s');
                $jamSelesai = $start->copy()->addMinutes($op->interval_menit)->format('H:i:s');

                $jadwal = Jadwal::firstOrCreate(
                    [
                        'id_lapangan' => $request->id_lapangan,
                        'tanggal'     => $tanggal->toDateString(),
                        'jam_mulai'   => $jamMulai,
                    ],
                    [
                        'jam_selesai' => $jamSelesai,
                    ]
                );

                // Periksa apakah jadwal sudah lewat
                $isPast = Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam_mulai)->lt($now);

                $slots[] = [
                    'id_jadwal'    => $jadwal->id_jadwal,
                    'jam_mulai'    => substr($jamMulai, 0, 5),
                    'jam_selesai'  => substr($jamSelesai, 0, 5),
                    'jam'          => substr($jamMulai, 0, 5) . ' - ' . substr($jamSelesai, 0, 5),
                    'harga' => round(($op->harga / 60) * $op->interval_menit, 0),
                    'durasi_menit' => $op->interval_menit,
                    'booked'       => in_array($jadwal->id_jadwal, $bookedJadwalIds),
                    'past'         => $isPast,  // Tandai apakah slot sudah lewat
                ];

                $start->addMinutes($op->interval_menit);
            }
        }

        return response()->json($slots);
    }
}
