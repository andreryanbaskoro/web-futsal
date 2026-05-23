<x-common.component-card>
    @slot('title')
    <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        {{ $title }}
    </h3>
    @endslot

    <div
        class="space-y-6"
        x-data="{
        selectedLapangan: '{{ old('id_lapangan', $jamOperasional->id_lapangan ?? '') }}',

        currentHari: '{{ $jamOperasional->hari ?? '' }}',

        hariList: ['senin','selasa','rabu','kamis','jumat','sabtu','minggu'],

        hariTerpakai: @js($hariTerpakai),

        isDisabled(hari) {

            if (!this.selectedLapangan) return false;

            let used = this.hariTerpakai[this.selectedLapangan] || [];

            if (hari === this.currentHari) {
                return false;
            }

            return used.includes(hari);
        },

        pilihSemua() {
            this.hariList.forEach(h => {
                if (!this.isDisabled(h)) {
                    document.getElementById('hari_' + h).checked = true;
                }
            });
        },

        pilihHariKerja() {

            this.resetHari();

            ['senin','selasa','rabu','kamis','jumat'].forEach(h => {

                if (!this.isDisabled(h)) {
                    document.getElementById('hari_' + h).checked = true;
                }
            });
        },

        pilihWeekend() {

            this.resetHari();

            ['sabtu','minggu'].forEach(h => {

                if (!this.isDisabled(h)) {
                    document.getElementById('hari_' + h).checked = true;
                }
            });
        },

        resetHari() {

            this.hariList.forEach(h => {

                if (!this.isDisabled(h)) {
                    document.getElementById('hari_' + h).checked = false;
                }
            });
        }
    }">

        {{-- LAPANGAN --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Lapangan
            </label>
            <select
                x-model="selectedLapangan"
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
        <div class="space-y-4">

            {{-- HEADER --}}
            <div>
                <label class="block text-sm font-semibold text-gray-800 dark:text-white/90">
                    Hari Operasional
                </label>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Pilih satu atau beberapa hari operasional lapangan.
                </p>
            </div>

            {{-- ACTION BUTTON --}}
            <div class="flex flex-wrap gap-2">
                <button
                    type="button"
                    @click="pilihSemua()"
                    class="inline-flex items-center rounded-full border border-gray-300
                           bg-white px-3 py-1.5 text-xs font-medium text-gray-700
                           transition hover:border-brand-300 hover:text-brand-600
                           dark:border-gray-700 dark:bg-gray-900
                           dark:text-gray-300 dark:hover:border-brand-700 dark:hover:text-brand-400">
                    Semua Hari
                </button>

                <button
                    type="button"
                    @click="pilihHariKerja()"
                    class="inline-flex items-center rounded-full border border-gray-300
                           bg-white px-3 py-1.5 text-xs font-medium text-gray-700
                           transition hover:border-brand-300 hover:text-brand-600
                           dark:border-gray-700 dark:bg-gray-900
                           dark:text-gray-300 dark:hover:border-brand-700 dark:hover:text-brand-400">
                    Hari Kerja
                </button>

                <button
                    type="button"
                    @click="pilihWeekend()"
                    class="inline-flex items-center rounded-full border border-gray-300
                           bg-white px-3 py-1.5 text-xs font-medium text-gray-700
                           transition hover:border-brand-300 hover:text-brand-600
                           dark:border-gray-700 dark:bg-gray-900
                           dark:text-gray-300 dark:hover:border-brand-700 dark:hover:text-brand-400">
                    Weekend
                </button>

                <button
                    type="button"
                    @click="resetHari()"
                    class="inline-flex items-center rounded-full border border-red-200
                           bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600
                           transition hover:bg-red-100
                           dark:border-red-900/40 dark:bg-red-900/10
                           dark:text-red-400 dark:hover:bg-red-900/20">
                    Reset
                </button>
            </div>

            {{-- CHECKBOX --}}
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @php
                $selectedHari = old('hari', isset($jamOperasional) ? [$jamOperasional->hari] : []);
                @endphp

                @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $hari)
                <label
                    for="hari_{{ $hari }}"
                    class="group flex cursor-pointer items-center gap-3 rounded-xl
                               border border-gray-200 bg-white px-3 py-2.5
                               transition-all duration-200 hover:border-brand-300 hover:shadow-sm
                               dark:border-gray-700 dark:bg-gray-900 dark:hover:border-brand-700">

                    <input
                        type="checkbox"
                        id="hari_{{ $hari }}"
                        name="hari[]"
                        value="{{ $hari }}"
                        {{ in_array($hari, $selectedHari) ? 'checked' : '' }}

                        :disabled="isDisabled('{{ $hari }}')"

                        class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500
           disabled:cursor-not-allowed disabled:opacity-40
           dark:border-gray-600 dark:bg-gray-800">

                    <div class="flex flex-col leading-tight">
                        <span class="text-sm font-semibold text-gray-800 dark:text-white/90">
                            {{ ucfirst($hari) }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            Hari operasional
                        </span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- JAM BUKA --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Jam Buka
            </label>

            <input
                type="time"
                name="jam_buka"
                value="{{ old('jam_buka', isset($jamOperasional) ? \Carbon\Carbon::parse(str_replace('.', ':', $jamOperasional->jam_buka))->format('H:i') : '') }}"
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

            <input
                type="time"
                name="jam_tutup"
                value="{{ old('jam_tutup', isset($jamOperasional) ? \Carbon\Carbon::parse(str_replace('.', ':', $jamOperasional->jam_tutup))->format('H:i') : '') }}"
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