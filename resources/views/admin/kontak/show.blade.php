@extends('layouts.admin')

@section('content')

<div class="space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">
                Detail Pesan Kontak
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Dikirim pada
                <span class="font-medium text-gray-700 dark:text-gray-200">
                    {{ $kontak->created_at->format('d M Y H:i') }}
                </span>
            </p>
        </div>

        <a href="{{ route('admin.kontak.index') }}"
            class="px-4 py-2 text-sm rounded-lg
                  bg-gray-100 text-gray-700 hover:bg-gray-200
                  dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
            ← Kembali
        </a>
    </div>

    {{-- ================= INFORMASI PENGIRIM ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- PENGIRIM --}}
        <div class="rounded-2xl border border-gray-200 bg-white
                    dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <h3 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
                Informasi Pengirim
            </h3>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Nama</span>
                    <span class="font-medium text-gray-700 dark:text-gray-200">
                        {{ $kontak->nama }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Email</span>
                    <span class="font-medium text-gray-700 dark:text-gray-200">
                        {{ $kontak->email }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">No. Telepon</span>
                    <span class="font-medium text-gray-700 dark:text-gray-200">
                        {{ $kontak->no_telepon ?? '-' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- STATUS PESAN --}}
        <div class="rounded-2xl border border-gray-200 bg-white
                    dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <h3 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
                Informasi Pesan
            </h3>

            <div class="space-y-3 text-sm">

                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Subjek</span>
                    <span class="font-medium text-gray-700 dark:text-gray-200">
                        {{ $kontak->translated_subjek }}
                    </span>
                </div>




                <div class="flex justify-between items-center">
                    <span class="text-gray-500 dark:text-gray-400">Status</span>

                    @php
                    $statusClass = match($kontak->status) {
                    'baru' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                    'dibaca' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
                    'dibalas' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                    default => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300'
                    };
                    @endphp

                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                        {{ ucfirst($kontak->status) }}
                    </span>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= ISI PESAN ================= --}}
    <div class="rounded-2xl border border-gray-200 bg-white
                dark:border-gray-800 dark:bg-white/[0.03] p-6">
        <h3 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
            Isi Pesan
        </h3>

        <div class="text-sm text-gray-700 dark:text-gray-200
                    whitespace-pre-line bg-gray-50 dark:bg-white/[0.05]
                    p-4 rounded-lg">
            {{ $kontak->pesan }}
        </div>
    </div>

    {{-- ================= AKSI ================= --}}
    <div class="flex justify-end gap-3">

        @if($kontak->status !== 'dibalas')
        <form action="{{ route('admin.kontak.updateStatus', [$kontak->id, 'dibalas']) }}"
            method="POST">
            @csrf
            @method('PATCH')
            <button
                class="px-4 py-2 rounded-lg text-sm font-medium
                       bg-green-600 text-white hover:bg-green-700">
                Tandai Dibalas
            </button>
        </form>
        @endif

        <form action="{{ route('admin.kontak.destroy', $kontak->id) }}"
            method="POST"
            onsubmit="return confirm('Yakin ingin menghapus pesan ini?')">
            @csrf
            @method('DELETE')
            <button
                class="px-4 py-2 rounded-lg text-sm font-medium
                       bg-red-600 text-white hover:bg-red-700">
                Hapus Pesan
            </button>
        </form>
    </div>

</div>

@endsection