@extends('layouts.owner')

@section('title', 'Laporan Transaksi - Futsal ACR')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
           Laporan Transaksi
        </h1>
    </div>

    @php
    $transaksisJs = $transaksisJs ?? collect();
    @endphp

    <div
        class="space-y-4"
        x-data="Table(@js($transaksisJs), {
        perPage: 10,
        searchKeys: [
            'tanggal_label',
            'kode_pemesanan',
            'nama_pelanggan',
            'nama_lapangan',
            'total_bayar_label',
            'status'
        ]
    })"
        x-init="
        periode = '{{ request('periode', 'bulanan') }}';
        if (periode !== 'custom') {
            document.getElementById('tanggal_dari_wrap')?.classList.add('hidden');
            document.getElementById('tanggal_sampai_wrap')?.classList.add('hidden');
        }
    ">

        @include('elements.flash-messages')

        <form method="GET" action="{{ route('owner.laporan.transaksi') }}" class="mb-6">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 items-end">

                <div class="lg:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Periode
                    </label>

                    <select
                        name="periode"
                        x-model="periode"
                        @change="
                        if (periode === 'custom') {
                            document.getElementById('tanggal_dari_wrap')?.classList.remove('hidden');
                            document.getElementById('tanggal_sampai_wrap')?.classList.remove('hidden');
                        } else {
                            document.getElementById('tanggal_dari_wrap')?.classList.add('hidden');
                            document.getElementById('tanggal_sampai_wrap')?.classList.add('hidden');
                        }
                    "
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10">

                        <option value="harian" {{ request('periode', 'bulanan') == 'harian' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="mingguan" {{ request('periode') == 'mingguan' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulanan" {{ request('periode', 'bulanan') == 'bulanan' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="tahunan" {{ request('periode') == 'tahunan' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="bulan_lalu" {{ request('periode') == 'bulan_lalu' ? 'selected' : '' }}>Bulan Lalu</option>
                        <option value="tahun_lalu" {{ request('periode') == 'tahun_lalu' ? 'selected' : '' }}>Tahun Lalu</option>
                        <option value="custom" {{ request('periode') == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>

                <div id="tanggal_dari_wrap" class="lg:col-span-2 {{ request('periode') == 'custom' ? '' : 'hidden' }}">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Tanggal Dari
                    </label>

                    <x-form.date-picker
                        id="tanggal_dari"
                        name="tanggal_dari"
                        placeholder="Tanggal awal"
                        defaultDate="{{ request('tanggal_dari') }}" />
                </div>

                <div id="tanggal_sampai_wrap" class="lg:col-span-2 {{ request('periode') == 'custom' ? '' : 'hidden' }}">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Tanggal Sampai
                    </label>

                    <x-form.date-picker
                        id="tanggal_sampai"
                        name="tanggal_sampai"
                        placeholder="Tanggal akhir"
                        defaultDate="{{ request('tanggal_sampai') }}" />
                </div>

                <div class="lg:col-span-6">
                    <label class="mb-1.5 block text-sm font-medium text-transparent select-none">
                        Action
                    </label>

                    <div class="flex flex-wrap gap-2">
                        <button
                            type="submit"
                            class="h-11 px-5 rounded-lg bg-blue-500 text-sm font-medium text-white hover:bg-blue-600">
                            Filter
                        </button>

                        <a
                            href="{{ route('owner.laporan.transaksi') }}"
                            class="h-11 px-5 flex items-center justify-center rounded-lg bg-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-300">
                            Reset
                        </a>

                        <a
                            href="{{ route('owner.laporan.transaksi.export', request()->all()) }}"
                            class="h-11 px-5 flex items-center justify-center rounded-lg bg-green-500 text-sm font-medium text-white hover:bg-green-600">
                            <i class="fa-solid fa-file-excel mr-2"></i>
                            Export Excel
                        </a>
                    </div>
                </div>

            </div>
        </form>

        {{-- SUMMARY CARD --}}
        <div class="w-full">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                Rekap {{ ucfirst($periode) }}

                {{-- TOTAL KEUANGAN --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-sm font-medium text-gray-500">
                        Total Keuangan
                    </p>

                    <h3 class="mt-3 text-3xl font-bold text-green-600">
                        Rp {{ number_format($totalKeuangan, 0, ',', '.') }}
                    </h3>
                </div>

                {{-- TOTAL BOOKING --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-sm font-medium text-gray-500">
                        Total Booking
                    </p>

                    <h3 class="mt-3 text-3xl font-bold text-blue-600">
                        {{ number_format($totalBooking) }}
                    </h3>
                </div>

                {{-- RATA-RATA --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-sm font-medium text-gray-500">
                        Rata-rata per Booking
                    </p>

                    <h3 class="mt-3 text-3xl font-bold text-purple-600">
                        Rp {{ number_format($rataRata, 0, ',', '.') }}
                    </h3>
                </div>

            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 mb-4 sm:px-6">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                    Rekap {{ ucfirst($periode) }}
                </h3>
            </div>

            <div class="overflow-hidden">
                <div class="max-w-full px-5 overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-y border-gray-200 dark:border-gray-700">
                                <th class="px-4 py-3 text-sm text-left text-gray-500">Periode</th>
                                <th class="px-4 py-3 text-sm text-left text-gray-500">Total Booking</th>
                                <th class="px-4 py-3 text-sm text-left text-gray-500">Total Transaksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($rekap as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $row['label'] }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $row['total_booking'] }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    Rp {{ number_format($row['total_transaksi'], 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                    Data rekap belum tersedia
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                    Detail Laporan Transaksi
                </h3>

                <div class="relative">
                    <span class="absolute -translate-y-1/2 left-4 top-1/2">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                    <input
                        type="text"
                        x-model.debounce.300ms="search"
                        placeholder="Cari..."
                        class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-10 pr-4 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 sm:w-[260px]" />
                </div>
            </div>

            <div class="overflow-hidden">
                <div class="max-w-full px-5 overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-y border-gray-200 dark:border-gray-700">
                                @php
                                $columns = [
                                ['label' => 'Tanggal Bayar', 'key' => 'tanggal'],
                                ['label' => 'Kode Pemesanan', 'key' => 'kode_pemesanan'],
                                ['label' => 'Nama Pelanggan', 'key' => 'nama_pelanggan'],
                                ['label' => 'Lapangan', 'key' => 'nama_lapangan'],
                                ['label' => 'Total Bayar', 'key' => 'total_bayar'],
                                ['label' => 'Status', 'key' => 'status'],
                                ];
                                @endphp

                                @foreach($columns as $col)
                                <th class="px-4 py-3 text-sm text-gray-500 text-left cursor-pointer" @click="sortBy('{{ $col['key'] }}')">
                                    {{ $col['label'] }}
                                    <span x-show="sortKey === '{{ $col['key'] }}'" class="ml-1">
                                        <template x-if="sortOrder === 'asc'">▲</template>
                                        <template x-if="sortOrder === 'desc'">▼</template>
                                    </span>
                                </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="item in paginated" :key="item.id_pemesanan">
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                    <td class="px-4 py-4 text-sm text-gray-600" x-text="item.tanggal_label"></td>
                                    <td class="px-4 py-4 text-sm text-gray-600" x-text="item.kode_pemesanan"></td>
                                    <td class="px-4 py-4 text-sm text-gray-800 font-medium" x-text="item.nama_pelanggan"></td>
                                    <td class="px-4 py-4 text-sm text-gray-600" x-text="item.nama_lapangan"></td>
                                    <td class="px-4 py-4 text-sm text-gray-600" x-text="item.total_bayar_label"></td>
                                    <td class="px-4 py-4 text-sm text-gray-600 capitalize" x-text="item.status"></td>
                                </tr>
                            </template>

                            <template x-if="paginated.length === 0">
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                        Data transaksi belum tersedia
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
                <div class="flex items-center justify-between">
                    <button
                        @click="prev"
                        :disabled="currentPage === 1"
                        :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''"
                        class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Prev
                    </button>

                    <ul class="flex items-center gap-1">
                        <template x-for="page in displayedPages" :key="page">
                            <li>
                                <button
                                    x-show="page !== '...'"
                                    @click="goToPage(page)"
                                    x-text="page"
                                    :class="currentPage === page ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100'"
                                    class="h-9 w-9 flex items-center justify-center rounded-lg text-sm font-medium"></button>

                                <span x-show="page === '...'" class="h-9 w-9 flex items-center justify-center text-gray-400">…</span>
                            </li>
                        </template>
                    </ul>

                    <button
                        @click="next"
                        :disabled="currentPage === totalPages"
                        :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''"
                        class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script src="{{ asset('js/table.js') }}"></script>
    @endpush