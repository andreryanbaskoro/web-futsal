@extends('layouts.admin')

@section('content')

<div class="space-y-1">

    {{-- Include Flash Messages --}}
    @include('elements.flash-messages')

    <form
        x-ref="lapanganForm"
        action="{{ route('admin.lapangan.store') }}"
        method="POST">
        @csrf

        @include('admin.lapangan._form')

        <div class="mt-6 flex justify-end gap-3">

            {{-- BATAL --}}
            <button
                type="button"
                @click="
        $store.modal.redirectRoute = '{{ url()->previous() }}'; 
        $store.modal.show('cancel')"
                class="inline-flex items-center rounded-lg border border-gray-300 px-5 py-2.5
                   text-sm font-medium text-gray-700 hover:bg-gray-100
                   dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5">
                <i class="fas fa-times mr-2"></i> Batal
            </button>

            {{-- SIMPAN --}}
            <button
                type="button"
                @click="
                if ($refs.lapanganForm.checkValidity()) {
                    $store.modal.formRef = $refs.lapanganForm;  // ← Assign form ke modal
                    $store.modal.show('save')
                } else {
                    $refs.lapanganForm.reportValidity()
                }
            "
                class="inline-flex items-center rounded-lg bg-brand-500
                   px-5 py-2.5 text-sm font-medium text-white
                   hover:bg-brand-600">
                <i class="fas fa-save mr-2"></i> Simpan
            </button>

        </div>
    </form>


</div>

@endsection

@push('modals')
@include('elements.modal-simpan')
@endpush