@extends('layouts.admin')

@section('content')

<div
    class="space-y-4"
    x-data="Table(@js($pembayaran))">

    {{-- FLASH MESSAGE --}}
    @include('elements.flash-messages')

    {{-- CARD --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- HEADER --}}
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Data Pembayaran
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Monitoring status pembayaran pemesanan lapangan
            </p>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto px-6 py-4">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th
                            class="px-3 py-3 text-left text-gray-500 dark:text-gray-400 cursor-pointer"
                            @click="sortBy('waktu_bayar')" data-sort="waktu_bayar">
                            Tanggal Bayar
                            <span x-show="sortKey === 'waktu_bayar'">
                                <template x-if="sortAsc">▲</template>
                                <template x-if="!sortAsc">▼</template>
                            </span>
                        </th>
                        <th
                            class="px-3 py-3 text-left text-gray-500 dark:text-gray-400 cursor-pointer"
                            @click="sortBy('kode_pemesanan')" data-sort="kode_pemesanan">
                            Kode Pemesanan
                            <span x-show="sortKey === 'kode_pemesanan'">
                                <template x-if="sortAsc">▲</template>
                                <template x-if="!sortAsc">▼</template>
                            </span>
                        </th>
                        <th
                            class="px-3 py-3 text-left text-gray-500 dark:text-gray-400 cursor-pointer"
                            @click="sortBy('pemesan')" data-sort="pemesan">
                            Pemesan
                        </th>
                        <th
                            class="px-3 py-3 text-left text-gray-500 dark:text-gray-400 cursor-pointer"
                            @click="sortBy('total_bayar')" data-sort="total_bayar">
                            Total Bayar
                        </th>
                        <th
                            class="px-3 py-3 text-left text-gray-500 dark:text-gray-400">
                            Status Transaksi
                        </th>
                        <th
                            class="px-3 py-3 text-left text-gray-500 dark:text-gray-400">
                            Tipe Pembayaran
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($pembayaran as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                        {{-- TANGGAL BAYAR --}}
                        <td class="px-3 py-4 text-gray-700 dark:text-gray-200">
                            {{ $item->waktu_bayar ? $item->waktu_bayar->format('d M Y H:i') : '-' }}
                        </td>

                        {{-- KODE PEMESANAN --}}
                        <td class="px-3 py-4 font-medium text-gray-800 dark:text-white">
                            {{ $item->pemesanan->kode_pemesanan ?? '-' }}
                        </td>

                        {{-- PEMESAN --}}
                        <td class="px-3 py-4 text-gray-700 dark:text-gray-200">
                            {{ $item->pemesanan->pengguna->nama ?? '-' }}
                        </td>

                        {{-- TOTAL BAYAR --}}
                        <td class="px-3 py-4 text-gray-700 dark:text-gray-200">
                            Rp {{ number_format($item->pemesanan->total_bayar ?? 0, 0, ',', '.') }}
                        </td>

                        {{-- STATUS TRANSAKSI --}}
                        <td class="px-3 py-4">
                            @php
                            if ($item->isBerhasil()) {
                            $statusClass = 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300';
                            $statusText = 'Berhasil';
                            } elseif ($item->isPending()) {
                            $statusClass = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300';
                            $statusText = 'Pending';
                            } elseif ($item->isGagal()) {
                            $statusClass = 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300';
                            $statusText = 'Gagal';
                            } else {
                            $statusClass = 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300';
                            $statusText = $item->status_transaksi;
                            }
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>

                        {{-- TIPE PEMBAYARAN --}}
                        <td class="px-3 py-4 text-gray-700 dark:text-gray-200">
                            {{ ucfirst($item->tipe_pembayaran) ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-gray-500 dark:text-gray-400">
                            Data pembayaran belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/table.js') }}"></script>
@endpush