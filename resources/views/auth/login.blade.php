@extends('partials.auth')

@push('styles')
<style>
    .auth-page {
        min-height: 100vh;
        display: flex;
    }

    .auth-left {
        flex: 1;
        background: var(--gradient-hero);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: var(--space-2xl);
    }

    .auth-left::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url('https://images.unsplash.com/photo-1552667466-07770ae110d0?w=1200&q=80');
        background-size: cover;
        background-position: center;
        opacity: 0.2;
    }

    .auth-left-content {
        position: relative;
        z-index: 1;
        color: var(--color-white);
        text-align: center;
        max-width: 400px;
    }

    .auth-left-content i.fa-futbol {
        font-size: 64px;
        color: var(--color-primary);
        margin-bottom: var(--space-lg);
    }

    .auth-left-content h1 {
        color: var(--color-white);
        margin-bottom: var(--space-md);
    }

    .auth-right {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: var(--space-2xl);
        background: var(--color-white);
    }

    .auth-form {
        width: 100%;
        max-width: 400px;
    }

    .auth-form h2 {
        margin-bottom: var(--space-sm);
    }

    .auth-form .subtitle {
        color: var(--color-gray-600);
        margin-bottom: var(--space-xl);
    }

    .social-login {
        display: flex;
        gap: var(--space-md);
        margin-bottom: var(--space-xl);
    }

    .social-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        padding: var(--space-md);
        border: 2px solid var(--color-gray-300);
        border-radius: var(--radius-lg);
        font-weight: 500;
        transition: var(--transition-base);
    }

    .social-btn:hover {
        border-color: var(--color-primary);
        background: rgba(29, 185, 84, 0.05);
    }

    .divider {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-xl);
        color: var(--color-gray-500);
        font-size: var(--text-sm);
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--color-gray-300);
    }

    .form-footer {
        text-align: center;
        margin-top: var(--space-xl);
        color: var(--color-gray-600);
    }

    .form-footer a {
        color: var(--color-primary);
        font-weight: 500;
    }

    .remember-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-lg);
        font-size: var(--text-sm);
    }

    .remember-row label {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        cursor: pointer;
    }

    .remember-row input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--color-primary);
    }

    .forgot-link {
        color: var(--color-primary);
    }

    @media (max-width: 991px) {
        .auth-left {
            display: none;
        }

        .auth-right {
            padding: var(--space-lg);
        }
    }
</style>
@endpush

@section('content')

<div class="auth-page">
    <!-- Left Side - Branding -->
    <div class="auth-left">
        <div class="auth-left-content">
            <i class="fas fa-futbol"></i>
            <h1>FUSTAL ACR</h1>
            <p>Booking lapangan futsal online lebih mudah dan cepat. Pilih jadwal, bayar online, langsung main!</p>
            <div style="margin-top: var(--space-2xl);">
                <div style="display: flex; justify-content: center; gap: var(--space-xl);">
                    <div>
                        <div style="font-size: var(--text-3xl); font-weight: 700; color: var(--color-primary);">5+
                        </div>
                        <div style="font-size: var(--text-sm); opacity: 0.8;">Lapangan</div>
                    </div>
                    <div>
                        <div style="font-size: var(--text-3xl); font-weight: 700; color: var(--color-primary);">
                            1000+</div>
                        <div style="font-size: var(--text-sm); opacity: 0.8;">Booking/Bulan</div>
                    </div>
                    <div>
                        <div style="font-size: var(--text-3xl); font-weight: 700; color: var(--color-primary);">4.9
                        </div>
                        <div style="font-size: var(--text-sm); opacity: 0.8;">Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="auth-right">
        <div class="auth-form">
            <a href="../index.html"
                style="display: inline-flex; align-items: center; gap: var(--space-sm); color: var(--color-gray-600); margin-bottom: var(--space-xl); font-size: var(--text-sm);">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>

            <h2>Selamat Datang! 👋</h2>
            <p class="subtitle">Masuk untuk melanjutkan booking lapangan</p>

            <!-- Social Login -->
            <div class="social-login">
                <a href="#" class="social-btn">
                    <img src="https://www.google.com/favicon.ico" alt="Google" width="20">
                    Google
                </a>
                <a href="#" class="social-btn">
                    <i class="fab fa-facebook" style="color: #1877F2; font-size: 20px;"></i>
                    Facebook
                </a>
            </div>

            <div class="divider">atau masuk dengan email</div>

            <!-- Login Form -->
            <form id="loginForm" onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" placeholder="nama@email.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div style="position: relative;">
                        <input type="password" class="form-control" id="password" placeholder="Masukkan password"
                            required style="padding-right: 48px;">
                        <button type="button" onclick="togglePassword()"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--color-gray-500);">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                <div class="remember-row">
                    <label>
                        <input type="checkbox"> Ingat saya
                    </label>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div>
                <button type="submit" class="btn btn-primary w-full btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>

            <div class="form-footer">
                Belum punya akun? <a href="register.html">Daftar sekarang</a>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
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

    function handleLogin(e) {
        e.preventDefault();
        // Simulate login - in real app, this would call API
        alert('Login berhasil! Mengalihkan ke halaman booking...');
        window.location.href = 'jadwal.html';
    }
</script>
@endpush