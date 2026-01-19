@extends('layouts.admin')

@section('content')

<div
    class="space-y-1"
    x-data="Table(@js($jadwal), {
        perPage: 10,
        searchKeys: ['lapangan.nama_lapangan', 'tanggal', 'jam_mulai', 'jam_selesai', 'status']
    })">

    {{-- FLASH MESSAGE --}}
    @include('elements.flash-messages')

    {{-- CARD --}}
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- HEADER --}}
        <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Jadwal Lapangan
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
                            @php
                            $columns = [
                            ['label' => 'Tanggal', 'key' => 'tanggal'],
                            ['label' => 'Kode Pemesanan', 'key' => 'pemesananJadwal.pemesanan.kode_pemesanan'],
                            ['label' => 'Lapangan', 'key' => 'lapangan.nama_lapangan'],
                            ['label' => 'Jam', 'key' => 'jam_mulai'],
                            ['label' => 'Dibooking Oleh', 'key' => 'pemesananJadwal.pemesanan.pengguna.nama']
                            ];
                            @endphp

                            @foreach($columns as $col)
                            <th
                                class="px-4 py-3 text-sm text-gray-500 text-left cursor-pointer"
                                @click="sortBy('{{ $col['key'] }}')"
                                data-sort="{{ $col['key'] }}">
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
                        <template x-for="(item, index) in paginated" :key="item.id_jadwal">
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                                {{-- TANGGAL --}}
                                <td class="px-4 py-4 text-sm text-gray-600" x-text="new Date(item.tanggal).toLocaleDateString()"></td>

                                {{-- KODE PEMESAN --}}
                                <td class="px-4 py-4 text-sm text-gray-600" x-text="item.pemesanan_jadwal ? item.pemesanan_jadwal.pemesanan.kode_pemesanan : '-'"></td>

                                {{-- LAPANGAN --}}
                                <td class="px-4 py-4 font-medium text-gray-800 dark:text-white" x-text="item.lapangan.nama_lapangan"></td>

                                {{-- JAM --}}
                                <td class="px-4 py-4 text-sm text-gray-600" x-text="`${item.jam_mulai.slice(0, 5)} - ${item.jam_selesai.slice(0, 5)}`"></td>

                                {{-- PEMESAN --}}
                                <td class="px-4 py-4 text-sm text-gray-600" x-text="item.pemesanan_jadwal ? item.pemesanan_jadwal.pemesanan.pengguna.nama : '-'"></td>



                            </tr>
                        </template>

                        <tr x-show="paginated.length === 0">
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                Data jadwal belum tersedia
                            </td>
                        </tr>
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