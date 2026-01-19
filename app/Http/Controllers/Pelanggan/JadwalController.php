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
}
