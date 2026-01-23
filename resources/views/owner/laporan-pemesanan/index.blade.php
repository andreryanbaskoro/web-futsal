@extends('layouts.owner')

@section('title', 'Laporan Pemesanan - Futsal ACR')

@section('content')

@php
use Carbon\Carbon;

/**
* Data siap untuk Table.js
*/
$pemesananJs = $pemesanan->map(function ($p) {

$jadwalText = [];
$jadwalRaw = [];

foreach ($p->detailJadwal as $dj) {
$tanggal = Carbon::parse($dj->tanggal)->format('d/m/Y');
$jam = substr($dj->jam_mulai,0,5).' - '.substr($dj->jam_selesai,0,5);

$jadwalText[] = "$tanggal $jam";
$jadwalRaw[] = [
'tanggal' => $tanggal,
'jam' => $jam
];
}

return [
'id_pemesanan' => $p->id_pemesanan,
'kode_pemesanan' => $p->kode_pemesanan,
'pengguna' => [
'nama' => $p->pengguna->nama ?? '-'
],
'lapangan' => [
'nama_lapangan' => $p->lapangan->nama_lapangan ?? '-'
],
'detail_jadwal' => implode(', ', $jadwalText), // untuk sort/search
'detail_jadwal_raw' => $jadwalRaw, // untuk tampilan
'total_bayar' => $p->total_bayar,
'status_pemesanan' => $p->status_pemesanan,
];
});
@endphp

<div
    class="space-y-1"
    x-data="Table(@js($pemesananJs), {
        perPage: 10,
        searchKeys: [
            'kode_pemesanan',
            'pengguna.nama',
            'lapangan.nama_lapangan',
            'detail_jadwal',
            'status_pemesanan',
            'total_bayar'
        ]
    })"
    x-key="{{ request()->fullUrl() }}">

    {{-- FLASH --}}
    @include('elements.flash-messages')

    <form method="GET"
        action="{{ route('owner.laporan.pemesanan') }}"
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

            {{-- STATUS PEMESANAN --}}
            <div class="lg:col-span-1">
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Status
                </label>

                <div
                    x-data="{ isOptionSelected: {{ request('status') ? 'true' : 'false' }} }"
                    class="relative">

                    <select
                        name="status"
                        @change="isOptionSelected = true"
                        class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent
                   px-4 py-2.5 pr-11 text-sm
                   focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10"
                        :class="isOptionSelected ? 'text-gray-800' : 'text-gray-400'">

                        <option value="">Semua Status</option>

                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>
                            Dibayar
                        </option>

                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>
                            Dibatalkan
                        </option>

                        <option value="kadaluarsa" {{ request('status') == 'kadaluarsa' ? 'selected' : '' }}>
                            Kadaluarsa
                        </option>
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
                <a href="{{ route('owner.laporan.pemesanan') }}"
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
                <a href="{{ route('owner.laporan.pemesanan.export', request()->all()) }}"
                    class="h-11 w-full flex items-center justify-center rounded-lg
                       bg-green-500 text-sm font-medium text-white hover:bg-green-600">
                    Export Excel
                </a>
            </div>

        </div>
    </form>



    {{-- CARD --}}
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800">

        {{-- HEADER --}}
        <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <h3 class="text-xl font-semibold text-gray-800">
                Laporan Pemesanan
            </h3>

            <div class="relative">
                <span class="absolute -translate-y-1/2 left-4 top-1/2">
                    <i class="fas fa-search text-gray-400"></i>
                </span>
                <input
                    type="text"
                    x-model.debounce.300ms="search"
                    placeholder="Cari..."
                    class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent
           py-2.5 pl-10 pr-4 text-sm text-gray-800
           focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/10
           sm:w-[260px]" />
            </div>
        </div>

        {{-- TABLE --}}
        <div class="overflow-hidden">
            <div class="max-w-full px-5 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-y border-gray-200">
                            @php
                            $columns = [
                            ['label' => 'No', 'key' => null],
                            ['label' => 'Kode', 'key' => 'kode_pemesanan'],
                            ['label' => 'Pemesan', 'key' => 'pengguna.nama'],
                            ['label' => 'Lapangan', 'key' => 'lapangan.nama_lapangan'],
                            ['label' => 'Jadwal', 'key' => 'detail_jadwal'],
                            ['label' => 'Total', 'key' => 'total_bayar'],
                            ['label' => 'Status', 'key' => 'status_pemesanan'],
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

                            <th class="px-4 py-3 text-sm text-gray-500 text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        <template x-for="(item, index) in paginated" :key="item.id_pemesanan">
                            <tr class="hover:bg-gray-50">

                                {{-- NO --}}
                                <td class="px-4 py-4 text-sm text-gray-500"
                                    x-text="(currentPage - 1) * perPage + index + 1"></td>

                                {{-- KODE --}}
                                <td class="px-4 py-4 text-sm font-medium text-gray-800"
                                    x-text="item.kode_pemesanan"></td>

                                {{-- PEMESAN --}}
                                <td class="px-4 py-4 text-sm text-gray-600"
                                    x-text="item.pengguna.nama"></td>

                                {{-- LAPANGAN --}}
                                <td class="px-4 py-4 text-sm text-gray-600"
                                    x-text="item.lapangan.nama_lapangan"></td>

                                {{-- JADWAL --}}
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    <template x-for="(jadwal, i) in item.detail_jadwal_raw" :key="i">
                                        <div class="text-xs">
                                            <span x-text="jadwal.tanggal"></span> |
                                            <span x-text="jadwal.jam"></span>
                                        </div>
                                    </template>
                                </td>

                                {{-- TOTAL --}}
                                <td class="px-4 py-4 text-sm text-gray-600"
                                    x-text="`Rp ${Number(item.total_bayar).toLocaleString('id-ID')}`"></td>

                                {{-- STATUS --}}
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                        :class="{
                                            'bg-green-50 text-green-600': item.status_pemesanan === 'dibayar',
                                            'bg-yellow-50 text-yellow-600': item.status_pemesanan === 'pending',
                                            'bg-red-50 text-red-600': ['dibatalkan','kadaluarsa'].includes(item.status_pemesanan)
                                        }"
                                        x-text="item.status_pemesanan">
                                    </span>
                                </td>

                                {{-- AKSI --}}
                                <td class="px-4 py-4 text-end">
                                    <a :href="`/owner/pemesanan/${item.id_pemesanan}`"
                                        class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="paginated.length === 0">
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                Data pemesanan tidak ditemukan
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">

                    {{-- PREV --}}
                    <button
                        @click="prev"
                        :disabled="currentPage === 1"
                        :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''"
                        class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white
                   px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Prev
                    </button>

                    {{-- PAGE NUMBER --}}
                    <ul class="flex items-center gap-1">
                        <template x-for="page in displayedPages" :key="page">
                            <li>
                                <button
                                    x-show="page !== '...'"
                                    @click="goToPage(page)"
                                    x-text="page"
                                    :class="currentPage === page
                            ? 'bg-blue-500 text-white'
                            : 'text-gray-700 hover:bg-gray-100'"
                                    class="h-9 w-9 flex items-center justify-center rounded-lg text-sm font-medium">
                                </button>

                                <span
                                    x-show="page === '...'"
                                    class="h-9 w-9 flex items-center justify-center text-gray-400">
                                    …
                                </span>
                            </li>
                        </template>
                    </ul>

                    {{-- NEXT --}}
                    <button
                        @click="next"
                        :disabled="currentPage === totalPages"
                        :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''"
                        class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white
                   px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Next
                    </button>

                </div>
            </div>

        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/table.js') }}"></script>
@endpush