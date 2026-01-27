<x-common.component-card>
    @slot('title')
    <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        Ganti Password
    </h3>
    @endslot

    <form
        x-ref="passwordForm"
        action="{{ route('owner.profile.password') }}"
        method="POST"
        class="space-y-6">
        @csrf
        @method('PUT')

        {{-- PASSWORD LAMA --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                Password Lama
            </label>
            <input
                type="password"
                name="password_lama"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
               focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10
               dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('password_lama')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>


        {{-- PASSWORD BARU --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                Password Baru
            </label>
            <input
                type="password"
                name="password"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                       focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('password')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- KONFIRMASI --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                Konfirmasi Password Baru
            </label>
            <input
                type="password"
                name="password_confirmation"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                       focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
        </div>

        {{-- ACTION --}}
        <div class="flex justify-end gap-3">
            <button
                type="button"
                @click="
            if ($refs.passwordForm.checkValidity()) {
                $store.modal.formRef = $refs.passwordForm
                $store.modal.show('update')
            } else {
                $refs.passwordForm.reportValidity()
            }
        "
                class="inline-flex items-center rounded-lg bg-red-500 px-5 py-2.5
               text-sm font-medium text-white hover:bg-red-600">
                <i class="fas fa-key mr-2"></i> Ubah Password
            </button>
        </div>


    </form>
</x-common.component-card>


@push('modals')
@include('elements.modal-perbarui')
@endpush