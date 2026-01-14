@extends('layouts.pelanggan')

@section('title', 'Booking Lapangan Futsal Online')

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
<section style="padding: var(--space-xl) 0; background: var(--color-gray-100);">
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
</section>

<!-- Fields List -->
<section class="section">
    <div class="container">
        <div class="grid grid-3">

            @forelse ($lapangans as $lapangan)
            <div class="card field-card">
                <div class="card-image">
                    <img src="{{ $lapangan->image ?? 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=600&q=80' }}"
                        alt="{{ $lapangan->nama_lapangan }}">

                    <span class="field-badge badge 
                    {{ $lapangan->status === 'aktif' ? 'badge-success' : 'badge-warning' }}">
                        <i class="fas 
                        {{ $lapangan->status === 'aktif' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                        {{ $lapangan->status === 'aktif' ? 'Tersedia' : 'Hampir Penuh' }}
                    </span>
                </div>

                <div class="card-body">
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
                            {{ $lapangan->kapasitas ?? '-' }}
                        </span>

                        <span class="field-rating">
                            <i class="fas fa-star"></i>
                            {{ number_format($lapangan->rating, 1) }}
                            <small>({{ $lapangan->rating_count }})</small>
                        </span>

                    </div>

                    <p class="card-text">
                        {{ $lapangan->deskripsi ?? 'Deskripsi belum tersedia.' }}
                    </p>

                    <div class="field-footer">
                        <div class="field-price">
                            Rp {{ number_format($lapangan->harga, 0, ',', '.') }}
                            <span>/{{ $lapangan->interval_menit }} menit</span>
                        </div>

                        <a href="#"
                            class="btn btn-primary btn-sm">
                            Booking
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <p style="grid-column: 1 / -1; text-align:center;">
                Lapangan belum tersedia.
            </p>
            @endforelse

        </div>

    </div>
</section>
@endsection