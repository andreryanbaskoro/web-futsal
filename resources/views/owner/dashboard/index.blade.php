@extends('layouts.owner')

@section('title', 'Dashboard Owner')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Dashboard Owner
        </h1>
    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        {{-- TOTAL PEMESANAN --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Pemesanan</p>
                    <h3 class="mt-1 text-2xl font-bold text-gray-800">
                        {{ number_format($totalPemesanan) }}
                    </h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i class="fas fa-list"></i>
                </div>
            </div>
        </div>

        {{-- TOTAL DIBAYAR --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Dibayar</p>
                    <h3 class="mt-1 text-2xl font-bold text-gray-800">
                        Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>

        {{-- JADWAL HARI INI --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Jadwal Hari Ini</p>
                    <h3 class="mt-1 text-2xl font-bold text-gray-800">
                        {{ number_format($jadwalHariIni) }}
                    </h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>

        {{-- PENDING HARI INI --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pending Jadwal Hari Ini</p>
                    <h3 class="mt-1 text-2xl font-bold text-gray-800">
                        {{ number_format($pemesananPendingHariIni) }}
                    </h3>
                    <p class="mt-1 text-xs text-gray-400">
                        Slot belum dibayar
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-50 text-yellow-600">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>



    </div>


    {{-- QUICK ACCESS --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

        {{-- LAPORAN JADWAL --}}
        <a href="{{ route('owner.laporan.jadwal') }}"
            class="group rounded-2xl border border-gray-200 bg-white p-6
                  transition hover:border-blue-500 hover:shadow-lg">

            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-xl
                            bg-blue-50 text-blue-600 group-hover:bg-blue-500 group-hover:text-white">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 group-hover:text-blue-600">
                        Laporan Jadwal
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Lihat jadwal pemakaian lapangan per tanggal
                    </p>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <span class="text-sm font-medium text-blue-600">
                    Lihat Laporan
                </span>
                <i class="fas fa-arrow-right text-blue-500"></i>
            </div>
        </a>

        {{-- LAPORAN PEMESANAN --}}
        <a href="{{ route('owner.laporan.pemesanan') }}"
            class="group rounded-2xl border border-gray-200 bg-white p-6
                  transition hover:border-green-500 hover:shadow-lg">

            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-xl
                            bg-green-50 text-green-600 group-hover:bg-green-500 group-hover:text-white">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 group-hover:text-green-600">
                        Laporan Pemesanan
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Data pemesanan, pembayaran, dan status transaksi
                    </p>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <span class="text-sm font-medium text-green-600">
                    Lihat Laporan
                </span>
                <i class="fas fa-arrow-right text-green-500"></i>
            </div>
        </a>

    </div>

</div>
@endsection