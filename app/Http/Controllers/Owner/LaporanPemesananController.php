<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PemesananExport;
use Illuminate\Support\Facades\Schema;

class LaporanPemesananController extends Controller
{
    /**
     * Base query untuk laporan pemesanan (dipakai index & export)
     */
    private function baseQuery(Request $request)
    {
        $query = Pemesanan::with(['pengguna', 'lapangan', 'detailJadwal'])
            ->orderBy('created_at', 'desc');

        // FILTER TANGGAL (berdasarkan jadwal)
        if ($request->filled('tanggal_dari')) {
            $query->whereHas('detailJadwal', function ($q) use ($request) {
                $q->whereDate('tanggal', '>=', $request->tanggal_dari);
            });
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereHas('detailJadwal', function ($q) use ($request) {
                $q->whereDate('tanggal', '<=', $request->tanggal_sampai);
            });
        }

        // FILTER LAPANGAN
        if ($request->filled('lapangan')) {
            if (Schema::hasColumn('pemesanan', 'id_lapangan')) {
                $query->where('id_lapangan', $request->lapangan);
            } else {
                $query->whereHas('lapangan', function ($q) use ($request) {
                    $q->where('id_lapangan', $request->lapangan);
                });
            }
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status_pemesanan', $request->status);
        }

        return $query->distinct();
    }

    /**
     * INDEX LAPORAN
     */
    public function index(Request $request)
    {
        $pemesanan = $this->baseQuery($request)
            ->get()
            ->map(function ($item) {

                $jadwalText = [];
                $jadwalRaw  = [];

                foreach ($item->detailJadwal as $dj) {
                    $tanggal = Carbon::parse($dj->tanggal)->format('d/m/Y');
                    $jam     = substr($dj->jam_mulai, 0, 5) . ' - ' . substr($dj->jam_selesai, 0, 5);

                    $jadwalText[] = "$tanggal $jam";
                    $jadwalRaw[] = [
                        'tanggal' => $tanggal,
                        'jam' => $jam
                    ];
                }

                $item->detail_jadwal = implode(', ', $jadwalText);
                $item->detail_jadwal_raw = $jadwalRaw;

                $item->total_bayar = (int) ($item->total_bayar ?? 0);

                return $item;
            });

        return view('owner.laporan-pemesanan.index', [
            'pemesanan' => $pemesanan,
            'lapangans' => Lapangan::all(),
        ]);
    }

    /**
     * EXPORT EXCEL
     */
    public function exportExcel(Request $request)
    {
        $data = $this->baseQuery($request)->get();

        return Excel::download(
            new PemesananExport($data),
            'laporan_pemesanan.xlsx'
        );
    }
}
