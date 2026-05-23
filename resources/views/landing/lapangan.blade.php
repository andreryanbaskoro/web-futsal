@extends('layouts.app')

@section('title', 'Lapangan | Futsal ACR')

@section('content')

<!-- Page Header -->
<section class="page-header" style="background: var(--gradient-dark); padding: 140px 0 60px;">
    <div class="container text-center">
        <h1 style="color: var(--color-white); margin-bottom: var(--space-md);">
            Daftar Lapangan
        </h1>

        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">
            Pilih lapangan favorit Anda dari berbagai pilihan yang tersedia dengan fasilitas premium
        </p>
    </div>
</section>

<!-- Fields List -->
<section class="section">
    <div class="container">

        <div class="grid grid-3">

            @forelse ($lapanganList as $lapangan)

            @php
            $hargaTermurah = optional($lapangan->jamOperasional->first())->harga;

            $isAvailable = $lapangan->status === 'aktif';

            // DEFAULT IMAGE
            $defaultImage = 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=600&q=80';

            if ($lapangan->image_type === 'upload' && !empty($lapangan->image)) {

            $imageSrc = asset('storage/' . $lapangan->image);

            } elseif ($lapangan->image_type === 'url' && !empty($lapangan->image)) {

            $imageSrc = $lapangan->image;

            } else {

            $imageSrc = $defaultImage;
            }

            // STATUS BADGE
            $status = strtolower($lapangan->status);

            $badge = match($status) {

            'aktif' => [
            'bg' => '#16a34a',
            'icon' => 'fa-check-circle',
            'text' => 'Tersedia'
            ],

            'maintenance' => [
            'bg' => '#f59e0b',
            'icon' => 'fa-tools',
            'text' => 'Maintenance'
            ],

            'perbaikan' => [
            'bg' => '#dc2626',
            'icon' => 'fa-wrench',
            'text' => 'Perbaikan'
            ],

            'event' => [
            'bg' => '#2563eb',
            'icon' => 'fa-calendar',
            'text' => 'Dipakai Event'
            ],

            default => [
            'bg' => '#6b7280',
            'icon' => 'fa-info-circle',
            'text' => ucfirst($status)
            ]
            };
            @endphp

            <!-- CARD -->
            <div
                class="card field-card animate-fadeInUp"
                style="
                    display:flex;
                    flex-direction:column;
                    height:100%;
                    transition:.3s;
                    overflow:hidden;
                    {{ !$isAvailable ? 'opacity:.75; filter:grayscale(.2);' : '' }}
                ">

                <!-- IMAGE -->
                <div
                    class="card-image"
                    style="position:relative;">

                    <img
                        src="{{ $imageSrc }}"
                        alt="{{ $lapangan->nama_lapangan }}">

                    {{-- Overlay jika tidak tersedia --}}
                    @if (!$isAvailable)
                    <div
                        style="
                            position:absolute;
                            inset:0;
                            background:rgba(0,0,0,.35);
                        ">
                    </div>
                    @endif

                    <!-- STATUS BADGE -->
                    <span
                        class="field-badge"
                        style="
                            position:absolute;
                            top:15px;
                            right:15px;
                            z-index:2;
                            background: {{ $badge['bg'] }};
                            color:white;
                            padding:6px 12px;
                            border-radius:999px;
                            font-size:12px;
                            font-weight:600;
                            display:inline-flex;
                            align-items:center;
                            gap:6px;
                            box-shadow:0 4px 10px rgba(0,0,0,.15);
                        ">

                        <i class="fas {{ $badge['icon'] }}"></i>
                        {{ $badge['text'] }}

                    </span>
                </div>

                <!-- BODY -->
                <div
                    class="card-body"
                    style="
                        display:flex;
                        flex-direction:column;
                        flex:1;
                    ">

                    <!-- TITLE -->
                    <h3 class="card-title">
                        {{ $lapangan->nama_lapangan }}
                    </h3>

                    <!-- INFO -->
                    <div class="field-info">

                        <span>
                            <i class="fas fa-ruler-combined"></i>
                            {{ $lapangan->dimensi ?? '-' }}
                        </span>

                        <span>
                            <i class="fas fa-users"></i>
                            {{ $lapangan->kapasitas }} orang
                        </span>

                        <span class="field-rating">
                            <i class="fas fa-star"></i>
                            {{ $lapangan->rating }} ({{ $lapangan->rating_count }})
                        </span>

                    </div>

                    <!-- DESCRIPTION -->
                    <p class="card-text">
                        {{ $lapangan->deskripsi }}
                    </p>

                    <!-- FOOTER -->
                    <div
                        class="field-footer"
                        style="
                            margin-top:auto;
                            display:flex;
                            justify-content:space-between;
                            align-items:center;
                            gap:10px;
                        ">

                        <!-- PRICE -->
                        <div class="field-price">

                            @if ($hargaTermurah)

                            Rp {{ number_format($hargaTermurah, 0, ',', '.') }}
                            <span>/jam</span>

                            @else

                            <span>Harga belum tersedia</span>

                            @endif

                        </div>

                        <!-- BUTTON -->
                        @if ($lapangan->status === 'aktif')

                        <a
                            href="{{ route('jadwal', ['id_lapangan' => $lapangan->id_lapangan]) }}"
                            class="btn btn-primary btn-sm">

                            <i class="fas fa-calendar-check me-1"></i>
                            Booking

                        </a>

                        @else

                        <button
                            class="btn btn-secondary btn-sm"
                            disabled
                            style="
                                cursor:not-allowed;
                                opacity:.8;
                            ">

                            <i class="fas fa-ban me-1"></i>
                            Tidak Tersedia

                        </button>

                        @endif

                    </div>
                </div>
            </div>

            @empty

            <p style="grid-column: span 3; text-align:center;">
                Tidak ada lapangan tersedia
            </p>

            @endforelse

        </div>
    </div>
</section>

@endsection