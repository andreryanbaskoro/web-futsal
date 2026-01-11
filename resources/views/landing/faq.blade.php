@extends('layouts.app')

@section('title', 'Booking Lapangan Futsal Online')


@push('styles')
<style>
    .faq-item {
        background: var(--color-white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card);
        margin-bottom: var(--space-md);
        overflow: hidden;
    }

    .faq-question {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-lg);
        cursor: pointer;
        font-weight: 600;
        transition: var(--transition-base);
    }

    .faq-question:hover {
        background: var(--color-gray-100);
    }

    .faq-question i {
        transition: transform var(--transition-base);
        color: var(--color-primary);
    }

    .faq-item.active .faq-question i {
        transform: rotate(180deg);
    }

    .faq-answer {
        padding: 0 var(--space-lg);
        max-height: 0;
        overflow: hidden;
        transition: all var(--transition-base);
    }

    .faq-item.active .faq-answer {
        padding: 0 var(--space-lg) var(--space-lg);
        max-height: 500px;
    }

    .faq-answer p {
        color: var(--color-gray-600);
        line-height: 1.8;
    }

    .faq-category {
        margin-bottom: var(--space-2xl);
    }

    .faq-category-title {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-lg);
        padding-bottom: var(--space-md);
        border-bottom: 2px solid var(--color-gray-200);
    }

    .faq-category-title i {
        width: 40px;
        height: 40px;
        background: var(--gradient-primary);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
</style>
@endpush

@section('content')

<section style="background: var(--gradient-dark); padding: 140px 0 60px;">
    <div class="container text-center">
        <h1 style="color: var(--color-white); margin-bottom: var(--space-md);">FAQ</h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">Pertanyaan yang sering diajukan
        </p>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <!-- Booking -->
        <div class="faq-category">
            <div class="faq-category-title">
                <i class="fas fa-calendar-check"></i>
                <h2>Booking & Pemesanan</h2>
            </div>
            <div class="faq-item active">
                <div class="faq-question" onclick="toggleFaq(this)">
                    Bagaimana cara booking lapangan?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Untuk booking lapangan, ikuti langkah berikut: (1) Pilih lapangan yang diinginkan, (2)
                        Tentukan tanggal dan jam yang tersedia, (3) Login atau daftar akun, (4) Konfirmasi
                        pemesanan, (5) Lakukan pembayaran. Setelah pembayaran berhasil, booking Anda otomatis
                        terkonfirmasi dan Anda akan menerima email konfirmasi.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    Berapa lama waktu untuk membayar booking?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Anda memiliki waktu <strong>30 menit</strong> untuk menyelesaikan pembayaran setelah booking
                        dibuat. Jika melebihi batas waktu tersebut, booking akan otomatis dibatalkan dan jadwal akan
                        tersedia kembali untuk pelanggan lain.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    Apakah bisa booking untuk beberapa jam sekaligus?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Ya, tentu bisa! Anda dapat memilih beberapa slot waktu sekaligus dalam satu transaksi
                        booking. Pilih jam-jam yang berurutan atau tidak berurutan sesuai kebutuhan Anda.</p>
                </div>
            </div>
        </div>

        <!-- Payment -->
        <div class="faq-category">
            <div class="faq-category-title">
                <i class="fas fa-credit-card"></i>
                <h2>Pembayaran</h2>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    Metode pembayaran apa saja yang tersedia?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Kami menyediakan berbagai metode pembayaran: Transfer Bank (BCA, Mandiri, BNI, BRI), QRIS
                        (bisa scan dengan semua e-wallet), GoPay, OVO, DANA, dan ShopeePay. Semua pembayaran
                        dilakukan secara online dan otomatis terverifikasi.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    Apakah ada biaya tambahan?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Ya, terdapat biaya admin sebesar Rp 2.500 per transaksi untuk biaya payment gateway. Biaya
                        ini akan ditampilkan secara transparan sebelum Anda melakukan pembayaran.</p>
                </div>
            </div>
        </div>

        <!-- Cancellation -->
        <div class="faq-category">
            <div class="faq-category-title">
                <i class="fas fa-times-circle"></i>
                <h2>Pembatalan & Refund</h2>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    Apakah bisa refund jika batal?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Ya, pembatalan dapat dilakukan dengan ketentuan berikut:<br>
                        • <strong>H-24 jam:</strong> Refund 100%<br>
                        • <strong>H-12 jam:</strong> Refund 50%<br>
                        • <strong>Kurang dari H-12 jam:</strong> Tidak ada refund<br>
                        Refund akan dikembalikan ke metode pembayaran yang sama dalam 3-5 hari kerja.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    Bagaimana cara membatalkan booking?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Untuk membatalkan booking, masuk ke akun Anda, buka menu "Riwayat Pemesanan", pilih booking
                        yang ingin dibatalkan, lalu klik tombol "Batalkan". Anda akan menerima email konfirmasi
                        pembatalan.</p>
                </div>
            </div>
        </div>

        <!-- Facilities -->
        <div class="faq-category">
            <div class="faq-category-title">
                <i class="fas fa-building"></i>
                <h2>Fasilitas</h2>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    Fasilitas apa saja yang tersedia?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Fasilitas yang tersedia meliputi: Ruang ganti & locker, Kamar mandi dengan shower, Musholla,
                        Cafe & resto, Parkir luas, Free WiFi, Tribun penonton, dan Sewa sepatu & rompi (opsional,
                        dengan biaya tambahan).</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    Apakah bisa sewa sepatu dan rompi?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Ya, kami menyediakan layanan sewa sepatu futsal (Rp 15.000/pasang) dan rompi tim (Rp
                        10.000/set). Informasikan kepada petugas saat Anda datang ke lapangan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section bg-light">
    <div class="container text-center">
        <h3 style="margin-bottom: var(--space-md);">Masih punya pertanyaan?</h3>
        <p style="color: var(--color-gray-600); margin-bottom: var(--space-xl);">Hubungi tim kami untuk bantuan
            lebih lanjut</p>
        <div style="display: flex; justify-content: center; gap: var(--space-md); flex-wrap: wrap;">
            <a href="kontak.html" class="btn btn-primary"><i class="fas fa-envelope"></i> Hubungi Kami</a>
            <a href="https://wa.me/6281234567890" class="btn btn-outline"
                style="border-color: #25D366; color: #25D366;"><i class="fab fa-whatsapp"></i> Chat WhatsApp</a>
        </div>
    </div>
</section>

@endsection
@push('scripts')
<script src="{{ asset('js/booking.js') }}"></script>
@endpush
@push('scripts')
<script>
    function toggleFaq(element) {
        const item = element.parentElement;
        const wasActive = item.classList.contains('active');

        // Close all items in the same category
        const category = item.closest('.faq-category');
        category.querySelectorAll('.faq-item').forEach(faq => faq.classList.remove('active'));

        // Toggle clicked item
        if (!wasActive) {
            item.classList.add('active');
        }
    }
</script>
@endpush