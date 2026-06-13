<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;

class LaporanTransaksiController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'bulanan');

        $query = Pemesanan::with(['pengguna', 'lapangan'])
            ->where('status_pemesanan', Pemesanan::DIBAYAR)
            ->whereNotNull('waktu_bayar');

        $this->applyPeriodeFilter($query, $request, $periode);

        $transaksis = $query->orderByDesc('waktu_bayar')->get();

        $totalKeuangan = $transaksis->sum('total_bayar');
        $totalBooking  = $transaksis->count();
        $rataRata      = $totalBooking > 0 ? $totalKeuangan / $totalBooking : 0;

        $rekap = $this->buildRekap($transaksis, $periode);

        $transaksisJs = $transaksis->map(function ($t) {
            $tanggal = Carbon::parse($t->waktu_bayar);

            return [
                'id_pemesanan'      => $t->id_pemesanan,
                'tanggal'           => $tanggal->format('Y-m-d H:i:s'),
                'tanggal_label'     => $tanggal->format('d/m/Y H:i'),
                'kode_pemesanan'    => $t->kode_pemesanan ?? '-',
                'nama_pelanggan'    => $t->pengguna->nama ?? '-',
                'nama_lapangan'     => $t->lapangan->nama_lapangan ?? '-',
                'total_bayar'       => (int) $t->total_bayar,
                'total_bayar_label' => 'Rp ' . number_format((int) $t->total_bayar, 0, ',', '.'),
                'status'            => $t->status_pemesanan,
            ];
        })->values();

        return view('owner.laporan-transaksi.index', compact(
            'transaksis',
            'transaksisJs',
            'totalKeuangan',
            'totalBooking',
            'rataRata',
            'rekap',
            'periode'
        ));
    }

    public function exportExcel(Request $request)
    {
        $periode = $request->get('periode', 'bulanan');

        $query = Pemesanan::with(['pengguna', 'lapangan'])
            ->where('status_pemesanan', Pemesanan::DIBAYAR)
            ->whereNotNull('waktu_bayar');

        $this->applyPeriodeFilter($query, $request, $periode);

        $transaksis = $query->orderByDesc('waktu_bayar')->get();

        return Excel::download(
            new TransaksiExport($transaksis, $periode),
            'laporan_transaksi_' . now()->format('YmdHis') . '.xlsx'
        );
    }

    private function applyPeriodeFilter($query, Request $request, string $periode)
    {
        $today = Carbon::today();

        switch ($periode) {
            case 'harian':
                $query->whereDate('waktu_bayar', $today);
                break;

            case 'mingguan':
                $query->whereBetween('waktu_bayar', [
                    $today->copy()->startOfWeek(),
                    $today->copy()->endOfWeek(),
                ]);
                break;

            case 'bulanan':
                $query->whereBetween('waktu_bayar', [
                    $today->copy()->startOfMonth(),
                    $today->copy()->endOfMonth(),
                ]);
                break;

            case 'tahunan':
                $query->whereBetween('waktu_bayar', [
                    $today->copy()->startOfYear(),
                    $today->copy()->endOfYear(),
                ]);
                break;

            case 'bulan_lalu':
                $bulanLalu = $today->copy()->subMonthNoOverflow();
                $query->whereBetween('waktu_bayar', [
                    $bulanLalu->copy()->startOfMonth(),
                    $bulanLalu->copy()->endOfMonth(),
                ]);
                break;

            case 'tahun_lalu':
                $tahunLalu = $today->copy()->subYear();
                $query->whereBetween('waktu_bayar', [
                    $tahunLalu->copy()->startOfYear(),
                    $tahunLalu->copy()->endOfYear(),
                ]);
                break;

            case 'custom':
                if ($request->filled('tanggal_dari')) {
                    $query->whereDate('waktu_bayar', '>=', $request->tanggal_dari);
                }

                if ($request->filled('tanggal_sampai')) {
                    $query->whereDate('waktu_bayar', '<=', $request->tanggal_sampai);
                }
                break;

            default:
                $query->whereBetween('waktu_bayar', [
                    $today->copy()->startOfMonth(),
                    $today->copy()->endOfMonth(),
                ]);
                break;
        }
    }

    private function buildRekap($transaksis, string $periode)
    {
        return $transaksis->groupBy(function ($item) use ($periode) {
            $tanggal = Carbon::parse($item->waktu_bayar);

            return match ($periode) {
                'mingguan' => $tanggal->format('o-\WW'),
                'bulanan'  => $tanggal->format('Y-m'),
                'tahunan'  => $tanggal->format('Y'),
                'bulan_lalu' => $tanggal->format('Y-m'),
                'tahun_lalu'  => $tanggal->format('Y'),
                default    => $tanggal->format('Y-m-d'),
            };
        })->map(function ($items) use ($periode) {
            $first = Carbon::parse($items->first()->waktu_bayar);

            $label = match ($periode) {
                'mingguan' => 'Minggu ' . $first->format('W') . ' - ' . $first->format('Y'),
                'bulanan', 'bulan_lalu' => $first->translatedFormat('F Y'),
                'tahunan', 'tahun_lalu' => $first->format('Y'),
                default => $first->format('d/m/Y'),
            };

            return [
                'label'          => $label,
                'total_booking'   => $items->count(),
                'total_transaksi' => $items->sum('total_bayar'),
            ];
        })->values();
    }
}
