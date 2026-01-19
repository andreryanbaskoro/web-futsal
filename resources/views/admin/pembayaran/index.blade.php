@extends('layouts.admin')

@section('content')

<div
    class="space-y-1"
    x-data="Table(@js($pembayaran), {
        perPage: 10,
        searchKeys: [
            'kode_pemesanan',
            'nama_pengguna',
            'status_transaksi',
            'tipe_pembayaran',
            'waktu_bayar',
            'total_bayar'
        ]
    })">

    {{-- FLASH MESSAGE --}}
    @include('elements.flash-messages')

    {{-- CARD --}}
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- HEADER --}}
        <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Data Pembayaran
            </h3>

            {{-- SEARCH --}}
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

        {{-- TABLE --}}
        <div class="overflow-hidden">
            <div class="max-w-full px-5 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-y border-gray-200 dark:border-gray-700">
                            @php
                            $columns = [
                            ['label' => 'No', 'key' => null],
                            ['label' => 'Kode Pemesanan', 'key' => 'kode_pemesanan'],
                            ['label' => 'Pemesan', 'key' => 'nama_pengguna'],
                            ['label' => 'Total Bayar', 'key' => 'total_bayar'],
                            ['label' => 'Tanggal Bayar', 'key' => 'waktu_bayar'],
                            ['label' => 'Status Transaksi', 'key' => 'status_transaksi'],
                            ['label' => 'Tipe Pembayaran', 'key' => 'tipe_pembayaran'],
                            ];
                            @endphp

                            @foreach($columns as $col)
                            <th
                                class="px-4 py-3 text-sm text-gray-500 text-left {{ $col['key'] ? 'cursor-pointer select-none' : '' }}"
                                @if($col['key'])
                                @click="sortBy('{{ $col['key'] }}')"
                                data-sort="{{ $col['key'] }}"
                                @endif>
                                {{ $col['label'] }}
                            </th>
                            @endforeach

                        </tr>
                    </thead>


                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                        <template x-for="(item, index) in paginated" :key="item.id_pemesanan">
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                                {{-- NO --}}
                                <td class="px-4 py-4 text-sm text-gray-500"
                                    x-text="(currentPage - 1) * perPage + index + 1">
                                </td>

                                {{-- KODE --}}
                                <td class="px-4 py-4 text-sm font-medium text-gray-800 dark:text-white"
                                    x-text="item.kode_pemesanan">
                                </td>

                                {{-- PEMESAN --}}
                                <td class="px-4 py-4 text-sm text-gray-600"
                                    x-text="item.nama_pengguna ?? '-'">
                                </td>

                                {{-- TOTAL --}}
                                <td class="px-4 py-4 text-sm text-gray-600"
                                    x-text="`Rp ${Number(item.total_bayar).toLocaleString('id-ID')}`">
                                </td>

                                {{-- TANGGAL BAYAR --}}
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    <template x-if="item.waktu_bayar">
                                        <span x-text="item.waktu_bayar"></span>
                                    </template>
                                    <template x-if="!item.waktu_bayar">
                                        <span class="italic text-gray-400">-</span>
                                    </template>
                                </td>

                                {{-- STATUS PEMESANAN --}}
                                {{-- STATUS TRANSAKSI --}}
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                        :class="{
            'bg-gray-100 text-gray-600': item.status_transaksi === 'belum_bayar',
            'bg-yellow-50 text-yellow-600': item.status_transaksi === 'pending',
            'bg-green-50 text-green-600': ['capture','settlement'].includes(item.status_transaksi),
            'bg-red-50 text-red-600': ['deny','cancel','expire'].includes(item.status_transaksi)
        }"
                                        x-text="(() => {
            const map = {
                belum_bayar: 'Menunggu Pembayaran',
                pending: 'Menunggu Pembayaran',
                capture: 'Pembayaran Berhasil',
                settlement: 'Pembayaran Berhasil',
                deny: 'Pembayaran Ditolak',
                cancel: 'Dibatalkan',
                expire: 'Kedaluwarsa'
            };
            return map[item.status_transaksi] ?? item.status_transaksi;
        })()">
                                    </span>
                                </td>


                                {{-- TIPE PEMBAYARAN --}}
                                <td class="px-4 py-4 text-sm text-gray-600"
                                    x-text="item.tipe_pembayaran ?? '-'">
                                </td>

                            </tr>
                        </template>

                        <tr x-show="paginated.length === 0">
                            <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                                Data pembayaran tidak ditemukan
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
            <div class="flex items-center justify-between">
                <button @click="prev"
                    :disabled="currentPage === 1"
                    :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''"
                    class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Prev
                </button>

                <ul class="flex items-center gap-1">
                    <template x-for="page in displayedPages" :key="page">
                        <li>
                            <button x-show="page !== '...'" @click="goToPage(page)" x-text="page"
                                :class="currentPage === page ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100'"
                                class="h-9 w-9 flex items-center justify-center rounded-lg text-sm font-medium"></button>
                            <span x-show="page === '...'" class="h-9 w-9 flex items-center justify-center text-gray-400">…</span>
                        </li>
                    </template>
                </ul>

                <button @click="next"
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

@push('modals')
@include('elements.modal-hapus')
@endpush