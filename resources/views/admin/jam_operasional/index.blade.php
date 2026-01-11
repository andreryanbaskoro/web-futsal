@extends('layouts.admin')

@section('content')

<div
    class="space-y-1"
    x-data="Table(@js($jamOperasional))">

    {{-- Include Flash Messages --}}
    @include('elements.flash-messages')

    {{-- CARD --}}
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- HEADER --}}
        <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Data Jam Operasional
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

                <a href="{{ route('admin.jam-operasional.create') }}"
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
                            <th class="px-4 py-3 text-sm text-gray-500 text-left">No</th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left">Lapangan</th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left">Hari</th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left">Jam Buka</th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left">Jam Tutup</th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left">Interval (Menit)</th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-left">Harga</th>
                            <th class="px-4 py-3 text-sm text-gray-500 text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="(item, index) in paginated" :key="item.id_operasional">
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                                <td class="px-4 py-4 text-sm text-gray-500"
                                    x-text="(currentPage - 1) * perPage + index + 1">
                                </td>

                                <td class="px-4 py-4 text-sm text-gray-800 dark:text-white" x-text="item.lapangan.nama_lapangan"></td>
                                <td class="px-4 py-4 text-sm text-gray-500 capitalize" x-text="item.hari"></td>
                                <td class="px-4 py-4 text-sm text-gray-500"
                                    x-text="item.jam_buka.slice(0,5)">
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500"
                                    x-text="item.jam_tutup.slice(0,5)">
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500" x-text="item.interval_menit"></td>
                                <td
                                    class="px-4 py-4 text-sm text-gray-500"
                                    x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.harga)">
                                </td>
                                <td class="px-4 py-4 text-end">
                                    <div class="flex justify-end gap-3">
                                        <a
                                            :href="`/admin/jam-operasional/${item.id_operasional}/edit`"
                                            class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form
                                            :action="`/admin/jam-operasional/${item.id_operasional}`"
                                            method="POST"
                                            x-ref="deleteForm"
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
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                Data jam operasional tidak ditemukan
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