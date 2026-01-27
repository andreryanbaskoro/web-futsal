@extends('layouts.app')

@section('title', 'Kontak | Futsal ACR')

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

    .btn-loading {
        align-items: center;
        gap: 6px;
    }

    button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    #contact-section {
        scroll-margin-top: 120px;
    }
</style>


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
                        Jl. Youmakhe Kelurahan Hinekombe,Distrik Sentani, Kabupaten Jayapura<br>
                        Papua 99351
                    </p>
                    <a href="https://maps.app.goo.gl/zsGSjPPAFSxytkjBA" target="_blank">Lihat di Maps <i
                            class="fas fa-external-link-alt"></i></a>
                </div>
            </div>
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                <div>
                    <h4>Telepon</h4>
                    <p>0811481679</p>
                    <p>08134751554</p>
                    <a href="tel:+6281234567890">Hubungi Sekarang</a>
                </div>
            </div>
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <h4>Email</h4>
                    <p>acr@futsal.id</p>
                    <a href="mailto:admin.futsalacr.id">Kirim Email</a>
                </div>
            </div>
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-clock"></i></div>
                <div>
                    <h4>Jam Operasional</h4>
                    <p>Senin - Minggu</p>
                    <p><strong>10:00 - 22:00 WIT</strong></p>
                    <span style="color: var(--color-success); font-weight: 500;"><i class="fas fa-circle"
                            style="font-size: 8px;"></i> Buka Sekarang</span>
                </div>
            </div>
        </div>

        <!-- Contact Form & Map -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-2xl);">
            <!-- Form -->
            <div class="card" style="padding: var(--space-2xl);" id="contact-section">
                <h3 style="margin-bottom: var(--space-lg);">Kirim Pesan</h3>

                {{-- ALERT SUCCESS --}}
                @include('elements.flash-messages')

                {{-- ALERT ERROR --}}
                @if ($errors->any())
                <div class="alert alert-danger" style="margin-bottom: var(--space-md);">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form id="contact-form" action="{{ route('contact.send') }}" method="POST">
                    @csrf

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                placeholder="Nama Anda"
                                value="{{ old('nama') }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="email@contoh.com"
                                value="{{ old('email') }}"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input
                            type="tel"
                            name="no_telepon"
                            class="form-control"
                            placeholder="08xxxxxxxxxx"
                            value="{{ old('no_telepon') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Subjek</label>
                        <select name="subjek" class="form-control form-select" required>
                            <option value="">Pilih subjek</option>
                            <option value="booking" {{ old('subjek') == 'booking' ? 'selected' : '' }}>Pertanyaan Booking</option>
                            <option value="payment" {{ old('subjek') == 'payment' ? 'selected' : '' }}>Masalah Pembayaran</option>
                            <option value="facility" {{ old('subjek') == 'facility' ? 'selected' : '' }}>Fasilitas</option>
                            <option value="partnership" {{ old('subjek') == 'partnership' ? 'selected' : '' }}>Kerjasama</option>
                            <option value="other" {{ old('subjek') == 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pesan</label>
                        <textarea
                            name="pesan"
                            class="form-control"
                            rows="5"
                            placeholder="Tulis pesan Anda..."
                            required>{{ old('pesan') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-full" id="btn-submit">
                        <span class="btn-text">
                            <i class="fas fa-paper-plane"></i> Kirim Pesan
                        </span>
                        <span class="btn-loading" style="display:none;">
                            <i class="fas fa-spinner fa-spin"></i> Mengirim...
                        </span>
                    </button>

                </form>
            </div>

            <!-- Map -->
            <div>
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1004.7832127544837!2d140.49097173394867!3d-2.5689107064228565!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x686cee6cbdb22761%3A0x46cdedacded9c58a!2sLapangan%20Futsal%20ACR%20sentani!5e0!3m2!1sid!2sid!4v1768146218447!5m2!1sid!2sid"
                        width="100%"
                        height="400"
                        style="border:0;"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Social Media -->
<section class="section bg-light">
    <div class="container text-center">
        <h3 style="margin-bottom: var(--space-md);">Ikuti Kami di Media Sosial</h3>
        <p style="color: var(--color-gray-600); margin-bottom: var(--space-xl);">Dapatkan update terbaru, promo, dan info turnamen</p>
        <div style="display: flex; justify-content: center; gap: var(--space-md);">
            <a href="https://www.facebook.com" target="_blank"
                style="width: 60px; height: 60px; background: #1877F2; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; transition: var(--transition-base);"
                onmouseover="this.style.transform='translateY(-4px)'"
                onmouseout="this.style.transform='translateY(0)'">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.instagram.com" target="_blank"
                style="width: 60px; height: 60px; background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; transition: var(--transition-base);"
                onmouseover="this.style.transform='translateY(-4px)'"
                onmouseout="this.style.transform='translateY(0)'">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://x.com" target="_blank"
                style="width: 60px; height: 60px; background: #000000; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; transition: var(--transition-base);"
                onmouseover="this.style.transform='translateY(-4px)'"
                onmouseout="this.style.transform='translateY(0)'">
                <i class="fab fa-x"></i>
            </a>
            <a href="https://www.youtube.com" target="_blank"
                style="width: 60px; height: 60px; background: #FF0000; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; transition: var(--transition-base);"
                onmouseover="this.style.transform='translateY(-4px)'"
                onmouseout="this.style.transform='translateY(0)'">
                <i class="fab fa-youtube"></i>
            </a>
            <a href="https://wa.me/yourphonenumber" target="_blank"
                style="width: 60px; height: 60px; background: #25D366; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; transition: var(--transition-base);"
                onmouseover="this.style.transform='translateY(-4px)'"
                onmouseout="this.style.transform='translateY(0)'">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </div>

</section>


@endsection

@push('scripts')
<script src="{{ asset('js/booking.js') }}"></script>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // 🔹 Disable button + loading
        const form = document.getElementById('contact-form');
        const btn = document.getElementById('btn-submit');
        const text = btn.querySelector('.btn-text');
        const loading = btn.querySelector('.btn-loading');

        if (form) {
            form.addEventListener('submit', function() {
                btn.disabled = true;
                text.style.display = 'none';
                loading.style.display = 'inline-flex';
            });
        }

        // 🔹 Auto scroll ke form jika ada success / error
        @if(session() -> has('success') || $errors -> any())
        const target = document.getElementById('contact-section');
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
        @endif
    });
</script>
@endpush