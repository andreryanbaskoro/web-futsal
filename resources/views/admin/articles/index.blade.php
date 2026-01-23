@extends('layouts.admin')

@section('content')
<div
    class="space-y-1"
    x-data="Table(@js($articles), {
        perPage: 10,
        searchKeys: ['judul','kategori','author','tanggal_post','tags']
    })">

    {{-- FLASH MESSAGE --}}
    @include('elements.flash-messages')

    {{-- CARD --}}
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- HEADER --}}
        <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Daftar Artikel
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

                <a href="{{ route('admin.articles.create') }}"
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
                            @php
                            $columns = [
                            ['label' => 'No', 'key' => 'id'],
                            ['label' => 'Thumbnail', 'key' => 'featured_image'],
                            ['label' => 'Judul', 'key' => 'judul'],
                            ['label' => 'Kategori', 'key' => 'kategori'],
                            ['label' => 'Author', 'key' => 'author'],
                            ['label' => 'Tanggal', 'key' => 'tanggal_post'],
                            ['label' => 'Waktu Baca', 'key' => 'waktu_baca'],
                            ['label' => 'Tags', 'key' => 'tags'],
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

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="(item, index) in paginated" :key="item.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                {{-- NO --}}
                                <td class="px-4 py-4 text-sm text-gray-500" x-text="(currentPage - 1) * perPage + index + 1"></td>

                                {{-- THUMBNAIL --}}
                                <td class="px-4 py-4">
                                    <img
                                        x-bind:src="(() => {
            if (!item.featured_image) return '/images/default-thumbnail.png';
            return item.featured_image.startsWith('http')
                ? item.featured_image
                : '/storage/' + item.featured_image;
        })()"
                                        alt="Thumbnail"
                                        class="w-16 h-10 object-cover rounded">
                                </td>


                                {{-- JUDUL --}}
                                <td class="px-4 py-4">
                                    <div class="font-medium text-gray-800 dark:text-white" x-text="item.judul"></div>
                                </td>

                                {{-- KATEGORI --}}
                                <td class="px-4 py-4 text-sm text-gray-600" x-text="item.kategori ?? '-'"></td>

                                {{-- AUTHOR --}}
                                <td class="px-4 py-4 text-sm text-gray-600" x-text="item.author ?? '-'"></td>

                                {{-- TANGGAL POST --}}
                                <td class="px-4 py-4 text-sm text-gray-500" x-text="new Date(item.tanggal_post).toLocaleDateString('id-ID')"></td>

                                {{-- WAKTU BACA --}}
                                <td class="px-4 py-4 text-sm text-gray-600" x-text="item.waktu_baca ?? '-'"></td>

                                {{-- TAGS --}}
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    <template x-for="tag in item.tags ?? []" :key="tag">
                                        <span class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-2 py-0.5 rounded-full mr-1 mb-1" x-text="tag"></span>
                                    </template>
                                    <span x-show="!(item.tags && item.tags.length)" class="text-gray-400 text-xs">-</span>
                                </td>

                                {{-- AKSI --}}
                                <td class="px-4 py-4 text-end">
                                    <div class="flex justify-end gap-3">
                                        <a :href="`/admin/articles/${item.id}/edit`" class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form :action="`/admin/articles/${item.id}`" method="POST" x-ref="articleDeleteForm" @submit.prevent="showDeleteModal($event)">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="paginated.length === 0">
                            <td colspan="9" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                Artikel tidak ditemukan
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