@extends('layouts.pelanggan')


@section('content')
<section style="background: var(--gradient-dark); padding: 90px 0 50px;">
    <div class="container text-center">
        <h1 style="color: var(--color-white); margin-bottom: var(--space-md);">
            Profil Akun
        </h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">
            Kelola informasi pribadi dan keamanan akun Anda dengan mudah
        </p>
    </div>
</section>

<section style="padding: 20px 0;">
    <div class="container">


        {{-- FLASH MESSAGE --}}
        @include('elements.flash-messages')

        <div class="profile-wrapper">

            <!-- SIDEBAR KIRI -->
            <aside class="user-sidebar"
                style="
        background: var(--color-white);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        position: sticky;
        top: 100px;
        box-shadow:
            0 10px 25px rgba(0,0,0,0.08),
            0 4px 10px rgba(0,0,0,0.04);
    ">

                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>

                <h3 class="user-name">{{ auth()->user()->nama }}</h3>
                <p class="user-email">{{ auth()->user()->email }}</p>

                <div class="user-menu">
                    <a href="{{ route('pelanggan.booking.history') }}">
                        <i class="fas fa-history"></i> Riwayat Booking
                    </a>

                    <a href="{{ route('pelanggan.profile.index') }}" class="active">
                        <i class="fas fa-user"></i> Profil Saya
                    </a>
                </div>
            </aside>

            <!-- KONTEN KANAN -->
            <main class="profile-content">

                <div class="profile-layout">
                    @include('pelanggan.profile._form-profile')
                    @include('pelanggan.profile._form-password')
                </div>
            </main>

        </div>


    </div>
</section>
@endsection

@push('styles')
<style>
    .profile-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-2xl);
        align-items: start;
    }

    .profile-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        flex-shrink: 0;
    }

    .profile-wrapper {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: var(--space-2xl);
        align-items: start;
    }

    /* SIDEBAR */
    .user-sidebar {
        background: var(--color-white);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-card);
        padding: var(--space-xl);
        position: sticky;
        top: 100px;
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e5e7eb, #f3f4f6);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--space-md);
    }

    .user-avatar i {
        font-size: 36px;
        color: #9ca3af;
    }

    .user-name {
        text-align: center;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .user-email {
        text-align: center;
        font-size: var(--text-sm);
        color: var(--color-gray-600);
        margin-bottom: var(--space-lg);
    }

    .user-menu {
        border-top: 1px solid var(--color-gray-200);
        padding-top: var(--space-lg);
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .user-menu a {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-md);
        border-radius: var(--radius-md);
        color: var(--color-gray-700);
        transition: var(--transition-base);
    }

    .user-menu a:hover {
        background: var(--color-gray-100);
    }

    .user-menu a.active {
        background: rgba(29, 185, 84, 0.1);
        color: var(--color-primary);
    }

    /* KONTEN KANAN */
    .profile-content {
        width: 100%;
    }

    /* FORM GRID */
    .profile-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-2xl);
    }

    /* RESPONSIVE */
    @media (max-width: 1024px) {
        .profile-wrapper {
            grid-template-columns: 240px 1fr;
        }
    }

    @media (max-width: 768px) {
        .profile-wrapper {
            grid-template-columns: 1fr;
        }

        .profile-layout {
            grid-template-columns: 1fr;
        }

        .user-sidebar {
            position: relative;
            top: 0;
        }
    }


    /* VARIANT */
    .profile-icon--success {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }

    .profile-icon--danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }


    @media (max-width: 768px) {
        .profile-layout {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
@if (session('success'))
<script>
    Swal.fire({
        title: 'Berhasil',
        html: `
            <div style="
                display:flex;
                flex-direction:column;
                align-items:center;
                text-align:center;
                gap:8px;
                font-size:14px;
                color:#374151;
            ">
                <i class="fas fa-circle-check"
                   style="font-size:48px;color:#16a34a"></i>

                <p style="margin:0;font-weight:500">
                    {{ session('success') }}
                </p>
            </div>
        `,
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#16a34a'
    });
</script>
@endif
@endpush


@push('scripts')
@if ($errors->any())
<script>
    Swal.fire({
        title: 'Gagal menyimpan',
        html: `
            <div style="
                display:flex;
                flex-direction:column;
                align-items:center;
                text-align:center;
                font-size:14px;
                color:#374151;
                gap:8px;
            ">
                <i class="fas fa-triangle-exclamation"
                   style="font-size:44px;color:#dc2626;margin-bottom:4px"></i>

                @if($errors->count() === 1)
                    <p style="margin:0;font-weight:500">
                        {{ $errors->first() }}
                    </p>
                @else
                    <ul style="margin-top:6px;padding-left:18px;text-align:left">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        `,
        showConfirmButton: true,
        confirmButtonText: 'Perbaiki',
        confirmButtonColor: '#dc2626'
    });
</script>
@endif
@endpush