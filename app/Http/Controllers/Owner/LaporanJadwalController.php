<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalExport;

class LaporanJadwalController extends Controller
{

    public function index(Request $request)
    {
        $query = Jadwal::with(['lapangan', 'pemesananJadwal.pemesanan.pengguna']);

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('lapangan')) {
            $query->whereHas('lapangan', function ($q) use ($request) {
                $q->where('id_lapangan', $request->lapangan);
            });
        }


        $jadwals = $query->orderBy('tanggal')->get();

        $lapangans = \App\Models\Lapangan::all();

        return view('owner.laporan-jadwal.index', compact('jadwals', 'lapangans'));
    }


    public function exportExcel(Request $request)
    {
        $jadwals = Jadwal::with(['lapangan', 'pemesananJadwal.pemesanan.pengguna'])
            ->when($request->tanggal_dari, fn($q) => $q->where('tanggal', '>=', $request->tanggal_dari))
            ->when($request->tanggal_sampai, fn($q) => $q->where('tanggal', '<=', $request->tanggal_sampai))
            ->when($request->filled('lapangan'), function ($q) use ($request) {
                $q->whereHas('lapangan', function ($qq) use ($request) {
                    $qq->where('id_lapangan', $request->lapangan);
                });
            })

            ->orderBy('tanggal')
            ->get();

        return Excel::download(new JadwalExport($jadwals), 'laporan_jadwal.xlsx');
    }
}
