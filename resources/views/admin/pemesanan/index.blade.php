@extends('layouts.admin')

@section('content')

<div
    class="space-y-1"
    x-data="Table(@js($pemesanan), {
        perPage: 10,
        searchKeys: ['kode_pemesanan', 'pengguna.nama', 'lapangan.nama_lapangan', 'jadwal', 'total_bayar', 'status_pemesanan']
    })">

    {{-- FLASH MESSAGE --}}
    @include('elements.flash-messages')

    {{-- CARD --}}
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- HEADER --}}
        <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Data Pemesanan
            </h3>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
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
        </div>

        {{-- TABLE --}}
        <div class="overflow-hidden">
            <div class="max-w-full px-5 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-y border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-sm text-gray-500 text-left">Kode</th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left">Pemesan</th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left">Lapangan</th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left">Jadwal</th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left">Total</th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left">Status</th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($pemesanan as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                            {{-- KODE --}}
                            <td class="px-4 py-4 text-sm text-gray-800 dark:text-white">
                                {{ $item->kode_pemesanan }}
                            </td>

                            {{-- PEMESAN --}}
                            <td class="px-4 py-4 text-sm text-gray-600">
                                {{ $item->pengguna->nama }}
                            </td>

                            {{-- LAPANGAN --}}
                            <td class="px-4 py-4 text-sm text-gray-600">
                                {{ $item->lapangan->nama_lapangan }}
                            </td>

                            {{-- JADWAL --}}
                            <td class="px-4 py-4 text-sm text-gray-600">
                                @foreach ($item->detailJadwal as $dj)
                                <div class="text-xs">
                                    {{ \Carbon\Carbon::parse($dj->tanggal)->format('d M Y') }}
                                    |
                                    {{ substr($dj->jam_mulai, 0, 5) }} - {{ substr($dj->jam_selesai, 0, 5) }}
                                </div>
                                @endforeach
                            </td>

                            {{-- TOTAL --}}
                            <td class="px-4 py-4 text-sm text-gray-600">
                                Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-4">
                                @php
                                $statusClass = match($item->status_pemesanan) {
                                'dibayar' => 'bg-green-50 text-green-600',
                                'pending' => 'bg-yellow-50 text-yellow-600',
                                'dibatalkan','kadaluarsa' => 'bg-red-50 text-red-600',
                                default => 'bg-gray-100 text-gray-600'
                                };
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucfirst($item->status_pemesanan) }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="px-4 py-4 text-end">
                                <a href="{{ route('admin.pemesanan.show', $item->id_pemesanan) }}"
                                    class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
            <div class="flex items-center justify-between">
                <button @click="prev" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
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

                <button @click="next" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
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