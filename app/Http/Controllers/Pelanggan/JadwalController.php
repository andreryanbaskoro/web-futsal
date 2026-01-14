<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lapangan;
use App\Models\JamOperasional;
use App\Models\Jadwal;
use App\Models\Pemesanan;
use Carbon\Carbon;

class JadwalController extends Controller
{
    /**
     * Halaman utama jadwal
     */
    public function index()
    {
        $lapangans = Lapangan::where('status', 'aktif')->orderBy('nama_lapangan')->get();
        return view('pelanggan.jadwal', compact('lapangans'));
    }

    /**
     * Ambil slot jadwal berdasarkan lapangan & tanggal
     * URL: /pelanggan/jadwal/slots
     */
    public function slots(Request $request)
    {
        $request->validate([
            'id_lapangan' => 'required',
            'tanggal' => 'required|date',
        ]);

        $tanggal = Carbon::parse($request->tanggal);
        $hariMap = [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            7 => 'minggu'
        ];
        $hari = $hariMap[$tanggal->dayOfWeekIso];

        $hari = $hariMap[$tanggal->dayOfWeekIso];

        $operasionals = JamOperasional::where('id_lapangan', $request->id_lapangan)
            ->where('hari', $hari)
            ->get();

        if ($operasionals->isEmpty()) {
            return response()->json([]);
        }

        $slots = [];

        foreach ($operasionals as $op) {
            $start = Carbon::createFromFormat('H:i:s', $op->jam_buka);
            $end   = Carbon::createFromFormat('H:i:s', $op->jam_tutup);

            while ($start->lt($end)) {
                $jamMulai = $start->format('H:i:s');
                $jamSelesai = $start->copy()->addMinutes($op->interval_menit)->format('H:i:s');

                // 🔥 pakai MODEL Jadwal Anda (ID otomatis!)
                $jadwal = Jadwal::firstOrCreate([
                    'id_lapangan' => $request->id_lapangan,
                    'tanggal'     => $tanggal->toDateString(), // ini harus Carbon Y-m-d
                    'jam_mulai'   => $jamMulai,
                ], [
                    'jam_selesai' => $jamSelesai,
                ]);


                $slots[] = [
                    'id_jadwal'    => $jadwal->id_jadwal,
                    'jam_mulai'    => substr($jamMulai, 0, 5),
                    'jam_selesai'  => substr($jamSelesai, 0, 5),
                    'jam'          => substr($jamMulai, 0, 5) . ' - ' . substr($jamSelesai, 0, 5),
                    'harga'        => (int) (($op->harga / 60) * $op->interval_menit),
                    'durasi_menit' => $op->interval_menit,
                    'status'       => $jadwal->isTersedia() ? 'tersedia' : 'dipesan',
                ];



                $start->addMinutes($op->interval_menit);
            }
        }

        return response()->json($slots);
    }
}
