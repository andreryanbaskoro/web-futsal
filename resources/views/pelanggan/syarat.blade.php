@extends('layouts.pelanggan')

@section('title', 'Syarat & Ketentuan | Futsal ACR')

@push('styles')
<style>
    .terms-content {
        max-width: 800px;
        margin: 0 auto;
    }

    .terms-content h2 {
        margin: var(--space-2xl) 0 var(--space-md);
        color: var(--color-dark);
        font-size: var(--text-2xl);
    }

    .terms-content h3 {
        margin: var(--space-xl) 0 var(--space-md);
        color: var(--color-dark);
        font-size: var(--text-xl);
    }

    .terms-content p {
        color: var(--color-gray-700);
        line-height: 1.8;
        margin-bottom: var(--space-md);
    }

    .terms-content ul,
    .terms-content ol {
        color: var(--color-gray-700);
        line-height: 1.8;
        margin-bottom: var(--space-lg);
        padding-left: var(--space-xl);
    }

    .terms-content li {
        margin-bottom: var(--space-sm);
    }

    .terms-toc {
        background: var(--color-gray-100);
        padding: var(--space-xl);
        border-radius: var(--radius-lg);
        margin-bottom: var(--space-2xl);
    }

    .terms-toc h4 {
        margin-bottom: var(--space-md);
    }

    .terms-toc ol {
        margin: 0;
    }

    .terms-toc a {
        color: var(--color-primary);
    }

    .terms-meta {
        display: flex;
        gap: var(--space-lg);
        margin-bottom: var(--space-xl);
        font-size: var(--text-sm);
        color: var(--color-gray-600);
    }
</style>
@endpush


@section('content')

<section style="background: var(--gradient-dark); padding: 140px 0 60px;">
    <div class="container text-center">
        <h1 style="color: var(--color-white); margin-bottom: var(--space-md);">Syarat & Ketentuan</h1>
        <p style="color: rgba(255,255,255,0.7);">Ketentuan penggunaan layanan FUSTAL ACR</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="terms-content">
            <div class="terms-meta">
                <span><i class="fas fa-calendar"></i> Terakhir diperbarui: 1 Januari 2026</span>
                <span><i class="fas fa-file-alt"></i> Versi 2.0</span>
            </div>

            <div class="terms-toc">
                <h4>Daftar Isi</h4>
                <ol>
                    <li><a href="#general">Ketentuan Umum</a></li>
                    <li><a href="#booking">Pemesanan Lapangan</a></li>
                    <li><a href="#payment">Pembayaran</a></li>
                    <li><a href="#cancellation">Pembatalan & Refund</a></li>
                    <li><a href="#rules">Peraturan Penggunaan</a></li>
                    <li><a href="#liability">Tanggung Jawab</a></li>
                    <li><a href="#privacy">Privasi</a></li>
                </ol>
            </div>

            <h2 id="general">1. Ketentuan Umum</h2>
            <p>Dengan mengakses dan menggunakan layanan FUSTAL ACR, Anda menyatakan telah membaca, memahami, dan
                menyetujui untuk terikat dengan syarat dan ketentuan ini. Jika Anda tidak menyetujui ketentuan ini,
                mohon untuk tidak menggunakan layanan kami.</p>
            <p>FUSTAL ACR berhak untuk mengubah, memodifikasi, atau memperbarui syarat dan ketentuan ini kapan
                saja tanpa pemberitahuan sebelumnya. Perubahan akan berlaku efektif segera setelah dipublikasikan di
                website.</p>

            <h2 id="booking">2. Pemesanan Lapangan</h2>
            <h3>2.1 Proses Booking</h3>
            <ul>
                <li>Pemesanan dapat dilakukan melalui website atau aplikasi resmi FUSTAL ACR.</li>
                <li>Untuk melakukan booking, pengguna wajib memiliki akun yang terdaftar.</li>
                <li>Booking dianggap sah setelah pembayaran berhasil dikonfirmasi oleh sistem.</li>
            </ul>

            <h3>2.2 Batas Waktu Pembayaran</h3>
            <ul>
                <li>Pengguna memiliki waktu 30 menit untuk menyelesaikan pembayaran setelah booking dibuat.</li>
                <li>Jika pembayaran tidak diselesaikan dalam waktu tersebut, booking akan otomatis dibatalkan.</li>
                <li>Jadwal yang dibatalkan akan tersedia kembali untuk pengguna lain.</li>
            </ul>

            <h3>2.3 Kedatangan</h3>
            <ul>
                <li>Pengguna diharapkan datang 15 menit sebelum jadwal bermain untuk persiapan.</li>
                <li>Keterlambatan lebih dari 15 menit akan mengurangi durasi bermain.</li>
                <li>Tunjukkan bukti booking kepada petugas saat kedatangan.</li>
            </ul>

            <h2 id="payment">3. Pembayaran</h2>
            <p>Metode pembayaran yang tersedia:</p>
            <ul>
                <li>Transfer Bank (BCA, Mandiri, BNI, BRI)</li>
                <li>Virtual Account</li>
                <li>QRIS</li>
                <li>E-Wallet (GoPay, OVO, DANA, ShopeePay)</li>
            </ul>
            <p>Biaya admin sebesar Rp 2.500 akan dikenakan untuk setiap transaksi sebagai biaya pemrosesan
                pembayaran.</p>

            <h2 id="cancellation">4. Pembatalan & Refund</h2>
            <h3>4.1 Kebijakan Pembatalan</h3>
            <ul>
                <li><strong>H-24 jam atau lebih:</strong> Refund 100% dari total pembayaran</li>
                <li><strong>H-12 jam sampai H-24 jam:</strong> Refund 50% dari total pembayaran</li>
                <li><strong>Kurang dari H-12 jam:</strong> Tidak ada refund</li>
            </ul>

            <h3>4.2 Proses Refund</h3>
            <ul>
                <li>Refund akan diproses dalam waktu 3-5 hari kerja.</li>
                <li>Dana akan dikembalikan ke metode pembayaran yang sama.</li>
                <li>Biaya admin tidak dapat di-refund.</li>
            </ul>

            <h2 id="rules">5. Peraturan Penggunaan Lapangan</h2>
            <ul>
                <li>Wajib menggunakan sepatu futsal (non-marking sole).</li>
                <li>Dilarang membawa makanan dan minuman ke dalam lapangan.</li>
                <li>Dilarang merokok di area lapangan dan ruangan ber-AC.</li>
                <li>Dilarang melakukan tindakan yang dapat merusak fasilitas.</li>
                <li>Pengguna bertanggung jawab atas keamanan barang bawaan pribadi.</li>
                <li>Wajib menjaga kebersihan dan ketertiban selama menggunakan fasilitas.</li>
                <li>Pengguna wajib mengakhiri sesi bermain tepat waktu.</li>
            </ul>

            <h2 id="liability">6. Tanggung Jawab</h2>
            <p>FUSTAL ACR tidak bertanggung jawab atas:</p>
            <ul>
                <li>Cedera yang terjadi selama aktivitas bermain.</li>
                <li>Kehilangan atau kerusakan barang pribadi pengguna.</li>
                <li>Kerugian akibat pembatalan yang dilakukan oleh pengguna.</li>
            </ul>
            <p>Pengguna disarankan untuk melakukan pemanasan sebelum bermain dan menggunakan perlengkapan pelindung
                jika diperlukan.</p>

            <h2 id="privacy">7. Privasi</h2>
            <p>Kami menghargai privasi Anda. Data pribadi yang dikumpulkan akan digunakan hanya untuk keperluan
                layanan dan tidak akan dibagikan kepada pihak ketiga tanpa persetujuan Anda, kecuali diwajibkan oleh
                hukum.</p>
            <p>Untuk informasi lebih lanjut, silakan baca Kebijakan Privasi kami.</p>

            <div
                style="background: var(--color-gray-100); padding: var(--space-xl); border-radius: var(--radius-lg); margin-top: var(--space-2xl);">
                <h4 style="margin-bottom: var(--space-md);">Pertanyaan?</h4>
                <p style="margin-bottom: var(--space-md);">Jika Anda memiliki pertanyaan mengenai syarat dan
                    ketentuan ini, silakan hubungi kami:</p>
                <p style="margin: 0;">
                    <i class="fas fa-envelope" style="color: var(--color-primary); margin-right: 8px;"></i>
                    <a href="mailto:admin.futsalacr.id"
                        style="color: var(--color-primary);">admin.futsalacr.id</a>
                </p>
            </div>
        </div>
    </div>
</section>

@endsection