@extends('layouts.admin')

@section('content')
<div
    class="space-y-1"
    x-data="Table(@js($kontak), {
    perPage: 10,
    searchKeys: ['nama','email','subjek','status'],
    getTranslatedSubjek(subjek) {
        const translations = {
            booking: 'Pertanyaan Booking',
            payment: 'Masalah Pembayaran',
            facility: 'Fasilitas',
            partnership: 'Kerjasama',
            other: 'Lainnya'
        };
        return translations[subjek] || subjek;
    }
})">

    {{-- FLASH MESSAGE --}}
    @include('elements.flash-messages')

    {{-- CARD --}}
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- HEADER --}}
        <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Pesan Kontak
            </h3>

            {{-- SEARCH --}}
            <div class="relative">
                <span class="absolute -translate-y-1/2 left-4 top-1/2">
                    <i class="fas fa-search text-gray-400"></i>
                </span>
                <input
                    type="text"
                    x-model.debounce.300ms="search"
                    placeholder="Cari pesan..."
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
                            ['label' => 'No', 'key' => 'id'],
                            ['label' => 'Nama', 'key' => 'nama'],
                            ['label' => 'Email', 'key' => 'email'],
                            ['label' => 'Subjek', 'key' => 'subjek'],
                            ['label' => 'Status', 'key' => 'status'],
                            ['label' => 'Tanggal', 'key' => 'created_at'],
                            ];
                            @endphp

                            @foreach($columns as $col)
                            <th
                                class="px-4 py-3 text-sm text-gray-500 text-left cursor-pointer"
                                @click="sortBy('{{ $col['key'] }}')">
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

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="(item, index) in paginated" :key="item.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                                {{-- NO --}}
                                <td class="px-4 py-4 text-sm text-gray-500"
                                    x-text="(currentPage - 1) * perPage + index + 1"></td>

                                {{-- NAMA --}}
                                <td class="px-4 py-4 font-medium text-gray-800 dark:text-white"
                                    x-text="item.nama"></td>

                                {{-- EMAIL --}}
                                <td class="px-4 py-4 text-sm text-gray-600"
                                    x-text="item.email"></td>

                                {{-- SUBJEK --}}
                                <td class="px-4 py-4 text-sm text-gray-600" x-text="item.translated_subjek"></td>


                                {{-- STATUS --}}
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                        :class="{
            'bg-blue-100 text-blue-700': item.status === 'baru',
            'bg-yellow-100 text-yellow-700': item.status === 'dibaca',
            'bg-green-100 text-green-700': item.status === 'dibalas'
        }"
                                        x-text="item.status_label">
                                    </span>
                                </td>


                                {{-- TANGGAL --}}
                                <td class="px-4 py-4 text-sm text-gray-500"
                                    x-text="new Date(item.created_at).toLocaleDateString('id-ID')"></td>

                                {{-- AKSI --}}
                                <td class="px-4 py-4 text-end">
                                    <div class="flex justify-end gap-3">

                                        {{-- BUKA PESAN --}}
                                        <a
                                            :href="`/admin/kontak/${item.id}`"
                                            class="text-indigo-500 hover:text-indigo-700"
                                            title="Buka Pesan">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- HAPUS --}}
                                        <form
                                            :action="`/admin/kontak/${item.id}`"
                                            method="POST"
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
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                Pesan tidak ditemukan
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
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

@push('modals')
@include('elements.modal-hapus')
@endpush