@extends('layouts.owner')

@section('title', 'Laporan Jadwal - Futsal ACR')

@section('content')

@php
use Carbon\Carbon;

/**
* Kirim dua field tanggal:
* - 'tanggal' => ISO 'YYYY-MM-DD' (dipakai untuk sort/search)
* - 'tanggal_label' => 'DD/MM/YYYY' (hanya untuk tampilan)
*/
$jadwalsJs = $jadwals->map(function($j) {
$tanggal = Carbon::parse($j->tanggal);
return [
'id_jadwal' => $j->id_jadwal,
'tanggal' => $tanggal->format('Y-m-d'), // dipakai untuk sort/search
'tanggal_label' => $tanggal->format('d/m/Y'), // dipakai untuk tampil
'jam_mulai' => $j->jam_mulai,
'jam_selesai' => $j->jam_selesai,
'lapangan' => [
'nama_lapangan' => $j->lapangan->nama_lapangan ?? '-'
],
'pemesanan_jadwal' => $j->pemesananJadwal ? [
'pemesanan' => [
'kode_pemesanan' => $j->pemesananJadwal->pemesanan->kode_pemesanan ?? '-',
'pengguna' => [
'nama' => $j->pemesananJadwal->pemesanan->pengguna->nama ?? '-'
]
]
] : null,
];
});

@endphp


<div
    class="space-y-1"
    x-data="Table(@js($jadwalsJs), {
        perPage: 10,
        searchKeys: [
            'lapangan.nama_lapangan',
            'tanggal',
            'tanggal_label',
            'jam_mulai',
            'jam_selesai',
            'pemesanan_jadwal.pemesanan.kode_pemesanan',
            'pemesanan_jadwal.pemesanan.pengguna.nama'
        ]
    })"
    x-key="{{ request()->fullUrl() }}">


    {{-- FLASH MESSAGE --}}
    @include('elements.flash-messages')

    <form method="GET"
        action="{{ route('owner.laporan.jadwal') }}"
        class="mb-6">

        <div class="grid grid-cols-1 gap-4
                sm:grid-cols-2
                lg:grid-cols-6
                items-end">

            {{-- TANGGAL DARI --}}
            <div class="lg:col-span-1">
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Tanggal Dari
                </label>
                <x-form.date-picker
                    id="tanggal_dari"
                    name="tanggal_dari"
                    placeholder="Tanggal awal"
                    defaultDate="{{ request('tanggal_dari') }}" />
            </div>

            {{-- TANGGAL SAMPAI --}}
            <div class="lg:col-span-1">
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Tanggal Sampai
                </label>
                <x-form.date-picker
                    id="tanggal_sampai"
                    name="tanggal_sampai"
                    placeholder="Tanggal akhir"
                    defaultDate="{{ request('tanggal_sampai') }}" />
            </div>

            {{-- LAPANGAN --}}
            <div class="lg:col-span-1">
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Lapangan
                </label>

                <div
                    x-data="{ isOptionSelected: {{ request('lapangan') ? 'true' : 'false' }} }"
                    class="relative">

                    <select
                        name="lapangan"
                        @change="isOptionSelected = true"
                        class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent
           px-4 py-2.5 pr-11 text-sm
           focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10"
                        :class="isOptionSelected ? 'text-gray-800' : 'text-gray-400'">

                        {{-- SEMUA LAPANGAN --}}
                        <option value="">
                            Semua Lapangan
                        </option>

                        @foreach ($lapangans as $lap)
                        <option value="{{ $lap->id_lapangan }}"
                            {{ request('lapangan') == $lap->id_lapangan ? 'selected' : '' }}>
                            {{ $lap->nama_lapangan }}
                        </option>
                        @endforeach
                    </select>

                    <span class="pointer-events-none absolute top-1/2 right-4 -translate-y-1/2 text-gray-500">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="lg:col-span-1">
                <label class="mb-1.5 block text-sm font-medium text-transparent select-none">
                    Filter
                </label>
                <button type="submit"
                    class="h-11 w-full rounded-lg bg-blue-500 text-sm font-medium text-white hover:bg-blue-600">
                    Filter
                </button>
            </div>

            {{-- RESET --}}
            <div class="lg:col-span-1">
                <label class="mb-1.5 block text-sm font-medium text-transparent select-none">
                    Reset
                </label>
                <a href="{{ route('owner.laporan.jadwal') }}"
                    class="h-11 w-full flex items-center justify-center rounded-lg
                       bg-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-300">
                    Reset
                </a>
            </div>

            {{-- EXPORT --}}
            <div class="lg:col-span-1">
                <label class="mb-1.5 block text-sm font-medium text-transparent select-none">
                    Export
                </label>
                <a href="{{ route('owner.laporan.jadwal.export', request()->all()) }}"
                    class="h-11 w-full flex items-center justify-center rounded-lg
                       bg-green-500 text-sm font-medium text-white hover:bg-green-600">
                    Export Excel
                </a>
            </div>

        </div>
    </form>





    {{-- CARD --}}
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- HEADER --}}
        <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Laporan Jadwal Lapangan
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
                            ['label' => 'Kode Pemesanan', 'key' => 'pemesanan_jadwal.pemesanan.kode_pemesanan'],
                            ['label' => 'Lapangan', 'key' => 'lapangan.nama_lapangan'],
                            ['label' => 'Jam', 'key' => 'jam_mulai'],
                            ['label' => 'Dibooking Oleh', 'key' => 'pemesanan_jadwal.pemesanan.pengguna.nama']
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

                                {{-- TANGGAL: tampilkan tanggal_label, tapi sort tetap pakai 'tanggal' (ISO) --}}
                                <td class="px-4 py-4 text-sm text-gray-600" x-text="item.tanggal_label"></td>

                                {{-- KODE PEMESAN --}}
                                <td class="px-4 py-4 text-sm text-gray-600" x-text="item.pemesanan_jadwal ? item.pemesanan_jadwal.pemesanan.kode_pemesanan : '-'"></td>

                                {{-- LAPANGAN --}}
                                <td class="px-4 py-4 font-medium text-gray-800 dark:text-white" x-text="item.lapangan.nama_lapangan"></td>

                                {{-- JAM --}}
                                <td class="px-4 py-4 text-sm text-gray-600" x-text="`${item.jam_mulai.slice(0,5)} - ${item.jam_selesai.slice(0,5)}`"></td>

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