<x-common.component-card>
    @slot('title')
    <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        Profil Saya
    </h3>
    @endslot

    <form
        x-ref="profileForm"
        action="{{ route('owner.profile.update') }}"
        method="POST"
        class="space-y-6">
        @csrf
        @method('PUT')

        {{-- NAMA --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                Nama Lengkap
            </label>
            <input
                type="text"
                name="nama"
                value="{{ old('nama', auth()->user()->nama) }}"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                       focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('nama')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- EMAIL --}}
        @if(auth()->user()->isOwner())
        <div>
            <label>Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email', auth()->user()->email) }}"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10
                dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('email')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
        @endif


        {{-- NO HP --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                No. HP
            </label>
            <input
                type="text"
                name="no_hp"
                value="{{ old('no_hp', auth()->user()->no_hp) }}"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                       focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('no_hp')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- INFO ROLE --}}
        <div class="rounded-lg bg-gray-50 p-4 text-sm text-gray-600 dark:bg-white/[0.03] dark:text-gray-300">
            <p><strong>Peran:</strong> {{ ucfirst(auth()->user()->peran) }}</p>
            <p><strong>Status:</strong> {{ auth()->user()->status }}</p>
        </div>

        {{-- ACTION --}}
        <div class="flex justify-end gap-3">
            <button
                type="button"
                @click="
            if ($refs.profileForm.checkValidity()) {
                $store.modal.formRef = $refs.profileForm
                $store.modal.show('update')
            } else {
                $refs.profileForm.reportValidity()
            }
        "
                class="inline-flex items-center rounded-lg bg-brand-500 px-5 py-2.5
               text-sm font-medium text-white hover:bg-brand-600">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>


    </form>
</x-common.component-card>


@push('modals')
@include('elements.modal-perbarui')
@endpush