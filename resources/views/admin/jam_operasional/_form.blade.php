<x-common.component-card>
    @slot('title')
    <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        {{ $title }}
    </h3>
    @endslot

    <div class="space-y-6">

        {{-- LAPANGAN --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Lapangan
            </label>
            <select
                name="id_lapangan"
                required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                       dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
                       bg-transparent px-4 py-2.5 text-sm text-gray-800
                       placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                       dark:placeholder:text-white/30">
                <option value="">Pilih Lapangan</option>
                @foreach($lapangan as $l)
                    <option value="{{ $l->id_lapangan }}"
                        {{ old('id_lapangan', $jamOperasional->id_lapangan ?? '') == $l->id_lapangan ? 'selected' : '' }}>
                        {{ $l->nama_lapangan }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- HARI --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Hari
            </label>
            <select
                name="hari"
                required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                       dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
                       bg-transparent px-4 py-2.5 text-sm text-gray-800
                       placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                       dark:placeholder:text-white/30">
                @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $hari)
                    <option value="{{ $hari }}"
                        {{ old('hari', $jamOperasional->hari ?? '') == $hari ? 'selected' : '' }}>
                        {{ ucfirst($hari) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- JAM BUKA --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Jam Buka
            </label>
            <input type="time"
                name="jam_buka"
                value="{{ old('jam_buka', $jamOperasional->jam_buka ?? '') }}"
                required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                       dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
                       bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
        </div>

        {{-- JAM TUTUP --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Jam Tutup
            </label>
            <input type="time"
                name="jam_tutup"
                value="{{ old('jam_tutup', $jamOperasional->jam_tutup ?? '') }}"
                required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                       dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
                       bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
        </div>

        {{-- INTERVAL --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Interval (Menit)
            </label>
            <input type="number"
                name="interval_menit"
                value="{{ old('interval_menit', $jamOperasional->interval_menit ?? 60) }}"
                min="1"
                required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                       dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
                       bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
        </div>

        {{-- HARGA --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Harga per Slot (Rp)
            </label>
            <input type="number"
                name="harga"
                value="{{ old('harga', $jamOperasional->harga ?? 0) }}"
                min="0"
                required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                       dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
                       bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
        </div>

    </div>
</x-common.component-card>
