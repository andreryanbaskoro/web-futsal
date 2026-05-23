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

        {{-- DIMENSI --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Dimensi
            </label>
            <input
                type="text"
                name="dimensi"
                value="{{ old('dimensi', $lapangan->dimensi ?? '') }}"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
               dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
               bg-transparent px-4 py-2.5 text-sm text-gray-800
               placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
               dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
               dark:placeholder:text-white/30"
                placeholder="Contoh: 25 x 15 m" />
        </div>

        {{-- KAPASITAS --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Kapasitas
            </label>
            <input
                type="text"
                name="kapasitas"
                value="{{ old('kapasitas', $lapangan->kapasitas ?? '') }}"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
               dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
               bg-transparent px-4 py-2.5 text-sm text-gray-800
               placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
               dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
               dark:placeholder:text-white/30"
                placeholder="Contoh: 10 orang" />
        </div>

        @include('admin.lapangan._form-image')


        {{-- STATUS --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Status Lapangan
            </label>

            <select
                name="status"
                required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
               dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
               bg-transparent px-4 py-2.5 text-sm text-gray-800
               focus:ring-3 focus:outline-hidden
               dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">

                <option value="aktif"
                    {{ old('status', $lapangan->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>
                    Aktif (Lapangan dapat dibooking)
                </option>

                <option value="maintenance"
                    {{ old('status', $lapangan->status ?? '') == 'maintenance' ? 'selected' : '' }}>
                    Maintenance (Sedang pemeliharaan)
                </option>

                <option value="perbaikan"
                    {{ old('status', $lapangan->status ?? '') == 'perbaikan' ? 'selected' : '' }}>
                    Perbaikan (Sedang diperbaiki)
                </option>

                <option value="event"
                    {{ old('status', $lapangan->status ?? '') == 'event' ? 'selected' : '' }}>
                    Event (Dipakai acara/turnamen)
                </option>

                <option value="nonaktif"
                    {{ old('status', $lapangan->status ?? '') == 'nonaktif' ? 'selected' : '' }}>
                    Nonaktif (Tidak digunakan)
                </option>

            </select>
        </div>

    </div>
</x-common.component-card>