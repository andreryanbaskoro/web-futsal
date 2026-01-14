@extends('layouts.admin')

@section('content')

<div
    class="space-y-1"
    x-data="Table(@js($lapangan))">

    {{-- Include Flash Messages --}}
    @include('elements.flash-messages')

    {{-- CARD --}}
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- HEADER --}}
        <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Data Lapangan
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

                <a href="{{ route('admin.lapangan.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                    <i class="fas fa-plus"></i>
                    Tambah
                </a>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="overflow-hidden">
            <div class="max-w-full px-5 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-y border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-sm text-gray-500 text-left" @click="sortBy('no')" data-sort="no">
                                No
                            </th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left" @click="sortBy('nama_lapangan')" data-sort="nama_lapangan">
                                Lapangan
                            </th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left" @click="sortBy('deskripsi')" data-sort="deskripsi">
                                Deskripsi
                            </th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left"
                                @click="sortBy('dimensi')"
                                data-sort="dimensi">
                                Dimensi
                            </th>

                            <th class="px-4 py-3 text-sm text-gray-500 text-left"
                                @click="sortBy('kapasitas')"
                                data-sort="kapasitas">
                                Kapasitas
                            </th>

                            <th class="px-4 py-3 text-sm text-gray-500 text-left"
                                @click="sortBy('rating')"
                                data-sort="rating">
                                Rating
                            </th>

                            <th class="px-4 py-3 text-sm text-gray-500 text-left" @click="sortBy('status')" data-sort="status">
                                Status
                            </th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left" @click="sortBy('created_at')" data-sort="created_at">
                                Waktu
                            </th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-end">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="(item, index) in paginated" :key="item.id_lapangan">
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                                <td class="px-4 py-4 text-sm text-gray-500"
                                    x-text="(currentPage - 1) * perPage + index + 1">
                                </td>

                                <td class="px-4 py-4">
                                    <div class="font-medium text-gray-800 dark:text-white"
                                        x-text="item.nama_lapangan">
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-sm text-gray-500 max-w-xs truncate"
                                    x-text="item.deskripsi">
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    <span x-text="item.dimensi ?? '-'"></span>
                                </td>

                                <td class="px-4 py-4 text-sm text-gray-600">
                                    <span x-text="item.kapasitas ?? '-'"></span>
                                </td>

                                <td class="px-4 py-4 text-sm text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-400"></i>
                                        <span x-text="item.rating ?? '0.0'"></span>
                                        <span class="text-xs text-gray-400">
                                            (<span x-text="item.rating_count ?? 0"></span>)
                                        </span>
                                    </div>
                                </td>

                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex rounded-full px-2 py-1 text-xs font-semibold"
                                        :class="item.status === 'aktif'
                                            ? 'bg-green-50 text-green-600'
                                            : 'bg-red-50 text-red-600'"
                                        x-text="item.status">
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-sm text-gray-500">
                                    <div>
                                        <!-- Menampilkan waktu Dibuat -->
                                        Dibuat: <span x-text="new Date(item.created_at).toLocaleDateString('id-ID')"></span>
                                    </div>
                                    <div x-show="item.created_at !== item.updated_at">
                                        <!-- Menampilkan waktu Diperbarui jika berbeda dengan Dibuat -->
                                        Diperbarui: <span x-text="new Date(item.updated_at).toLocaleDateString('id-ID')"></span>
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-end">
                                    <div class="flex justify-end gap-3">
                                        <a
                                            :href="`/admin/lapangan/${item.id_lapangan}/edit`"
                                            class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form
                                            :action="`/admin/lapangan/${item.id_lapangan}`"
                                            method="POST"
                                            x-ref="lapanganDeleteForm"
                                            @submit.prevent="showDeleteModal($event)">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        </template>

                        <tr x-show="paginated.length === 0">
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                Data lapangan tidak ditemukan
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
            <div class="flex items-center justify-between">
                <div>
                    <button @click="prev" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50">
                        Prev
                    </button>
                </div>

                <div>
                    <ul class="flex items-center gap-1">
                        <template x-for="page in displayedPages" :key="page">
                            <li>
                                <button
                                    x-show="page !== '...'"
                                    @click="goToPage(page)"
                                    x-text="page"
                                    :class="currentPage === page ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100'"
                                    class="h-9 w-9 flex items-center justify-center rounded-lg text-sm font-medium">
                                </button>

                                <span x-show="page === '...'" class="h-9 w-9 flex items-center justify-center text-gray-400">…</span>
                            </li>
                        </template>
                    </ul>
                </div>

                <div>
                    <button @click="next" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50">
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

@push('modals')
@include('elements.modal-hapus')
@endpush