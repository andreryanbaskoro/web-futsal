<div
    x-show="$store.modal.open"
    style="display: none;"
    class="fixed inset-0 z-[999999]">

    <!-- BACKDROP -->
    <div
        x-show="$store.modal.open"
        x-transition.opacity
        class="fixed inset-0 backdrop-blur-md bg-white/30 dark:bg-black/30"
        style="z-index:999999"
        @click="$store.modal.hide()">
    </div>

    <!-- MODAL BOX -->
    <div
        x-show="$store.modal.open"
        x-transition
        class="fixed inset-0 flex items-center justify-center px-4"
        style="z-index:1000000">

        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-lg dark:bg-gray-800 relative">

            {{-- LOADER SPINNER --}}
            <div 
                x-show="$store.modal.isLoading"
                class="absolute inset-0 flex items-center justify-center bg-white/70 dark:bg-gray-900/70 z-50">
                <x-common.preloader />
            </div>

            {{-- MODAL SIMPAN --}}
            <template x-if="$store.modal.type === 'save'">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                        Simpan Data
                    </h2>

                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Apakah kamu yakin ingin menyimpan data ini?
                    </p>

                    <div class="mt-6 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="$store.modal.hide()"
                            class="rounded-lg border px-4 py-2 text-sm">
                            <i class="fas fa-times mr-2"></i> Batal
                        </button>

                        <button
                            type="button"
                            @click="$store.modal.submitWithLoader($refs.lapanganForm)"
                            class="rounded-lg bg-brand-500 px-4 py-2 text-sm text-white">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </div>
            </template>

            {{-- MODAL BATAL --}}
            <template x-if="$store.modal.type === 'cancel'">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                        Batalkan Perubahan
                    </h2>

                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Data yang belum disimpan akan hilang.
                    </p>

                    <div class="mt-6 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="$store.modal.hide()"
                            class="rounded-lg border px-4 py-2 text-sm">
                            Tidak
                        </button>

                        <a
                            href="{{ route('admin.lapangan.index') }}"
                            @click="$store.modal.isLoading = true"
                            class="rounded-lg bg-red-500 px-4 py-2 text-sm text-white">
                            Ya, Batalkan
                        </a>
                    </div>
                </div>
            </template>

        </div>
    </div>
</div>
