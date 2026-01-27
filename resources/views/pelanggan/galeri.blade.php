@extends('layouts.pelanggan')


@section('title', 'Galeri | Futsal ACR')

@push('styles')
<style>
    .gallery-filter {
        display: flex;
        justify-content: center;
        gap: var(--space-md);
        margin-bottom: var(--space-2xl);
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-full);
        font-weight: 500;
        transition: var(--transition-base);
        background: var(--color-white);
        border: 2px solid var(--color-gray-300);
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: var(--color-primary);
        color: var(--color-white);
        border-color: var(--color-primary);
    }

    .masonry-grid {
        columns: 4;
        column-gap: var(--space-md);
    }

    .masonry-item {
        break-inside: avoid;
        margin-bottom: var(--space-md);
        position: relative;
        border-radius: var(--radius-lg);
        overflow: hidden;
        cursor: pointer;
    }

    .masonry-item img {
        width: 100%;
        display: block;
        transition: transform var(--transition-slow);
    }

    .masonry-item:hover img {
        transform: scale(1.05);
    }

    .masonry-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top,
                rgba(0, 0, 0, 0.7) 0%,
                transparent 60%);
        opacity: 0;
        transition: opacity var(--transition-base);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: var(--space-lg);
    }

    .masonry-item:hover .masonry-overlay {
        opacity: 1;
    }

    .masonry-overlay h4 {
        color: var(--color-white);
        margin-bottom: 4px;
    }

    .masonry-overlay p {
        color: rgba(255, 255, 255, 0.8);
        font-size: var(--text-sm);
    }

    @media (max-width: 1023px) {
        .masonry-grid {
            columns: 3;
        }
    }

    @media (max-width: 767px) {
        .masonry-grid {
            columns: 2;
        }
    }

    @media (max-width: 480px) {
        .masonry-grid {
            columns: 1;
        }
    }
</style>
@endpush


@section('content')
<!-- Page Header -->
<section style="background: var(--gradient-dark); padding: 140px 0 60px;">
    <div class="container text-center">
        <h1 style="color: var(--color-white); margin-bottom: var(--space-md);">Galeri Foto</h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">Lihat suasana lapangan dan
            fasilitas kami</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="gallery-filter">
            <button class="filter-btn active" data-filter="all">Semua</button>
            <button class="filter-btn" data-filter="lapangan">Lapangan</button>
            <button class="filter-btn" data-filter="fasilitas">Fasilitas</button>
            <button class="filter-btn" data-filter="aktivitas">Aktivitas</button>
            <button class="filter-btn" data-filter="event">Event</button>
        </div>

        <div class="masonry-grid">
            @forelse ($galleries as $gallery)
            <div class="masonry-item" data-category="{{ $gallery->category }}">
                <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}">

                <div class="masonry-overlay">
                    <h4>{{ $gallery->title }}</h4>
                    <p>{{ $gallery->description }}</p>
                </div>
            </div>
            @empty
            <p style="text-align:center; width:100%;">
                Belum ada foto di galeri.
            </p>
            @endforelse
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.filter-btn');
        const items = document.querySelectorAll('.masonry-item');

        buttons.forEach(btn => {
            btn.addEventListener('click', function() {
                buttons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;

                items.forEach(item => {
                    if (filter === 'all' || item.dataset.category === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
@endpush