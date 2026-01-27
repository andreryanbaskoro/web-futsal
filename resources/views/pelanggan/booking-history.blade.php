@extends('layouts.pelanggan')

@section('title', 'Riwayat Pemesanan - Futsal ACR')


@section('content')
<!-- Page Header -->
<section style="background: linear-gradient(135deg,#0b1020 0%, #111827 100%); padding: 80px 0 40px;">
    <div class="container text-center text-white">
        <h1 style="color: #fff; margin-bottom: 8px;">Riwayat Pemesanan</h1>
        <p style="color: rgba(255,255,255,0.75); max-width:600px; margin:0 auto;">Lihat riwayat pemesanan Anda di sini. Pilih lapangan dan tanggal untuk melihat jadwal yang tersedia.</p>
    </div>
</section>


<section style="padding: 40px 0 40px; background: var(--color-gray-100); min-height: 100vh;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 280px 1fr; gap: var(--space-2xl);">
            <!-- User Sidebar -->
            <div>
                <div class="user-sidebar" style="background: var(--color-white); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); padding: var(--space-xl); position: sticky; top: 100px;">
                    <div class="user-avatar"
                        style="
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e5e7eb, #f3f4f6);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--space-md);
     ">
                        <i class="fas fa-user"
                            style="font-size: 36px; color: #9ca3af;"></i>
                    </div>

                    <!-- Tampilkan Nama Lengkap dan Email -->
                    <h3 class="user-name" style="text-align: center; font-weight: 600; margin-bottom: 4px;">{{ auth()->user()->nama }}</h3>
                    <p class="user-email" style="text-align: center; font-size: var(--text-sm); color: var(--color-gray-600); margin-bottom: var(--space-lg);">{{ auth()->user()->email }}</p>

                    <!-- Menu Sidebar -->
                    <div class="user-menu" style="border-top: 1px solid var(--color-gray-200); padding-top: var(--space-lg);">
                        <a href="{{ route('pelanggan.booking.history') }}" class="active" style="display: flex; align-items: center; gap: var(--space-md); padding: var(--space-md); border-radius: var(--radius-md); color: var(--color-gray-700); transition: var(--transition-base); background: rgba(29, 185, 84, 0.1); color: var(--color-primary);">
                            <i class="fas fa-history" style="width: 20px; text-align: center;"></i> Riwayat Booking
                        </a>
                        <a href="{{ route('pelanggan.profile.index') }}"
                            style="display: flex; align-items: center; gap: var(--space-md); padding: var(--space-md); border-radius: var(--radius-md); color: var(--color-gray-700); transition: var(--transition-base);">
                            <i class="fas fa-user" style="width: 20px; text-align: center;"></i>
                            Profil Saya
                        </a>

                    </div>
                </div>
            </div>


            <!-- Main Content -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl); flex-wrap: wrap; gap: var(--space-md);">
                    <h2>Riwayat Pemesanan</h2>
                    <!-- <div style="display: flex; gap: var(--space-md);">
                        <select class="form-control form-select" style="width: auto;">
                            <option value="">Semua Status</option>
                            <option value="paid">Lunas</option>
                            <option value="pending">Pending</option>
                            <option value="expired">Kadaluarsa</option>
                        </select>
                        <select class="form-control form-select" style="width: auto;">
                            <option value="">Bulan Ini</option>
                            <option value="last">Bulan Lalu</option>
                            <option value="all">Semua</option>
                        </select>
                    </div> -->
                </div>

                <!-- Booking - Paid -->
                @if($bookings->isEmpty())
                <div class="empty-booking" style="text-align: center; padding: var(--space-lg); color: var(--color-gray-600);">
                    <i class="fas fa-exclamation-circle" style="font-size: 2em;"></i>
                    <h3 style="margin-top: var(--space-md); font-weight: 600;">Tidak ada Riwayat Pemesanan</h3>
                    <p>Anda belum melakukan pemesanan apapun. Ayo lakukan pemesanan sekarang!</p>
                    <a href="{{ route('pelanggan.jadwal') }}" class="btn btn-primary" style="background: var(--color-primary); color: var(--color-white); margin-top: var(--space-md);">
                        Lihat Jadwal
                    </a>
                </div>
                @else

                @foreach($bookings as $pemesanan)
                @php
                // Ambil detail jadwal (collection)
                $details = $pemesanan->detailJadwal;

                // Detail pertama
                $firstDetail = $details->first();

                // Ambil lapangan (aman dari null)
                $lapangan = $firstDetail?->jadwal?->lapangan
                ?? $pemesanan->lapangan
                ?? null;

                // Tanggal booking
                $tanggal = $firstDetail?->tanggal;

                // Total durasi (menit)
                $totalDurasi = $details->sum('durasi_menit') ?: 0;

                // Jam list
                $jamList = [];
                foreach ($details as $d) {
                $mulai = substr($d->jadwal->jam_mulai ?? $d->jam_mulai, 0, 5);
                $selesai = substr($d->jadwal->jam_selesai ?? $d->jam_selesai, 0, 5);
                $jamList[] = $mulai . ' - ' . $selesai;
                }

                // Rating user per PEMESANAN
                $userRating = $pemesanan->ulasan ?? null;
                @endphp




                <div class="booking-card" style="background: var(--color-white); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; margin-bottom: var(--space-lg);">
                    <div class="booking-card-header" style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-lg); border-bottom: 1px solid var(--color-gray-200);">
                        <span class="booking-code" style="font-family: var(--font-primary); font-weight: 600;">#{{ $pemesanan->kode_pemesanan }}</span>
                        <span class="badge-status {{ $pemesanan->status_pemesanan }}">
                            @switch($pemesanan->status_pemesanan)
                            @case('dibayar')
                            <i class="fas fa-check-circle"></i>
                            @break
                            @case('pending')
                            <i class="fas fa-clock"></i>
                            @break
                            @case('dibatalkan')
                            <i class="fas fa-times-circle"></i>
                            @break
                            @case('kadaluarsa')
                            <i class="fas fa-hourglass-end"></i>
                            @break
                            @endswitch

                            {{ ucfirst($pemesanan->status_pemesanan) }}
                        </span>
                    </div>


                    <div class="booking-card-body"
                        style="padding: var(--space-lg);
            display: grid;
            grid-template-columns: 120px 1fr 200px;
            gap: var(--space-lg);
            align-items: flex-start;">

                        {{-- LEFT : IMAGE --}}
                        <div>
                            @if($lapangan)
                            <img src="{{ $lapangan->image_url }}"
                                alt="Lapangan {{ $lapangan->nama_lapangan }}"
                                style="width:120px;height:90px;
                        border-radius:var(--radius-md);
                        object-fit:cover;">
                            @else
                            <div style="width:120px;height:90px;
                        background:#e5e7eb;
                        border-radius:var(--radius-md);"></div>
                            @endif
                        </div>

                        {{-- CENTER : DETAILS --}}
                        <div class="booking-details">

                            {{-- Nama Lapangan --}}
                            <h4 style="margin-bottom:4px;">
                                {{ $lapangan->nama_lapangan ?? 'Lapangan tidak tersedia' }}
                                <span style="font-size:12px;color:#6b7280;">
                                    ({{ $lapangan->dimensi ?? '-' }}, {{ $lapangan->kapasitas ?? '-' }})
                                </span>
                            </h4>

                            {{-- ⭐ Rating Global --}}
                            @if($lapangan)
                            <div
                                id="avg-rating-{{ $lapangan->id_lapangan }}"
                                class="avg-rating lapangan-{{ $lapangan->id_lapangan }}"
                                data-lapangan-id="{{ $lapangan->id_lapangan }}"
                                style="font-size:14px; color:#6b7280; margin-bottom:4px;">
                                ⭐ {{ number_format($lapangan->rating ?? 0, 1) }}
                                ({{ $lapangan->rating_count ?? 0 }} ulasan)
                            </div>
                            @else
                            <div style="font-size:14px; color:#6b7280; margin-bottom:4px;">-</div>
                            @endif


                            {{-- ⭐ Rating User untuk PEMESANAN (container untuk di-update via JS) --}}
                            <div id="user-rating-{{ $pemesanan->kode_pemesanan }}" style="font-size:13px; margin-bottom:6px;">
                                @if($pemesanan->ulasan)
                                Rating Anda:
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="color: {{ $i <= $pemesanan->ulasan->rating ? '#facc15' : '#e5e7eb' }}"></i>
                                    @endfor

                                    @if($pemesanan->ulasan->komentar)
                                    <div style="color:#6b7280; font-size:13px; margin-top:6px;">
                                        "{{ e($pemesanan->ulasan->komentar) }}"
                                    </div>
                                    @endif
                                    @else
                                    {{-- kosong kalau belum ada ulasan; JS akan mengisi setelah submit --}}
                                    @endif
                            </div>




                            {{-- Meta --}}
                            <div style="display:flex;
                    flex-wrap:wrap;
                    gap:12px;
                    font-size:13px;
                    color:#6b7280;
                    margin-bottom:6px;">
                                <span><i class="fas fa-calendar"></i>
                                    {{ $tanggal ? \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') : '-' }}
                                </span>
                                <span><i class="fas fa-clock"></i> {{ implode(', ', $jamList) }}</span>
                                <span><i class="fas fa-stopwatch"></i> {{ $totalDurasi }} Menit</span>
                            </div>

                            {{-- Harga --}}
                            <div style="font-weight:600;
                    font-size:15px;
                    color:var(--color-primary);">
                                Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
                            </div>
                        </div>

                        {{-- RIGHT : ACTIONS --}}
                        <div class="booking-actions"
                            style="display:flex;
    flex-direction:column;
    gap:8px;
    align-items:stretch;">

                            @if($pemesanan->status_pemesanan === 'dibayar')
                            @if(!$pemesanan->ulasan)
                            {{-- BELUM DINILAI --}}
                            <button
                                id="btn-rating-{{ $pemesanan->kode_pemesanan }}"
                                class="btn btn-accent btn-sm"
                                onclick="showRatingModal(
        '{{ $pemesanan->kode_pemesanan }}',
        {{ $lapangan->id_lapangan }}
    )">
                                ⭐ Berikan Rating
                            </button>


                            @else
                            <span class="badge badge-success badge-rated">
                                ⭐ Sudah Dinilai
                            </span>


                            @endif
                            @endif

                            <a href="{{ route('pelanggan.booking.history.detail', $pemesanan->kode_pemesanan) }}"
                                class="btn btn-outline btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Detail
                            </a>

                            <a href="{{ route('pelanggan.jadwal') }}"
                                class="btn btn-primary btn-sm">
                                Booking Lagi
                            </a>

                            @if($pemesanan->status_pemesanan === 'pending')
                            <a href="{{ route('pelanggan.booking.confirm', $pemesanan->kode_pemesanan) }}"
                                class="btn btn-primary-blue btn-sm">
                                Konfirmasi
                            </a>
                            @endif
                        </div>


                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
</section>

<script>
    function renderStars(rating) {
        let out = '';
        for (let i = 1; i <= 5; i++) {
            out += `<i class="fas fa-star"
                style="color:${i <= rating ? '#facc15' : '#e5e7eb'}; margin-right:2px;"></i>`;
        }
        return out;
    }

    function showRatingModal(pemesananKode, lapanganId, existingRating = 0, existingReview = '') {
        Swal.fire({
            title: existingRating ? 'Ubah Rating Anda' : 'Berikan Rating',
            html: `
                <div style="display:flex; justify-content:center; gap:10px; margin-bottom:12px;">
                    ${[1,2,3,4,5].map(i => `
                        <img class="star" data-value="${i}"
                             src="${i <= existingRating ? 'https://img.icons8.com/ios-filled/50/ffcc00/star.png' : 'https://img.icons8.com/ios/50/d3d3d3/star.png'}"
                             style="cursor:pointer; width:32px;">
                    `).join('')}
                </div>
                <input type="hidden" id="rating" value="${existingRating || 0}">
                <textarea id="review" class="form-control" rows="3"
                    placeholder="Tulis ulasan (opsional)...">${existingReview ? existingReview : ''}</textarea>
            `,
            showCancelButton: true,
            confirmButtonText: existingRating ? 'Simpan Perubahan' : 'Kirim',
            cancelButtonText: 'Batal',

            didOpen: () => {
                const confirmBtn = Swal.getConfirmButton();
                // disable confirm kalau belum ada rating (kecuali sudah ada existingRating)
                if (!existingRating) {
                    confirmBtn.setAttribute('disabled', 'disabled');
                }

                const stars = document.querySelectorAll('.star');
                stars.forEach(star => {
                    star.onclick = () => {
                        const val = parseInt(star.dataset.value);
                        document.getElementById('rating').value = val;

                        stars.forEach(s => {
                            s.src = parseInt(s.dataset.value) <= val ?
                                'https://img.icons8.com/ios-filled/50/ffcc00/star.png' :
                                'https://img.icons8.com/ios/50/d3d3d3/star.png';
                        });

                        confirmBtn.removeAttribute('disabled');
                    };
                });
            },

            preConfirm: () => {
                const rating = parseInt(document.getElementById('rating').value || 0);
                if (!rating) {
                    Swal.showValidationMessage('Pilih rating terlebih dahulu');
                    return false;
                }
                return {
                    rating,
                    review: document.getElementById('review').value || ''
                };
            }
        }).then(result => {
            if (!result.isConfirmed) return;

            const {
                rating,
                review
            } = result.value;

            fetch(`/pelanggan/booking/rating/${encodeURIComponent(pemesananKode)}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        rating,
                        review
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        Swal.fire('Gagal', data.message || 'Gagal menyimpan ulasan', 'error');
                        return;
                    }

                    Swal.fire('Berhasil', data.message, 'success');

                    // update rata-rata global (SEMUA card lapangan ini)
                    if (data.avg_rating !== undefined) {
                        document.querySelectorAll('.lapangan-' + lapanganId).forEach(el => {
                            el.innerHTML = `⭐ ${parseFloat(data.avg_rating).toFixed(1)} (${data.rating_count} ulasan)`;
                        });
                    }

                    // jika ada tombol rating kecil / besar, ganti sesuai keadaan
                    const btn = document.getElementById('btn-rating-' + pemesananKode);
                    if (btn) {
                        // jika awalnya ada tombol beri rating, ubah jadi badge + ubah tombol kecil
                        btn.outerHTML = `<span class="badge badge-success badge-rated"><i class="fas fa-star"></i> Sudah Dinilai</span>`;
                    }

                    // Update user rating area (jika ada)
                    const userBox = document.querySelector('#user-rating-' + pemesananKode);
                    if (userBox) {
                        userBox.innerHTML = `Rating Anda: ${renderStars(data.user_rating.rating)}`;
                        if (data.user_rating.komentar) {
                            userBox.innerHTML += `<div style="color:#6b7280; font-size:13px; margin-top:6px;">"${data.user_rating.komentar}"</div>`;
                        }
                    } else {
                        // jika userBox tidak ada (mis. halaman berbeda), insert small badge near actions apabila perlu
                    }
                })
                .catch(err => {
                    Swal.fire('Error', err.message || 'Terjadi kesalahan', 'error');
                });
        });
    }
</script>




@if (session('success') || session('error') || session('info'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: @json(session('success')),
            confirmButtonColor: '#1db954'
        });
        @endif

        @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: @json(session('info')),
            confirmButtonColor: '#3b82f6'
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: @json(session('error')),
            confirmButtonColor: '#ef4444'
        });
        @endif
    });
</script>
@endif

@endsection