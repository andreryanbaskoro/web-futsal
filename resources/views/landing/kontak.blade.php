@extends('layouts.app')

@section('title', 'Booking Lapangan Futsal Online')


@push('styles')
<style>
    .contact-card {
        background: var(--color-white);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        box-shadow: var(--shadow-card);
        display: flex;
        align-items: flex-start;
        gap: var(--space-lg);
        transition: var(--transition-base);
    }

    .contact-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
    }

    .contact-icon {
        width: 60px;
        height: 60px;
        background: var(--gradient-primary);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        flex-shrink: 0;
    }

    .contact-card h4 {
        margin-bottom: var(--space-sm);
    }

    .contact-card p {
        color: var(--color-gray-600);
        margin-bottom: var(--space-sm);
    }

    .contact-card a {
        color: var(--color-primary);
        font-weight: 500;
    }

    .map-container {
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .map-container iframe {
        width: 100%;
        height: 400px;
        border: none;
    }
</style>
@endpush

@section('content')
<section style="background: var(--gradient-dark); padding: 140px 0 60px;">
    <div class="container text-center">
        <h1 style="color: var(--color-white); margin-bottom: var(--space-md);">Hubungi Kami</h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">Ada pertanyaan? Jangan ragu untuk
            menghubungi kami</p>
    </div>
</section>

<!-- Contact Cards -->
<section class="section">
    <div class="container">
        <div class="grid grid-4" style="margin-bottom: var(--space-3xl);">
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <h4>Alamat</h4>
                    <p>
                        Jl. Sentani No. 123,
                        Setani, Jayapura,
                        Papua 99351
                    </p>
                    <a href="https://maps.google.com" target="_blank">Lihat di Maps <i
                            class="fas fa-external-link-alt"></i></a>
                </div>
            </div>
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                <div>
                    <h4>Telepon</h4>
                    <p>+62 812-3456-7890</p>
                    <p>+62 21-1234-5678</p>
                    <a href="tel:+6281234567890">Hubungi Sekarang</a>
                </div>
            </div>
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <h4>Email</h4>
                    <p>admin.futsalacr.id</p>
                    <p>bookingadmin.futsalacr.id</p>
                    <a href="mailto:admin.futsalacr.id">Kirim Email</a>
                </div>
            </div>
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-clock"></i></div>
                <div>
                    <h4>Jam Operasional</h4>
                    <p>Senin - Minggu</p>
                    <p><strong>Buka 24 Jam</strong></p>
                    <span style="color: var(--color-success); font-weight: 500;"><i class="fas fa-circle"
                            style="font-size: 8px;"></i> Buka Sekarang</span>
                </div>
            </div>
        </div>

        <!-- Contact Form & Map -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-2xl);">
            <!-- Form -->
            <div class="card" style="padding: var(--space-2xl);">
                <h3 style="margin-bottom: var(--space-lg);">Kirim Pesan</h3>
                <form onsubmit="handleContact(event)">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" placeholder="Nama Anda" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" placeholder="email@contoh.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="tel" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Subjek</label>
                        <select class="form-control form-select">
                            <option value="">Pilih subjek</option>
                            <option value="booking">Pertanyaan Booking</option>
                            <option value="payment">Masalah Pembayaran</option>
                            <option value="facility">Fasilitas</option>
                            <option value="partnership">Kerjasama</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pesan</label>
                        <textarea class="form-control" rows="5" placeholder="Tulis pesan Anda..."
                            required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="fas fa-paper-plane"></i> Kirim Pesan
                    </button>
                </form>
            </div>

            <!-- Map -->
            <div>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1004.7832127544837!2d140.49097173394867!3d-2.5689107064228565!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x686cee6cbdb22761%3A0x46cdedacded9c58a!2sLapangan%20Futsal%20ACR%20sentani!5e0!3m2!1sid!2sid!4v1768146218447!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <!-- <div class="card" style="padding: var(--space-lg); margin-top: var(--space-lg);">
                    <h4 style="margin-bottom: var(--space-md);">Petunjuk Arah</h4>
                    <ul
                        style="display: grid; gap: var(--space-sm); color: var(--color-gray-600); font-size: var(--text-sm);">
                        <li><i class="fas fa-car" style="color: var(--color-primary); margin-right: 8px;"></i> 5
                            menit dari Tol Kebayoran Baru</li>
                        <li><i class="fas fa-train" style="color: var(--color-primary); margin-right: 8px;"></i> 10
                            menit dari Stasiun MRT Blok M</li>
                        <li><i class="fas fa-bus" style="color: var(--color-primary); margin-right: 8px;"></i> Halte
                            Transjakarta terdekat: Blok M</li>
                        <li><i class="fas fa-parking" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Parkir gratis untuk 100 mobil & motor</li>
                    </ul>
                </div> -->
            </div>
        </div>
    </div>
</section>

<!-- Social Media -->
<section class="section bg-light">
    <div class="container text-center">
        <h3 style="margin-bottom: var(--space-md);">Ikuti Kami di Media Sosial</h3>
        <p style="color: var(--color-gray-600); margin-bottom: var(--space-xl);">Dapatkan update terbaru, promo, dan
            info turnamen</p>
        <div style="display: flex; justify-content: center; gap: var(--space-md);">
            <a href="#"
                style="width: 60px; height: 60px; background: #1877F2; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; transition: var(--transition-base);"
                onmouseover="this.style.transform='translateY(-4px)'"
                onmouseout="this.style.transform='translateY(0)'"><i class="fab fa-facebook-f"></i></a>
            <a href="#"
                style="width: 60px; height: 60px; background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; transition: var(--transition-base);"
                onmouseover="this.style.transform='translateY(-4px)'"
                onmouseout="this.style.transform='translateY(0)'"><i class="fab fa-instagram"></i></a>
            <a href="#"
                style="width: 60px; height: 60px; background: #1DA1F2; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; transition: var(--transition-base);"
                onmouseover="this.style.transform='translateY(-4px)'"
                onmouseout="this.style.transform='translateY(0)'"><i class="fab fa-twitter"></i></a>
            <a href="#"
                style="width: 60px; height: 60px; background: #FF0000; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; transition: var(--transition-base);"
                onmouseover="this.style.transform='translateY(-4px)'"
                onmouseout="this.style.transform='translateY(0)'"><i class="fab fa-youtube"></i></a>
            <a href="#"
                style="width: 60px; height: 60px; background: #25D366; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; transition: var(--transition-base);"
                onmouseover="this.style.transform='translateY(-4px)'"
                onmouseout="this.style.transform='translateY(0)'"><i class="fab fa-whatsapp"></i></a>
        </div>
    </div>
</section>


@endsection

@push('scripts')
<script src="{{ asset('js/booking.js') }}"></script>
@endpush

@push('scripts')
<script>
    function handleContact(e) {
        e.preventDefault();
        alert('Pesan Anda telah terkirim! Kami akan membalas dalam 1x24 jam.');
        e.target.reset();
    }
</script>
@endpush