@extends('layouts.app')

@section('title', 'Booking Lapangan Futsal Online')

@section('content')


<section style="background: var(--gradient-dark); padding: 140px 0 60px;">
    <div class="container text-center">
        <h1 style="color: var(--color-white); margin-bottom: var(--space-md);">Tentang Kami</h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">Mengenal lebih dekat FUSTAL ACR
        </p>
    </div>
</section>

<!-- About Story -->
<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-3xl); align-items: center;">
            <div>
                <span class="subtitle"
                    style="color: var(--color-primary); font-size: var(--text-sm); font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">Cerita
                    Kami</span>
                <h2 style="margin: var(--space-md) 0;">Bermain Futsal dengan Pengalaman Terbaik</h2>
                <p style="color: var(--color-gray-600); margin-bottom: var(--space-lg); line-height: 1.8;">
                    FUSTAL ACR didirikan pada tahun 2020 dengan satu visi sederhana: memberikan pengalaman bermain
                    futsal terbaik untuk semua kalangan. Kami percaya bahwa olahraga harus mudah diakses dan
                    menyenangkan.
                </p>
                <p style="color: var(--color-gray-600); margin-bottom: var(--space-lg); line-height: 1.8;">
                    Berawal dari satu lapangan kecil, kini kami telah berkembang menjadi kompleks futsal modern
                    dengan 5 lapangan berkualitas internasional. Sistem booking online kami memudahkan pelanggan
                    untuk memesan lapangan kapan saja dan di mana saja.
                </p>
                <div style="display: flex; gap: var(--space-2xl); margin-top: var(--space-xl);">
                    <div style="text-align: center;">
                        <div style="font-size: var(--text-3xl); font-weight: 700; color: var(--color-primary);">5+
                        </div>
                        <div style="font-size: var(--text-sm); color: var(--color-gray-600);">Tahun Beroperasi</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: var(--text-3xl); font-weight: 700; color: var(--color-primary);">50K+
                        </div>
                        <div style="font-size: var(--text-sm); color: var(--color-gray-600);">Booking Selesai</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: var(--text-3xl); font-weight: 700; color: var(--color-primary);">10K+
                        </div>
                        <div style="font-size: var(--text-sm); color: var(--color-gray-600);">Pelanggan Aktif</div>
                    </div>
                </div>
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1552667466-07770ae110d0?w=600&q=80" alt="FUSTAL ACR"
                    style="width: 100%; border-radius: var(--radius-xl); box-shadow: var(--shadow-xl);">
            </div>
        </div>
    </div>
</section>

<!-- Vision Mission -->
<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <span class="subtitle">Visi & Misi</span>
            <h2>Komitmen Kami</h2>
        </div>
        <div class="grid grid-2" style="max-width: 900px; margin: 0 auto;">
            <div class="card" style="padding: var(--space-2xl); text-align: center;">
                <div
                    style="width: 80px; height: 80px; background: var(--gradient-primary); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <i class="fas fa-eye" style="font-size: 32px; color: white;"></i>
                </div>
                <h3 style="margin-bottom: var(--space-md);">Visi</h3>
                <p style="color: var(--color-gray-600); line-height: 1.8;">Menjadi penyedia lapangan futsal terbaik
                    di Indonesia dengan standar internasional dan teknologi booking modern yang memudahkan semua
                    pecinta futsal.</p>
            </div>
            <div class="card" style="padding: var(--space-2xl); text-align: center;">
                <div
                    style="width: 80px; height: 80px; background: var(--gradient-primary); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <i class="fas fa-bullseye" style="font-size: 32px; color: white;"></i>
                </div>
                <h3 style="margin-bottom: var(--space-md);">Misi</h3>
                <p style="color: var(--color-gray-600); line-height: 1.8;">Menyediakan fasilitas berkualitas tinggi,
                    layanan profesional, dan sistem booking yang mudah untuk memberikan pengalaman bermain futsal
                    yang tak terlupakan.</p>
            </div>
        </div>
    </div>
</section>

<!-- Values -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="subtitle">Nilai-Nilai Kami</span>
            <h2>Yang Kami Junjung Tinggi</h2>
        </div>
        <div class="grid grid-4">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-star"></i></div>
                <h3>Kualitas</h3>
                <p>Standar tinggi dalam setiap aspek fasilitas dan layanan</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-handshake"></i></div>
                <h3>Kepercayaan</h3>
                <p>Membangun hubungan jangka panjang dengan pelanggan</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-lightbulb"></i></div>
                <h3>Inovasi</h3>
                <p>Terus berinovasi untuk pengalaman yang lebih baik</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-heart"></i></div>
                <h3>Kepedulian</h3>
                <p>Mengutamakan kepuasan dan kenyamanan pelanggan</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Bergabunglah Bersama Kami</h2>
            <p>Jadilah bagian dari komunitas futsal terbesar. Booking lapangan sekarang dan rasakan pengalaman
                bermain yang berbeda!</p>
            <a href="jadwal.html" class="btn btn-accent btn-lg"><i class="fas fa-calendar-check"></i> Booking
                Sekarang</a>
        </div>
    </div>
</section>

@endsection