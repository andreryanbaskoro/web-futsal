@extends('layouts.pelanggan')

@section('title', 'Detail Riwayat Pemesanan')

@push('styles')
<style>
    @media (max-width: 640px) {
        .cta-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .cta-actions a {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')


{{-- ================= HEADER ================= --}}
<!-- Page Header -->
<section style="background: linear-gradient(135deg,#0b1020 0%, #111827 100%); padding: 80px 0 40px;">
    <div class="container text-center text-white">
        <h1 style="color: #fff; margin-bottom: 8px;">Detail Pemesanan</h1>
        <p style="color: rgba(255,255,255,0.75); max-width:600px; margin:0 auto;"> Kode Booking:
            <strong>#{{ $pemesanan->kode_pemesanan }}</strong>
        </p>
    </div>
</section>

{{-- ================= MAIN CONTENT ================= --}}

<section style="padding:60px 0; background:var(--color-gray-100); min-height:100vh;">
    <div class="container" style="max-width:1100px;">

        {{-- ================= INFO GRID ================= --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px;">

            {{-- INFO PEMESANAN --}}
            <div style="
    background:#fff;
    border-radius:20px;
    box-shadow:var(--shadow-card);
    padding:28px;
">
                <h3 style="margin-bottom:20px;">Informasi Pemesanan</h3>

                <div style="display:grid; gap:14px; font-size:14px;">

                    {{-- KODE BOOKING --}}
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="color:#6b7280;">Kode Booking</span>
                        <strong>#{{ $pemesanan->kode_pemesanan }}</strong>
                    </div>

                    {{-- TANGGAL --}}
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="color:#6b7280;">Tanggal Booking</span>
                        <strong>{{ $tanggal }}</strong>
                    </div>

                    {{-- WAKTU PESAN --}}
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="color:#6b7280;">Waktu Pesan</span>
                        <strong>{{ $pemesanan->created_at->format('d M Y H:i') }}</strong>
                    </div>

                    {{-- DIVIDER --}}
                    <div style="height:1px; background:#e5e7eb; margin:6px 0;"></div>

                    {{-- TOTAL --}}
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="color:#6b7280;">Total Bayar</span>
                        <strong style="
                font-size:18px;
                color:var(--color-primary);
            ">
                            Rp {{ number_format($pemesanan->total_bayar,0,',','.') }}
                        </strong>
                    </div>

                    {{-- STATUS --}}
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="color:#6b7280;">Status Pemesanan</span>
                        <span class="badge-status {{ $pemesanan->status_pemesanan }}">
                            {{ ucfirst($pemesanan->status_pemesanan) }}
                        </span>
                    </div>

                </div>
            </div>


            {{-- INFO LAPANGAN --}}
            <div style="
    background:#fff;
    border-radius:20px;
    box-shadow:var(--shadow-card);
    padding:28px;
">
                <h3 style="margin-bottom:20px;">Informasi Lapangan</h3>

                <div style="
        display:grid;
        grid-template-columns:220px 1fr;
        gap:24px;
        align-items:start;
    ">

                    {{-- IMAGE --}}
                    <div style="position:relative;">
                        <img src="{{ $lapangan->image_url }}"
                            alt="{{ $lapangan->nama_lapangan }}"
                            style="
                    width:100%;
                    height:160px;
                    object-fit:cover;
                    border-radius:16px;
                ">

                        {{-- STATUS BADGE --}}
                        <span style="
                position:absolute;
                top:12px;
                left:12px;
                padding:6px 12px;
                font-size:12px;
                border-radius:999px;
                background: {{ $lapangan->status === 'aktif' ? '#16a34a' : '#dc2626' }};
                color:#fff;
                font-weight:600;
            ">
                            {{ ucfirst($lapangan->status) }}
                        </span>
                    </div>

                    {{-- CONTENT --}}
                    <div>
                        {{-- TITLE --}}
                        <div style="margin-bottom:10px;">
                            <strong style="font-size:20px;">
                                {{ $lapangan->nama_lapangan }}
                            </strong>
                        </div>

                        {{-- RATING GLOBAL --}}
                        <div style="display:flex; align-items:center; gap:6px; margin-bottom:12px;">
                            <i class="fas fa-star" style="color:#facc15;"></i>

                            {{-- bungkus rating angka & count dengan class yang bisa di-update --}}
                            <strong class="lapangan-{{ $lapangan->id_lapangan }}">
                                {{ number_format($lapangan->rating ?? 0, 1) }}
                                <span class="lapangan-count-{{ $lapangan->id_lapangan }}" style="color:#6b7280; font-size:14px;">
                                    ({{ $lapangan->rating_count ?? 0 }} ulasan)
                                </span>
                            </strong>
                        </div>


                        @php
                        $userUlasan = $pemesanan->ulasan ?? null;
                        @endphp

                        @if($pemesanan->status_pemesanan === 'dibayar')
                        <div id="user-rating-{{ $pemesanan->kode_pemesanan }}" style="margin-bottom:12px;">
                            @if($userUlasan)
                            <div style="font-size:14px; color:#374151; margin-bottom:6px;">
                                <strong>Rating Anda:</strong>
                                {!! \Illuminate\Support\Str::of(
                                str_repeat('<i class="fas fa-star" style="color:#ffd700;"></i>', $userUlasan->rating) .
                                str_repeat('<i class="fas fa-star" style="color:#e5e7eb;"></i>', 5 - $userUlasan->rating)
                                )->toString() !!}
                                @if($userUlasan->komentar)
                                <div style="color:#6b7280; font-size:13px; margin-top:6px;">
                                    "{{ e($userUlasan->komentar) }}"
                                </div>
                                @endif
                            </div>

                            <div style="display:flex; gap:8px; align-items:center;">
                                <span class="badge badge-success badge-rated">
                                    <i class="fas fa-star" style="color:#ffd700;"></i> Sudah Dinilai
                                </span>

                                <button
                                    class="btn btn-outline btn-sm btn-ubah-rating flex items-center gap-1"
                                    data-kode="{{ $pemesanan->kode_pemesanan }}"
                                    data-lapangan="{{ $lapangan->id_lapangan }}"
                                    data-rating="{{ $userUlasan->rating ?? 0 }}"
                                    data-review="{{ e($userUlasan->komentar ?? '') }}">
                                    <i class="fas fa-pen"></i> Ubah Rating
                                </button>
                            </div>
                            @else
                            <button
                                id="btn-rating-{{ $pemesanan->kode_pemesanan }}"
                                class="btn btn-accent btn-sm"
                                onclick="showRatingModal(
                    '{{ $pemesanan->kode_pemesanan }}',
                    {{ $lapangan->id_lapangan }},
                    0,
                    '' 
                )">
                                ⭐ Berikan Rating
                            </button>
                            @endif
                        </div>
                        @endif




                        {{-- DESKRIPSI --}}
                        @if($lapangan->deskripsi)
                        <p style="
                font-size:14px;
                color:#4b5563;
                line-height:1.6;
                margin-bottom:16px;
            ">
                            {{ $lapangan->deskripsi }}
                        </p>
                        @endif

                        {{-- META --}}
                        <div style="
                display:flex;
                gap:16px;
                flex-wrap:wrap;
            ">
                            <div style="
                    background:#f9fafb;
                    padding:10px 14px;
                    border-radius:12px;
                    font-size:14px;
                    display:flex;
                    align-items:center;
                    gap:8px;
                ">
                                <i class="fas fa-ruler-combined" style="color:var(--color-primary);"></i>
                                <span>{{ $lapangan->dimensi ?? '-' }}</span>
                            </div>

                            <div style="
                    background:#f9fafb;
                    padding:10px 14px;
                    border-radius:12px;
                    font-size:14px;
                    display:flex;
                    align-items:center;
                    gap:8px;
                ">
                                <i class="fas fa-users" style="color:var(--color-primary);"></i>
                                <span>{{ $lapangan->kapasitas ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>

        {{-- ================= JADWAL ================= --}}
        <div style="background:#fff; border-radius:16px; box-shadow:var(--shadow-card); padding:24px; margin-bottom:24px;">
            <h3 style="margin-bottom:16px;">Jadwal Booking</h3>

            <div style="display:grid; gap:12px;">
                @foreach($mergedSlots as $slot)
                <div style="display:flex; justify-content:space-between; align-items:center;
                            padding:14px 18px; background:#f9fafb; border-radius:10px;">
                    <div>
                        <strong>{{ $slot['start'] }} - {{ $slot['end'] }}</strong>
                        <div style="font-size:13px; color:#6b7280;">
                            {{ $slot['durasi'] }} menit
                        </div>
                    </div>
                    <i class="fas fa-clock" style="color:var(--color-primary);"></i>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ================= CTA ================= --}}
        <div style="
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    margin-top:32px;
    flex-wrap:wrap;
">
            {{-- KIRI --}}
            <a href="{{ route('pelanggan.booking.history') }}"
                class="btn btn-outline btn-md">
                ← Kembali ke Riwayat
            </a>

            {{-- KANAN --}}
            <a href="{{ route('pelanggan.jadwal') }}"
                class="btn btn-primary">
                Booking Lagi
            </a>

            @if($pemesanan->status_pemesanan === 'pending')
            <a href="{{ route('pelanggan.booking.confirm', $pemesanan->kode_pemesanan) }}"
                class="btn btn-primary-blue">
                Konfirmasi
            </a>
            @endif
        </div>


    </div>
</section>

<script>
    function renderStars(rating) {
        let out = '';
        for (let i = 1; i <= 5; i++) {
            out += `<i class="fas fa-star" style="color:${i <= rating ? '#facc15' : '#e5e7eb'}; margin-right:2px;"></i>`;
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
        }).then(async result => {
            if (!result.isConfirmed) return;

            const {
                rating,
                review
            } = result.value;

            try {
                const res = await fetch(`/pelanggan/booking/rating/${encodeURIComponent(pemesananKode)}`, {
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
                });

                // jaga parsing agar tidak crash jika server mengembalikan non-json
                const text = await res.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    data = null;
                }

                if (!res.ok || !data || !data.success) {
                    const msg = data && data.message ? data.message : (text || 'Gagal menyimpan ulasan');
                    Swal.fire('Gagal', msg, 'error');
                    return;
                }

                Swal.fire('Berhasil', data.message || 'Rating terkirim', 'success');

                // update rata-rata global
                if (data.avg_rating !== undefined) {
                    document.querySelectorAll('.lapangan-' + lapanganId).forEach(el => {
                        // jika Anda menyimpan angka dan count di class berbeda
                        el.innerHTML = parseFloat(data.avg_rating).toFixed(1) + ' ';
                    });
                    document.querySelectorAll('.lapangan-count-' + lapanganId).forEach(el => {
                        el.innerHTML = `(${data.rating_count} ulasan)`;
                    });
                }

                // ganti tombol beri rating kecil menjadi badge + tombol ubah (agar user bisa edit lagi)
                const btn = document.getElementById('btn-rating-' + pemesananKode);
                if (btn) {
                    btn.outerHTML = `
                    <span class="badge badge-success badge-rated" id="btn-rated-${pemesananKode}">
                        <i class="fas fa-star"></i> Sudah Dinilai
                    </span>
                    <button class="btn btn-outline btn-sm" onclick="showRatingModal('${pemesananKode}', ${lapanganId}, ${rating}, ${JSON.stringify(review)})">Ubah Rating</button>
                `;
                } else {
                    // jika sudah ada badge, pastikan ada tombol ubah juga
                    const rated = document.getElementById('btn-rated-' + pemesananKode);
                    if (!rated) {
                        // insert after user-rating box
                        const userBox = document.querySelector('#user-rating-' + pemesananKode);
                        if (userBox) {
                            userBox.insertAdjacentHTML('beforeend', `
                            <div style="margin-top:8px;">
                                <span class="badge badge-success badge-rated"><i class="fas fa-star"></i> Sudah Dinilai</span>
                                <button class="btn btn-outline btn-sm" onclick="showRatingModal('${pemesananKode}', ${lapanganId}, ${rating}, ${JSON.stringify(review)})">Ubah Rating</button>
                            </div>
                        `);
                        }
                    }
                }

                // Update user rating area
                // Update user rating area
                const userBox = document.querySelector('#user-rating-' + pemesananKode);
                if (userBox && data.user_rating) {
                    userBox.innerHTML = `
        <div style="font-size:14px; color:#374151; margin-bottom:6px;">
            <strong>Rating Anda:</strong>
            ${renderStars(data.user_rating.rating)}
            ${data.user_rating.komentar ? `<div style="color:#6b7280; font-size:13px; margin-top:6px;">"${data.user_rating.komentar}"</div>` : ''}
        </div>
        <div style="display:flex; gap:8px; align-items:center;">
            <span class="badge badge-success badge-rated">
                <i class="fas fa-star" style="color:#ffd700;"></i> Sudah Dinilai
            </span>
            <button class="btn btn-outline btn-sm btn-ubah-rating flex items-center gap-1"
                data-kode="${pemesananKode}"
                data-lapangan="${lapanganId}"
                data-rating="${data.user_rating.rating}"
                data-review="${data.user_rating.komentar || ''}">
                <i class="fas fa-pen"></i> Ubah Rating
            </button>
        </div>
    `;
                }

            } catch (err) {
                Swal.fire('Error', err.message || 'Terjadi kesalahan', 'error');
            }
        });
    }

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-ubah-rating');
        if (!btn) return;

        const kode = btn.dataset.kode;
        const lapanganId = parseInt(btn.dataset.lapangan, 10);
        const rating = parseInt(btn.dataset.rating || 0, 10);
        const review = btn.dataset.review || '';

        showRatingModal(kode, lapanganId, rating, review);
    });
</script>


@endsection