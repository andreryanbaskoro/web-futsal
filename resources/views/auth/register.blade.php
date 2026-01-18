@extends('layouts.auth')

@section('title', 'FUSTAL ACR - Daftar')
@section('content')

<div class="auth-page" style="min-height: 100vh; display: flex;">
    <!-- Left Side - Branding -->
    <div class="auth-left" style="flex: 1; background: var(--gradient-hero); position: relative; display: flex; align-items: center; justify-content: center; padding: var(--space-2xl);">
        <div class="auth-left-content" style="position: relative; z-index: 1; color: var(--color-white); text-align: center; max-width: 400px;">
            <i class="fas fa-futbol" style="font-size: 64px; color: var(--color-primary); margin-bottom: var(--space-lg);"></i>
            <h1 style="color: var(--color-white); margin-bottom: var(--space-md);">Futsal ACR</h1>
            <p>Bergabunglah dengan ribuan pemain futsal yang sudah menikmati kemudahan booking online.</p>

            <div style="margin-top: var(--space-2xl);">
                <div style="background: rgba(255,255,255,0.1); border-radius: var(--radius-lg); padding: var(--space-lg);">
                    <p style="font-style: italic; margin-bottom: var(--space-md);">
                        "{{ $randomTestimonial['quote'] }}"
                    </p>
                    <div style="display: flex; align-items: center; justify-content: center; gap: var(--space-sm);">
                        <img src="https://i.pravatar.cc/40?img={{ $randomTestimonial['avatar'] }}"
                            alt="{{ $randomTestimonial['name'] }}"
                            style="width: 40px; height: 40px; border-radius: 50%;">
                        <div style="text-align: left;">
                            <div style="font-weight: 600;">{{ $randomTestimonial['name'] }}</div>
                            <div style="font-size: var(--text-sm); opacity: 0.8;">
                                Member sejak {{ $randomTestimonial['year'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Register Form -->
    <div class="auth-right" style="flex: 1; display: flex; align-items: center; justify-content: center; padding: var(--space-2xl); background: var(--color-white); overflow-y: auto;">
        <div class="auth-form" style="width: 100%; max-width: 450px;">
            <a href="{{ route('beranda') }}" style="display: inline-flex; align-items: center; gap: var(--space-sm); color: var(--color-gray-600); margin-bottom: var(--space-xl); font-size: var(--text-sm);">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>

            <h2 style="margin-bottom: var(--space-sm);">Buat Akun Baru 🎉</h2>
            <p class="subtitle" style="color: var(--color-gray-600); margin-bottom: var(--space-xl);">Daftar gratis dan mulai booking lapangan favoritmu</p>

            @include('elements.flash-messages')

            <form id="registerForm" method="POST" action="{{ route('register.store') }}" onsubmit="handleRegister(event)">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" placeholder="Nama Lengkap" name="name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" placeholder="nama@email.com" name="email" required>
                </div>
                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="tel" class="form-control" placeholder="08xxxxxxxxxx" name="no_hp" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div style="position: relative;">
                        <input type="password" class="form-control" id="password" placeholder="Minimal 8 karakter" name="password" required minlength="8" oninput="checkPasswordStrength()">
                        <button type="button" onclick="togglePassword('password', 'toggleIcon1')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--color-gray-500);">
                            <i class="fas fa-eye" id="toggleIcon1"></i>
                        </button>
                    </div>
                    <div class="password-strength" style="height: 4px; background: var(--color-gray-200); border-radius: 2px; margin-top: var(--space-sm); overflow: hidden;">
                        <div class="password-strength-bar" id="strengthBar" style="height: 100%; width: 0; transition: all 0.3s ease;"></div>
                    </div>
                    <small style="color: var(--color-gray-500); font-size: 12px;" id="strengthText"></small>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <div style="position: relative;">
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Ulangi password" name="password_confirmation" required>
                        <button type="button" onclick="togglePassword('confirmPassword', 'toggleIcon2')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--color-gray-500);">
                            <i class="fas fa-eye" id="toggleIcon2"></i>
                        </button>
                    </div>
                </div>
                <label class="terms-check" style="display: flex; align-items: flex-start; gap: var(--space-sm); margin-bottom: var(--space-lg); font-size: var(--text-sm); color: var(--color-gray-600);">
                    <input type="checkbox" required style="width: 18px; height: 18px; accent-color: var(--color-primary); margin-top: 2px;">
                    <span>Saya menyetujui <a href="{{ route('syarat') }}" style="color: var(--color-primary);">Syarat Ketentuan & Kebijakan Privasi</a> Futsal ACR</span>
                </label>
                <button type="submit" class="btn btn-primary w-full btn-lg">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
            </form>

            <div class="form-footer" style="text-align: center; margin-top: var(--space-xl); color: var(--color-gray-600);">
                Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--color-primary); font-weight: 500;">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function checkPasswordStrength() {
        const password = document.getElementById('password').value;
        const bar = document.getElementById('strengthBar');
        const text = document.getElementById('strengthText');

        bar.className = 'password-strength-bar';

        if (password.length < 6) {
            bar.classList.add('weak');
            text.textContent = 'Password lemah';
            text.style.color = 'var(--color-error)';
        } else if (password.length < 10 || !/[A-Z]/.test(password) || !/[0-9]/.test(password)) {
            bar.classList.add('medium');
            text.textContent = 'Password cukup kuat';
            text.style.color = 'var(--color-warning)';
        } else {
            bar.classList.add('strong');
            text.textContent = 'Password kuat';
            text.style.color = 'var(--color-success)';
        }
    }

    function handleRegister(e) {
        e.preventDefault();
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('confirmPassword').value;

        if (password !== confirm) {
            alert('Password tidak cocok!');
            return;
        }

        // Lanjutkan dengan pengiriman formulir jika password cocok
        document.getElementById('registerForm').submit();
    }


    // Check screen width for responsiveness
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