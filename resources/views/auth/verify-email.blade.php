@extends('layouts.auth')

@section('title', 'Verifikasi Email')

@section('content')
<div class="auth-page" style="min-height: 100vh; display: flex;">
    <div class="auth-right" style="flex: 1; display: flex; align-items: center; justify-content: center; padding: var(--space-2xl); background: var(--color-white); overflow-y: auto;">
        <div class="auth-form" style="width: 100%; max-width: 450px;">
            <h2 style="margin-bottom: var(--space-sm);">Verifikasi Email</h2>
            <p style="margin-bottom: var(--space-xl);">Masukkan kode verifikasi yang kami kirimkan ke email {{ $email }}.</p>

            @include('elements.flash-messages')

            <!-- Form untuk memasukkan kode verifikasi -->
            <form method="POST" action="{{ route('verification.verify', ['email' => $email]) }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Kode Verifikasi</label>
                    <input type="text" class="form-control" name="verification_code" required>
                </div>

                <button type="submit" class="btn btn-primary w-full btn-lg" style="background: var(--color-primary); color: white;">
                    Verifikasi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
