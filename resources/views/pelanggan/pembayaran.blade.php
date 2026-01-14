@extends('layouts.pelanggan')

@section('title', 'Pembayaran')


@section('content')
<style>
    .payment-method {
        border: 2px solid var(--color-gray-300);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        cursor: pointer;
        transition: var(--transition-base);
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }

    .payment-method:hover {
        border-color: var(--color-primary);
        background: rgba(29, 185, 84, 0.02);
    }

    .payment-method.selected {
        border-color: var(--color-primary);
        background: rgba(29, 185, 84, 0.05);
    }

    .payment-method input {
        display: none;
    }

    .payment-radio {
        width: 20px;
        height: 20px;
        border: 2px solid var(--color-gray-400);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .payment-method.selected .payment-radio {
        border-color: var(--color-primary);
    }

    .payment-method.selected .payment-radio::after {
        content: '';
        width: 10px;
        height: 10px;
        background: var(--color-primary);
        border-radius: 50%;
    }

    .payment-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--color-gray-100);
        border-radius: var(--radius-md);
        font-size: 24px;
    }

    .payment-info {
        flex: 1;
    }

    .payment-info h4 {
        margin-bottom: 2px;
    }

    .payment-info p {
        font-size: var(--text-sm);
        color: var(--color-gray-600);
    }

    .qris-box {
        text-align: center;
        padding: var(--space-xl);
        background: var(--color-white);
        border: 2px dashed var(--color-gray-300);
        border-radius: var(--radius-lg);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: var(--space-sm);
    }

    .summary-row.total {
        font-weight: 700;
        font-size: var(--text-lg);
    }
</style>

<section style="padding:120px 0 60px;background:var(--color-gray-100);min-height:100vh;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 400px; gap: var(--space-2xl); max-width: 1000px; margin:0 auto;">

            <!-- Payment Methods -->
            <div>
                <div class="card" style="padding: var(--space-xl); margin-bottom: var(--space-xl);">
                    <!-- Countdown -->
                    <div style="background: var(--gradient-dark); color: white; padding: var(--space-lg); border-radius: var(--radius-lg); margin-bottom: var(--space-xl); text-align:center;">
                        <p style="font-size: var(--text-sm); margin-bottom: var(--space-sm);">
                            <i class="fas fa-clock"></i> Waktu Tersisa
                        </p>
                        <div style="font-family: var(--font-primary); font-size: var(--text-3xl); font-weight:700;">
                            <span id="timer">30:00</span>
                        </div>
                    </div>

                    <h3 style="margin-bottom: var(--space-lg);">Pilih Metode Pembayaran</h3>

                    <!-- Bank Transfer -->
                    <div style="margin-bottom: var(--space-lg);">
                        <p style="font-size: var(--text-sm); color: var(--color-gray-600); margin-bottom: var(--space-md); font-weight:500;">TRANSFER BANK</p>
                        <div style="display: grid; gap: var(--space-md);">
                            <label class="payment-method" onclick="selectPayment(this)">
                                <input type="radio" name="payment" value="bca">
                                <span class="payment-radio"></span>
                                <div class="payment-icon" style="background:#0066AE; color:white;">BCA</div>
                                <div class="payment-info">
                                    <h4>BCA Virtual Account</h4>
                                    <p>Otomatis terverifikasi</p>
                                </div>
                            </label>
                            <label class="payment-method" onclick="selectPayment(this)">
                                <input type="radio" name="payment" value="mandiri">
                                <span class="payment-radio"></span>
                                <div class="payment-icon" style="background:#003876; color:white;">MDR</div>
                                <div class="payment-info">
                                    <h4>Mandiri Virtual Account</h4>
                                    <p>Otomatis terverifikasi</p>
                                </div>
                            </label>
                            <label class="payment-method" onclick="selectPayment(this)">
                                <input type="radio" name="payment" value="bni">
                                <span class="payment-radio"></span>
                                <div class="payment-icon" style="background:#F15A22; color:white;">BNI</div>
                                <div class="payment-info">
                                    <h4>BNI Virtual Account</h4>
                                    <p>Otomatis terverifikasi</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- E-Wallet -->
                    <div style="margin-bottom: var(--space-lg);">
                        <p style="font-size: var(--text-sm); color: var(--color-gray-600); margin-bottom: var(--space-md); font-weight:500;">E-WALLET</p>
                        <div style="display: grid; gap: var(--space-md);">
                            <label class="payment-method selected" onclick="selectPayment(this)">
                                <input type="radio" name="payment" value="qris" checked>
                                <span class="payment-radio"></span>
                                <div class="payment-icon"><i class="fas fa-qrcode" style="color: var(--color-primary);"></i></div>
                                <div class="payment-info">
                                    <h4>QRIS</h4>
                                    <p>Scan dengan semua e-wallet & mobile banking</p>
                                </div>
                            </label>
                            <label class="payment-method" onclick="selectPayment(this)">
                                <input type="radio" name="payment" value="gopay">
                                <span class="payment-radio"></span>
                                <div class="payment-icon" style="background:#00AED6; color:white;"><i class="fas fa-wallet"></i></div>
                                <div class="payment-info">
                                    <h4>GoPay</h4>
                                    <p>Bayar dengan saldo GoPay</p>
                                </div>
                            </label>
                            <label class="payment-method" onclick="selectPayment(this)">
                                <input type="radio" name="payment" value="ovo">
                                <span class="payment-radio"></span>
                                <div class="payment-icon" style="background:#4C3494; color:white;"><i class="fas fa-wallet"></i></div>
                                <div class="payment-info">
                                    <h4>OVO</h4>
                                    <p>Bayar dengan saldo OVO</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- QRIS Display -->
                <div class="card" style="padding: var(--space-xl);" id="qrisSection">
                    <h3 style="margin-bottom: var(--space-lg); text-align: center;">Scan QRIS untuk Membayar</h3>
                    <div class="qris-box">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $pemesanan->kode_pemesanan }}" alt="QRIS Code" style="margin: 0 auto var(--space-lg);">
                        <p style="font-size: var(--text-sm); color: var(--color-gray-600);">Scan dengan aplikasi e-wallet atau mobile banking</p>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="booking-summary card" style="padding: var(--space-xl);">
                    <h3>Ringkasan Pesanan</h3>

                    <div style="display: grid; gap: var(--space-md); margin-bottom: var(--space-lg); padding-bottom: var(--space-lg); border-bottom: 1px solid var(--color-gray-300);">
                        <div style="display:flex; justify-content:space-between;">
                            <span class="label">Lapangan</span>
                            <span class="value">{{ $lapangan->nama_lapangan }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span class="label">Kode Booking</span>
                            <span class="value">#{{ $pemesanan->kode_pemesanan }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span class="label">Tanggal</span>
                            <span class="value">{{ $tanggal }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span class="label">Waktu</span>
                            @php
                            $waktuText = '';
                            $totalDurasi = 0;
                            @endphp
                            @foreach($mergedSlots as $slot)
                            @php
                            $totalDurasi += $slot['durasi_menit'];
                            $startFormatted = \Carbon\Carbon::parse($slot['start'])->format('H:i');
                            $endFormatted = \Carbon\Carbon::parse($slot['end'])->format('H:i');
                            $waktuText .= $startFormatted . ' - ' . $endFormatted . ', ';
                            @endphp
                            @endforeach
                            @php $waktuText = rtrim($waktuText, ', '); @endphp
                            <span class="value">{{ $waktuText }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span class="label">Durasi</span>
                            <span class="value">{{ $totalDurasi }} Menit</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span class="label">Status</span>
                            <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Pembayaran</span>
                        </div>
                    </div>

                    <div style="border-top:2px solid var(--color-gray-300); margin-top:var(--space-lg); padding-top:var(--space-lg);">
                        <div style="display:flex; justify-content:space-between; font-size:var(--text-xl); font-weight:700;">
                            <span>Total Bayar</span>
                            <span style="color: var(--color-primary);">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button class="btn btn-primary w-full mt-lg btn-lg" onclick="processPayment('{{ $pemesanan->kode_pemesanan }}')">
                        <i class="fas fa-check-circle"></i> Konfirmasi Pembayaran
                    </button>
                    <p style="text-align: center; font-size:12px; color: var(--color-gray-500); margin-top:var(--space-md);">
                        <i class="fas fa-lock"></i> Pembayaran aman & terenkripsi
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
function processPayment(kode) {
    // disable tombol dll
    const btn = event.target.closest('button');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

    fetch("{{ route('pelanggan.payment.create_snap') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ kode })
    })
    .then(r => r.json())
    .then(data => {
        if (data.snap_token) {
            snap.pay(data.snap_token, {
                onSuccess: function(result){
                    // sukses -> redirect atau refresh
                    window.location.href = "{{ route('pelanggan.booking.history') }}";
                },
                onPending: function(result){
                    // transaksi pending (VA etc)
                    alert('Transaksi pending. Silakan selesaikan pembayaran.');
                    window.location.href = "{{ route('pelanggan.booking.history') }}";
                },
                onError: function(result){
                    alert('Terjadi error saat memproses pembayaran: ' + result);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check-circle"></i> Konfirmasi Pembayaran';
                },
                onClose: function(){
                    // user menutup popup
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check-circle"></i> Konfirmasi Pembayaran';
                }
            });
        } else {
            alert('Gagal membuat transaksi: ' + (data.error || 'unknown'));
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle"></i> Konfirmasi Pembayaran';
        }
    })
    .catch(err => {
        alert('Network error: ' + err);
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check-circle"></i> Konfirmasi Pembayaran';
    });
}
</script>

<script>
    function selectPayment(element) {
        document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');
        element.querySelector('input').checked = true;
    }

    function processPayment(kode) {
        const btn = event.target.closest('button');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        btn.disabled = true;

        // Simulasi proses payment
        setTimeout(() => {
            alert('Pembayaran berhasil untuk kode booking ' + kode);
            window.location.href = "{{ route('pelanggan.booking.history') }}";
        }, 2000);
    }

    // Countdown berdasarkan expired_at
    const expiresAt = new Date("{{ $pemesanan->expired_at->toIso8601String() }}");
    const timerEl = document.getElementById('timer');

    function updateCountdown() {
        const now = new Date();
        let diff = Math.floor((expiresAt - now) / 1000);
        if (diff < 0) diff = 0;

        const mins = Math.floor(diff / 60);
        const secs = diff % 60;
        timerEl.textContent = `${mins.toString().padStart(2,'0')}:${secs.toString().padStart(2,'0')}`;

        if (diff > 0) {
            setTimeout(updateCountdown, 1000);
        } else {
            alert('Waktu pembayaran habis! Booking dibatalkan.');
            window.location.href = "{{ route('pelanggan.jadwal') }}";
        }
    }

    updateCountdown();
</script>
@endsection