@extends('layouts.pelanggan')

@section('title', 'Booking Berhasil')

@section('content')
<section style="padding:120px 0 60px;background:var(--color-gray-100);min-height:100vh;">
    <div class="container">
        <div style="max-width:600px;margin:0 auto;">

            <!-- SUCCESS BANNER -->
            <div class="card" style="padding:var(--space-2xl);text-align:center;margin-bottom:var(--space-xl);">
                <div style="width:80px;height:80px;background:rgba(67,160,71,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-lg);">
                    <i class="fas fa-check-circle" style="font-size:40px;color:var(--color-success);"></i>
                </div>

                <h2 style="margin-bottom:var(--space-sm);">Booking Berhasil Dibuat!</h2>
                <p style="color:var(--color-gray-600);">Kode Booking Anda:</p>

                <div style="
                    font-family:var(--font-primary);
                    font-size:var(--text-2xl);
                    font-weight:700;
                    color:var(--color-primary);
                    background:rgba(29,185,84,.1);
                    padding:var(--space-md);
                    border-radius:var(--radius-lg);
                    margin-top:var(--space-sm);
                ">
                    #{{ $pemesanan->kode_pemesanan }}
                </div>
            </div>

            <!-- COUNTDOWN -->
            <div class="card" style="padding:var(--space-xl);margin-bottom:var(--space-xl);background:var(--gradient-dark);color:#fff;">
                <div style="text-align:center;">
                    <p style="margin-bottom:var(--space-md);display:flex;align-items:center;justify-content:center;gap:var(--space-sm);">
                        <i class="fas fa-clock"></i> Selesaikan Pembayaran Dalam
                    </p>

                    <div class="countdown" style="display:flex;justify-content:center;gap:24px;">
                        <div class="countdown-item">
                            <div class="countdown-value" id="hours">00</div>
                            <div class="countdown-label" style="color:rgba(255,255,255,.7);">Jam</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-value" id="minutes">30</div>
                            <div class="countdown-label" style="color:rgba(255,255,255,.7);">Menit</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-value" id="seconds">00</div>
                            <div class="countdown-label" style="color:rgba(255,255,255,.7);">Detik</div>
                        </div>
                    </div>

                    <p style="margin-top:var(--space-lg);font-size:var(--text-sm);opacity:.8;">
                        Batas Pembayaran: {{ now()->addMinutes(30)->translatedFormat('d M Y, H:i') }} WIT
                    </p>
                </div>
            </div>

            <!-- DETAIL PEMESANAN -->
            <div class="card" style="padding:var(--space-xl);margin-bottom:var(--space-xl);">
                <h3 style="margin-bottom:var(--space-lg);padding-bottom:var(--space-md);border-bottom:1px solid var(--color-gray-300);">
                    <i class="fas fa-receipt" style="color:var(--color-primary);margin-right:var(--space-sm);"></i>
                    Detail Pemesanan
                </h3>

                <div style="display:grid;gap:var(--space-md);">
                    {{-- Lapangan --}}
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--color-gray-600);">Lapangan</span>
                        <span style="font-weight:500;">{{ $lapangan->nama_lapangan }}</span>
                    </div>

                    {{-- Tanggal --}}
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--color-gray-600);">Tanggal</span>
                        <span style="font-weight:500;">
                            {{ $tanggal }}
                        </span>
                    </div>

                    {{-- Waktu --}}
                    @php
                    $totalDurasi = 0;
                    $waktuList = [];
                    @endphp

                    @foreach ($mergedSlots as $slot)
                    @php
                    $totalDurasi += $slot['durasi_menit'];
                    $waktuList[] = $slot['start'] . ' - ' . $slot['end'];
                    @endphp
                    @endforeach

                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--color-gray-600);">Waktu</span>
                        <span style="font-weight:500;">
                            {{ implode(', ', $waktuList) }}
                        </span>
                    </div>

                    {{-- Durasi --}}
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--color-gray-600);">Durasi</span>
                        <span style="font-weight:500;">
                            {{ $totalDurasi }} Menit
                        </span>
                    </div>




                    {{-- Status --}}
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--color-gray-600);">Status</span>
                        <span class="badge badge-warning">
                            <i class="fas fa-clock"></i> Menunggu Pembayaran
                        </span>
                    </div>
                </div>

                {{-- Total --}}
                <div style="border-top:2px solid var(--color-gray-300);margin-top:var(--space-lg);padding-top:var(--space-lg);">
                    <div style="display:flex;justify-content:space-between;font-size:var(--text-xl);font-weight:700;">
                        <span>Total</span>
                        <span style="color:var(--color-primary);">
                            Rp {{ number_format($total,0,',','.') }}
                        </span>
                    </div>
                </div>
            </div>


            <div>
                <button onclick="payNow('{{ $pemesanan->kode_pemesanan }}')" class="btn btn-primary btn-lg w-full" style="margin-bottom: 12px;">
                    <i class="fas fa-credit-card"></i> Lanjut ke Pembayaran
                </button>

                <a href="{{ route('pelanggan.booking.history') }}" class="btn btn-outline w-full" style="margin-top: 12px;">
                    <i class="fas fa-history"></i> Lihat Riwayat Booking
                </a>
            </div>



        </div>

          </div>
    </div>
</section>

<script>
    // kirim timestamp ISO dari server (format ISO 8601)
    const expiresAt = new Date("{{ $pemesanan->expired_at->toIso8601String() }}");

    const hoursEl = document.getElementById('hours');
    const minutesEl = document.getElementById('minutes');
    const secondsEl = document.getElementById('seconds');

    function updateCountdown() {
        const now = new Date();
        let diff = Math.floor((expiresAt - now) / 1000); // detik tersisa

        if (diff < 0) diff = 0;

        const h = Math.floor(diff / 3600);
        const m = Math.floor((diff % 3600) / 60);
        const s = diff % 60;

        hoursEl.textContent = String(h).padStart(2, '0');
        minutesEl.textContent = String(m).padStart(2, '0');
        secondsEl.textContent = String(s).padStart(2, '0');

        if (diff > 0) {
            setTimeout(updateCountdown, 1000);
        } else {
            alert('Waktu pembayaran habis! Booking dibatalkan.');
            window.location.href = "{{ route('pelanggan.jadwal') }}";
        }
    }

    updateCountdown();
</script>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    function payNow(kode) {
        fetch("{{ route('pelanggan.payment.create_snap') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    kode: kode
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.snap_token) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal memulai pembayaran'
                    });
                    return;
                }

                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Pembayaran berhasil!'
                        }).then(() => {
                            window.location.href = "{{ route('pelanggan.booking.history') }}";
                        });
                    },
                    onPending: function(result) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Menunggu Pembayaran',
                            text: 'Pembayaran pending, silakan selesaikan pembayaran.'
                        }).then(() => {
                            window.location.href = "{{ route('pelanggan.booking.history') }}";
                        });
                    },
                    onError: function(result) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Pembayaran gagal!'
                        });
                    },
                    onClose: function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Dibatalkan',
                            text: 'Popup pembayaran ditutup'
                        });
                    }
                });
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan server'
                });
            });
    }
</script>



@endsection