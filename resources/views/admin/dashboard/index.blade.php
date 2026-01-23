@extends('layouts.admin')

@section('content')

{{-- Metric Cards --}}
<div class="grid grid-cols-4 sm:grid-cols-3 md:grid-cols-4 gap-5 mb-6">
    {{-- Total Lapangan --}}
    <x-common.component-card title="Lapangan">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Lapangan::count() }}</h4>
                <span class="text-sm text-gray-500 dark:text-gray-400">Aktif</span>
            </div>
            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-gray-100 dark:bg-gray-800">
                <i class="fas fa-futbol text-xl text-gray-800 dark:text-white"></i>
            </div>
        </div>
    </x-common.component-card>

    {{-- Total Pemesanan (Terbayar) --}}
    <x-common.component-card title="Pemesanan Terbayar">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ \App\Models\Pemesanan::where('status_pemesanan', 'dibayar')->count() }}
                </h4>
                <span class="text-sm text-gray-500 dark:text-gray-400">Hanya yang dibayar</span>
            </div>
            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-gray-100 dark:bg-gray-800">
                <i class="fas fa-receipt text-xl text-gray-800 dark:text-white"></i>
            </div>
        </div>
    </x-common.component-card>

    {{-- Total Pendapatan (Terbayar) --}}
    <x-common.component-card title="Pendapatan Terbayar">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format(\App\Models\Pemesanan::where('status_pemesanan', 'dibayar')->sum('total_bayar'), 0, ',', '.') }}
                </h4>
                <span class="text-sm text-gray-500 dark:text-gray-400">Hanya pembayaran berhasil</span>
            </div>
            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-gray-100 dark:bg-gray-800">
                <i class="fas fa-wallet text-xl text-gray-800 dark:text-white"></i>
            </div>
        </div>
    </x-common.component-card>

</div>

{{-- Latest Transactions Table (Terbayar Saja) --}}
<div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4 shadow-sm">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Latest Paid Bookings</h3>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm text-gray-700 dark:text-gray-300">
            <thead class="border-b border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-xs">
                <tr>
                    <th class="px-3 py-2">Kode</th>
                    <th class="px-3 py-2">Lapangan</th>
                    <th class="px-3 py-2">Tanggal</th>
                    <th class="px-3 py-2">Total</th>
                    <th class="px-3 py-2">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach(\App\Models\Pemesanan::where('status_pemesanan', 'dibayar')->latest()->take(10)->get() as $pemesanan)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <td class="px-3 py-2 font-medium">{{ $pemesanan->kode_pemesanan }}</td>
                    <td class="px-3 py-2">{{ $pemesanan->lapangan->nama_lapangan ?? '-' }}</td>
                    <td class="px-3 py-2">
                        {{ optional($pemesanan->detailJadwal->first())->tanggal?->format('d M Y') ?? '-' }}
                    </td>

                    <td class="px-3 py-2 font-semibold">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</td>
                    <td class="px-3 py-2">
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-600/20 dark:text-green-400">
                            Dibayar
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection