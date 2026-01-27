@extends('layouts.pelanggan')


@section('title', 'Lapangan | Futsal ACR')

@section('content')
<!-- Page Header -->
<section class="page-header" style="background: var(--gradient-dark); padding: 140px 0 60px;">
    <div class="container text-center">
        <h1 style="color: var(--color-white); margin-bottom: var(--space-md);">Daftar Lapangan</h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">
            Pilih lapangan favorit Anda dari berbagai pilihan yang tersedia dengan fasilitas premium
        </p>
    </div>
</section>

<!-- Filter Section -->
<!-- <section style="padding: var(--space-xl) 0; background: var(--color-gray-100);">
    <div class="container">
        <div class="flex justify-between items-center" style="flex-wrap: wrap; gap: var(--space-md);">
            <div class="flex gap-md" style="flex-wrap: wrap;">
                <select class="form-control form-select" style="width: auto; min-width: 150px;">
                    <option value="">Semua Tipe</option>
                    <option value="vinyl">Vinyl</option>
                    <option value="rumput">Rumput Sintetis</option>
                    <option value="parquet">Parquet</option>
                </select>
                <select class="form-control form-select" style="width: auto; min-width: 150px;">
                    <option value="">Semua Status</option>
                    <option value="available">Tersedia</option>
                    <option value="limited">Hampir Penuh</option>
                </select>
            </div>
            <div class="flex gap-md items-center">
                <span style="color: var(--color-gray-600);">Urutkan:</span>
                <select class="form-control form-select" style="width: auto; min-width: 150px;">
                    <option value="popular">Paling Populer</option>
                    <option value="price-low">Harga Terendah</option>
                    <option value="price-high">Harga Tertinggi</option>
                    <option value="rating">Rating Tertinggi</option>
                </select>
            </div>
        </div>
    </div>
</section> -->

<!-- Fields List -->
<section class="section">
    <div class="container">
        <div class="grid grid-3">
            @forelse ($lapanganList as $lapangan)
            @php
            $hargaTermurah = optional($lapangan->jamOperasional->first())->harga;
            @endphp

            <div
                class="card field-card animate-fadeInUp"
                style="display:flex;flex-direction:column;height:100%;">
                <div class="card-image">
                    @php
                    // DEFAULT IMAGE (UNSPLASH)
                    $defaultImage = 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=600&q=80';

                    $imageSrc = $lapangan->image
                    ? asset('storage/' . $lapangan->image)
                    : $defaultImage;
                    @endphp

                    <img
                        src="{{ $imageSrc }}"
                        alt="{{ $lapangan->nama_lapangan }}">


                    @if ($lapangan->status === 'aktif')
                    <span class="field-badge badge badge-success">
                        <i class="fas fa-check-circle"></i> Tersedia
                    </span>
                    @else
                    <span class="field-badge badge badge-warning">
                        <i class="fas fa-clock"></i> Hampir Penuh
                    </span>
                    @endif
                </div>

                <div class="card-body" style="display:flex;flex-direction:column;flex:1;">
                    <h3 class="card-title">
                        {{ $lapangan->nama_lapangan }}
                    </h3>

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

                    <p class="card-text">
                        {{ $lapangan->deskripsi }}
                    </p>

                    <div
                        class="field-footer"
                        style="margin-top:auto;display:flex;justify-content:space-between;align-items:center;">
                        <div class="field-price">
                            @if ($hargaTermurah)
                            Rp {{ number_format($hargaTermurah, 0, ',', '.') }}
                            <span>/jam</span>
                            @else
                            <span>Harga belum tersedia</span>
                            @endif
                        </div>

                        <a
                            href="{{ route('pelanggan.jadwal') }}"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-calendar-check me-1"></i>
                            Booking
                        </a>

                    </div>
                </div>
            </div>
            @empty
            <p style="grid-column: span 3; text-align: center;">
                Tidak ada lapangan tersedia
            </p>
            @endforelse
        </div>

    </div>
</section>
@endsection