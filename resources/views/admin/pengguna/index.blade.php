@extends('layouts.admin')

@section('content')

<div
    class="space-y-1"
    x-data="Table(@js($users), {
        perPage: 10,
        searchKeys: ['nama','email','no_hp','peran','status','created_at']
    })">

    {{-- FLASH MESSAGE --}}
    @include('elements.flash-messages')

    {{-- CARD --}}
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- HEADER --}}
        <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Data Pengguna
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

                <a href="{{ route('admin.pengguna.create') }}"
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
                            ['label' => 'No', 'key' => 'id_pengguna'],
                            ['label' => 'Nama', 'key' => 'nama'],
                            ['label' => 'Email', 'key' => 'email'],
                            ['label' => 'No HP', 'key' => 'no_hp'],
                            ['label' => 'Peran', 'key' => 'peran'],
                            ['label' => 'Status', 'key' => 'status'],
                            ['label' => 'Waktu', 'key' => 'created_at'],
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

                            <th class="px-4 py-3 text-sm text-gray-500 text-end">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="(item, index) in paginated" :key="item.id_pengguna">
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                                {{-- NO --}}
                                <td class="px-4 py-4 text-sm text-gray-500"
                                    x-text="(currentPage - 1) * perPage + index + 1"></td>

                                {{-- NAMA --}}
                                <td class="px-4 py-4 font-medium text-gray-800 dark:text-white"
                                    x-text="item.nama"></td>

                                {{-- EMAIL --}}
                                <td class="px-4 py-4 text-sm text-gray-500"
                                    x-text="item.email"></td>

                                {{-- NO HP --}}
                                <td class="px-4 py-4 text-sm text-gray-600"
                                    x-text="item.no_hp ?? '-'"></td>

                                {{-- PERAN --}}
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize"
                                        :class="{
            'bg-red-50 text-red-600': item.peran === 'admin',
            'bg-blue-50 text-blue-600': item.peran === 'owner',
            'bg-gray-100 text-gray-700': item.peran === 'pelanggan'
        }"
                                        x-text="item.peran">
                                    </span>
                                </td>


                                {{-- STATUS --}}
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize"
                                        :class="{
            'bg-green-50 text-green-600': item.status === 'active',
            'bg-gray-100 text-gray-700': item.status === 'inactive',
            'bg-yellow-50 text-yellow-600': item.status === 'suspended'
        }"
                                        x-text="item.status">
                                    </span>
                                </td>


                                {{-- WAKTU --}}
                                <td class="px-4 py-4 text-sm text-gray-500">
                                    <div>
                                        Dibuat:
                                        <span x-text="new Date(item.created_at).toLocaleDateString('id-ID')"></span>
                                    </div>
                                    <div x-show="item.created_at !== item.updated_at">
                                        Diperbarui:
                                        <span x-text="new Date(item.updated_at).toLocaleDateString('id-ID')"></span>
                                    </div>
                                </td>


                                {{-- AKSI --}}
                                <td class="px-4 py-4 text-end">
                                    <div class="flex justify-end gap-3">

                                        {{-- EDIT --}}
                                        <a
                                            :href="`/admin/pengguna/${item.id_pengguna}/edit`"
                                            class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- DELETE --}}
                                        <form
                                            :action="`/admin/pengguna/${item.id_pengguna}`"
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
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                Data pengguna tidak ditemukan
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
                    class="rounded-lg border px-3 py-2 text-sm">
                    Prev
                </button>

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
                                class="h-9 w-9 rounded-lg text-sm font-medium">
                            </button>
                        </li>
                    </template>
                </ul>

                <button @click="next"
                    :disabled="currentPage === totalPages"
                    :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''"
                    class="rounded-lg border px-3 py-2 text-sm">
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