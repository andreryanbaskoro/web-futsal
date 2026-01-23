<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\PemesananJadwal;
use App\Models\Jadwal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        return view('owner.dashboard.index', [
            'title' => 'Dashboard Owner',

            // TOTAL PEMESANAN (kode)
            'totalPemesanan' => Pemesanan::count(),

            // TOTAL DIBAYAR (uang)
            'totalDibayar' => Pemesanan::where('status_pemesanan', 'dibayar')
                ->sum('total_bayar'),

            // 🔥 PENDING PEMESANAN HARI INI (DISTINCT ID_PEMESANAN)
            'pemesananPendingHariIni' => PemesananJadwal::whereDate('tanggal', $today)
                ->whereHas('pemesanan', function ($q) {
                    $q->where('status_pemesanan', Pemesanan::PENDING);
                })
                ->count(),

            // TOTAL JADWAL HARI INI
            'jadwalHariIni' => Jadwal::whereDate('tanggal', $today)->count(),
        ]);
    }
}
