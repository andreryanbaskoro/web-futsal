<x-common.component-card>
    @slot('title')
    <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        {{ $title }}
    </h3>
    @endslot

    <div class="space-y-6">

        {{-- NAMA LAPANGAN --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Nama Lapangan
            </label>
            <input
                type="text"
                name="nama_lapangan"
                value="{{ old('nama_lapangan', $lapangan->nama_lapangan ?? '') }}"
                required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                       dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
                       bg-transparent px-4 py-2.5 text-sm text-gray-800
                       placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                       dark:placeholder:text-white/30"
                placeholder="Contoh: Lapangan Futsal A" />
        </div>

        {{-- HARGA PER JAM --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Harga per Jam
            </label>
            <input
                type="number"
                name="harga_per_jam"
                value="{{ old('harga_per_jam', $lapangan->harga_per_jam ?? 0) }}"
                min="0"
                required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                       dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
                       bg-transparent px-4 py-2.5 text-sm text-gray-800
                       placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                       dark:placeholder:text-white/30"
                placeholder="120000" />
        </div>

        {{-- DESKRIPSI --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Deskripsi
            </label>
            <textarea
                name="deskripsi"
                rows="4"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                       dark:focus:border-brand-800 w-full rounded-lg border border-gray-300
                       bg-transparent px-4 py-2.5 text-sm text-gray-800
                       placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                       dark:placeholder:text-white/30"
                placeholder="Deskripsi lapangan...">{{ old('deskripsi', $lapangan->deskripsi ?? '') }}</textarea>
        </div>

        {{-- STATUS (TOGGLE) --}}
        @php
        $statusAktif = old(
        'status',
        $lapangan->status ?? 'aktif'
        ) === 'aktif';
        @endphp

        <div x-data="{ switcherToggle: {{ $statusAktif ? 'true' : 'false' }} }">
            <label
                class="flex cursor-pointer items-center gap-3 text-sm font-medium
                       text-gray-700 select-none dark:text-gray-400">
                <div class="relative">
                    <input
                        type="checkbox"
                        class="sr-only"
                        :checked="switcherToggle"
                        @change="switcherToggle = !switcherToggle" />

                    {{-- value yang dikirim ke backend --}}
                    <input type="hidden" name="status" :value="switcherToggle ? 'aktif' : 'nonaktif'">

                    <div
                        class="block h-6 w-11 rounded-full"
                        :class="switcherToggle
                            ? 'bg-brand-500 dark:bg-brand-500'
                            : 'bg-gray-200 dark:bg-white/10'">
                    </div>

                    <div
                        class="shadow-theme-sm absolute top-0.5 left-0.5 h-5 w-5
                               rounded-full bg-white duration-300 ease-linear"
                        :class="switcherToggle ? 'translate-x-full' : 'translate-x-0'">
                    </div>
                </div>

                <span x-text="switcherToggle ? 'Aktif' : 'Nonaktif'"></span>
            </label>
        </div>

    </div>

</x-common.component-card>