@extends('layouts.admin')

@section('content')

<div class="space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">
                Detail Pemesanan
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Kode Pemesanan:
                <span class="font-medium text-gray-700 dark:text-gray-200">
                    {{ $pemesanan->kode_pemesanan }}
                </span>
            </p>
        </div>

        <a href="{{ route('admin.pemesanan.index') }}"
            class="px-4 py-2 text-sm rounded-lg
                  bg-gray-100 text-gray-700 hover:bg-gray-200
                  dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
            ← Kembali
        </a>
    </div>

    {{-- ================= INFORMASI PEMESAN & LAPANGAN ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- PEMESAN --}}
        <div class="rounded-2xl border border-gray-200 bg-white
                    dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <h3 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
                Informasi Pemesan
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Nama</span>
                    <span class="font-medium text-gray-700 dark:text-gray-200">
                        {{ $pemesanan->pengguna->nama }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Email</span>
                    <span class="font-medium text-gray-700 dark:text-gray-200">
                        {{ $pemesanan->pengguna->email }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Tanggal Pesan</span>
                    <span class="font-medium text-gray-700 dark:text-gray-200">
                        {{ $pemesanan->created_at->format('d M Y H:i') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- LAPANGAN --}}
        <div class="rounded-2xl border border-gray-200 bg-white
                    dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <h3 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
                Informasi Lapangan
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Lapangan</span>
                    <span class="font-medium text-gray-700 dark:text-gray-200">
                        {{ $pemesanan->lapangan->nama_lapangan }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Total Bayar</span>
                    <span class="font-semibold text-blue-600 dark:text-blue-400">
                        Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-500 dark:text-gray-400">Status</span>

                    @php
                    $statusClass = match($pemesanan->status_pemesanan) {
                    'dibayar' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                    'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
                    'dibatalkan','kadaluarsa' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                    default => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300'
                    };
                    @endphp

                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                        {{ ucfirst($pemesanan->status_pemesanan) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= JADWAL BOOKING ================= --}}
    <div class="rounded-2xl border border-gray-200 bg-white
                dark:border-gray-800 dark:bg-white/[0.03] p-6">
        <h3 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
            Jadwal Booking
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-3 py-2 text-left text-gray-500 dark:text-gray-400">Tanggal</th>
                        <th class="px-3 py-2 text-left text-gray-500 dark:text-gray-400">Jam</th>
                        <th class="px-3 py-2 text-left text-gray-500 dark:text-gray-400">Durasi</th>
                        <th class="px-3 py-2 text-left text-gray-500 dark:text-gray-400">Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                    $totalDurasi = 0;
                    $totalHarga = 0;
                    @endphp
                    @foreach ($pemesanan->detailJadwal as $dj)
                    @php
                    $totalDurasi += $dj->durasi_menit;
                    $totalHarga += $dj->harga;
                    @endphp
                    <tr>
                        <td class="px-3 py-2 text-gray-700 dark:text-gray-200">
                            {{ \Carbon\Carbon::parse($dj->tanggal)->format('d M Y') }}
                        </td>
                        <td class="px-3 py-2 text-gray-700 dark:text-gray-200">
                            {{ substr($dj->jam_mulai, 0, 5) }} -
                            {{ substr($dj->jam_selesai, 0, 5) }}
                        </td>
                        <td class="px-3 py-2 text-gray-700 dark:text-gray-200">
                            {{ $dj->durasi_menit }} menit
                        </td>
                        <td class="px-3 py-2 text-gray-700 dark:text-gray-200">
                            Rp {{ number_format($dj->harga, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-white/[0.05]">
                    <tr>
                        <td colspan="2" class="px-3 py-2 font-semibold text-gray-700 dark:text-gray-200 text-right">
                            Total
                        </td>
                        <td class="px-3 py-2 font-semibold text-gray-700 dark:text-gray-200">
                            {{ $totalDurasi }} menit
                        </td>
                        <td class="px-3 py-2 font-semibold text-gray-700 dark:text-gray-200">
                            Rp {{ number_format($totalHarga, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

@endsection