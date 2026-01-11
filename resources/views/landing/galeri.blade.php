@extends('layouts.app')

@section('title', 'Booking Lapangan Futsal Online')

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
            <div class="masonry-item" data-category="lapangan">
                <img src="https://images.unsplash.com/photo-1552667466-07770ae110d0?w=600&q=80"
                    alt="Lapangan Utama">
                <div class="masonry-overlay">
                    <h4>Lapangan Utama</h4>
                    <p>Rumput sintetis premium</p>
                </div>
            </div>
            <div class="masonry-item" data-category="aktivitas">
                <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=600&q=80"
                    alt="Pertandingan">
                <div class="masonry-overlay">
                    <h4>Pertandingan Seru</h4>
                    <p>Komunitas futsal bermain</p>
                </div>
            </div>
            <div class="masonry-item" data-category="fasilitas">
                <img src="https://images.unsplash.com/photo-1606925797300-0b35e9d1794e?w=600&q=80"
                    alt="Ruang Ganti">
                <div class="masonry-overlay">
                    <h4>Ruang Ganti</h4>
                    <p>Bersih dan nyaman</p>
                </div>
            </div>
            <div class="masonry-item" data-category="lapangan">
                <img src="https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=600&q=80" alt="Lapangan A">
                <div class="masonry-overlay">
                    <h4>Lapangan A</h4>
                    <p>Vinyl premium</p>
                </div>
            </div>
            <div class="masonry-item" data-category="event">
                <img src="https://images.unsplash.com/photo-1551958219-acbc608c6377?w=600&q=80" alt="Tournament">
                <div class="masonry-overlay">
                    <h4>Turnamen</h4>
                    <p>Event bulanan</p>
                </div>
            </div>
            <div class="masonry-item" data-category="lapangan">
                <img src="https://images.unsplash.com/photo-1624880357913-a8539238245b?w=600&q=80" alt="Lapangan B">
                <div class="masonry-overlay">
                    <h4>Lapangan B</h4>
                    <p>Rumput sintetis standar FIFA</p>
                </div>
            </div>
            <div class="masonry-item" data-category="fasilitas">
                <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600&q=80" alt="Gym Area">
                <div class="masonry-overlay">
                    <h4>Area Pemanasan</h4>
                    <p>Fasilitas gym mini</p>
                </div>
            </div>
            <div class="masonry-item" data-category="aktivitas">
                <img src="https://images.unsplash.com/photo-1517466787929-bc90951d0974?w=600&q=80" alt="Training">
                <div class="masonry-overlay">
                    <h4>Latihan Tim</h4>
                    <p>Sesi training rutin</p>
                </div>
            </div>
            <div class="masonry-item" data-category="lapangan">
                <img src="https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=600&q=80" alt="Lapangan C">
                <div class="masonry-overlay">
                    <h4>Lapangan C</h4>
                    <p>Parquet indoor</p>
                </div>
            </div>
            <div class="masonry-item" data-category="fasilitas">
                <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80" alt="Cafe Area">
                <div class="masonry-overlay">
                    <h4>Cafe & Resto</h4>
                    <p>Santai setelah bermain</p>
                </div>
            </div>
            <div class="masonry-item" data-category="event">
                <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=600&q=80" alt="Award">
                <div class="masonry-overlay">
                    <h4>Penyerahan Piala</h4>
                    <p>Juara turnamen 2025</p>
                </div>
            </div>
            <div class="masonry-item" data-category="aktivitas">
                <img src="https://images.unsplash.com/photo-1599058917212-d750089bc07e?w=600&q=80" alt="Pemanasan">
                <div class="masonry-overlay">
                    <h4>Sesi Pemanasan</h4>
                    <p>Persiapan sebelum main</p>
                </div>
            </div>
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