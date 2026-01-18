@extends('layouts.auth')
@section('title', 'FUSTAL ACR - Masuk')
@section('content')
<div class="auth-page" style="min-height: 100vh; display: flex;">
    <!-- Left Side - Branding -->
    <div class="auth-left" style="flex: 1; background: var(--gradient-hero); position: relative; display: flex; align-items: center; justify-content: center; padding: var(--space-2xl);">
        <div class="auth-left-content" style="position: relative; z-index: 1; color: var(--color-white); text-align: center; max-width: 400px;">
            <i class="fas fa-futbol" style="font-size: 64px; color: var(--color-primary); margin-bottom: var(--space-lg);"></i>
            <h1 style="color: var(--color-white); margin-bottom: var(--space-md);">FUSTAL ACR</h1>
            <p>Booking lapangan futsal online lebih mudah dan cepat. Pilih jadwal, bayar online, langsung main!</p>
            <div style="margin-top: var(--space-2xl);">
                <div style="display: flex; justify-content: center; gap: var(--space-xl);">
                    <div>
                        <div style="font-size: var(--text-3xl); font-weight: 700; color: var(--color-primary);">5+</div>
                        <div style="font-size: var(--text-sm); opacity: 0.8;">Lapangan</div>
                    </div>
                    <div>
                        <div style="font-size: var(--text-3xl); font-weight: 700; color: var(--color-primary);">1000+</div>
                        <div style="font-size: var(--text-sm); opacity: 0.8;">Booking/Bulan</div>
                    </div>
                    <div>
                        <div style="font-size: var(--text-3xl); font-weight: 700; color: var(--color-primary);">4.9</div>
                        <div style="font-size: var(--text-sm); opacity: 0.8;">Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="auth-right" style="flex: 1; display: flex; align-items: center; justify-content: center; padding: var(--space-2xl); background: var(--color-white);">
        <div class="auth-form" style="width: 100%; max-width: 400px;">
            <a href="{{ route('beranda') }}" style="display: inline-flex; align-items: center; gap: var(--space-sm); color: var(--color-gray-600); margin-bottom: var(--space-xl); font-size: var(--text-sm);">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>

            <h2 style="margin-bottom: var(--space-md);">Selamat Datang! 👋</h2>

            @include('elements.flash-messages')

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.process') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password" required style="padding-right: 48px;">
                        <button type="button" onclick="togglePassword()" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--color-gray-500);">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-full btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>
            <div class="form-footer" style="text-align: center; margin-top: var(--space-xl); color: var(--color-gray-600);">
                <p style="font-size: var(--text-sm);">
                    Belum punya akun?
                    <a href="{{ route('register') }}" style="color: var(--color-primary); font-weight: 600; text-decoration: none;">
                        Daftar sekarang
                    </a>
                </p>
                <!-- <p style="font-size: var(--text-sm); margin-top: var(--space-sm);">
                    <a href="{{ route('password.request') }}" style="color: var(--color-primary); font-weight: 600; text-decoration: none;">
                        Lupa Password?
                    </a>
                </p> -->
            </div>

        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');

        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Check screen width for responsiveness
    function handleResponsive() {
        if (window.innerWidth <= 991) {
            document.querySelector('.auth-left').style.display = 'none';
            document.querySelector('.auth-right').style.padding = 'var(--space-lg)';
        } else {
            document.querySelector('.auth-left').style.display = 'flex';
            document.querySelector('.auth-right').style.padding = 'var(--space-2xl)';
        }
    }

    // Run on page load and resize
    window.addEventListener('load', handleResponsive);
    window.addEventListener('resize', handleResponsive);
</script>

@endsection