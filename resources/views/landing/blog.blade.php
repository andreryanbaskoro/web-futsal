@extends('layouts.app')

@section('title', 'Booking Lapangan Futsal Online')

@section('content')
<section style="background: var(--gradient-dark); padding: 140px 0 60px;">
    <div class="container text-center">
        <h1 style="color: var(--color-white); margin-bottom: var(--space-md);">Blog & Artikel</h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">Tips, trik, dan informasi seputar
            futsal untuk Anda</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: var(--space-2xl);">
            <!-- Blog Grid -->
            <div>
                <div class="grid grid-2" style="margin-bottom: var(--space-2xl);">
                    <article class="card blog-card">
                        <div class="card-image"><img
                                src="https://images.unsplash.com/photo-1599058917212-d750089bc07e?w=600&q=80"
                                alt="Tips Pemanasan"></div>
                        <div class="card-body">
                            <div class="blog-meta">
                                <span><i class="fas fa-calendar"></i> 10 Jan 2026</span>
                                <span><i class="fas fa-tag"></i> Tips</span>
                            </div>
                            <h3 class="blog-title">5 Tips Pemanasan Sebelum Main Futsal</h3>
                            <p class="blog-excerpt">Pemanasan yang benar sangat penting untuk mencegah cedera saat
                                bermain futsal...</p>
                            <a href="blog-detail.html" class="blog-link">Baca Selengkapnya <i
                                    class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>
                    <article class="card blog-card">
                        <div class="card-image"><img
                                src="https://images.unsplash.com/photo-1511886929837-354d827aae26?w=600&q=80"
                                alt="Sepatu Futsal"></div>
                        <div class="card-body">
                            <div class="blog-meta">
                                <span><i class="fas fa-calendar"></i> 08 Jan 2026</span>
                                <span><i class="fas fa-tag"></i> Gear</span>
                            </div>
                            <h3 class="blog-title">Cara Memilih Sepatu Futsal yang Tepat</h3>
                            <p class="blog-excerpt">Sepatu futsal yang tepat dapat meningkatkan performa permainan
                                Anda...</p>
                            <a href="blog-detail.html" class="blog-link">Baca Selengkapnya <i
                                    class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>
                    <article class="card blog-card">
                        <div class="card-image"><img
                                src="https://images.unsplash.com/photo-1517466787929-bc90951d0974?w=600&q=80"
                                alt="Strategi"></div>
                        <div class="card-body">
                            <div class="blog-meta">
                                <span><i class="fas fa-calendar"></i> 05 Jan 2026</span>
                                <span><i class="fas fa-tag"></i> Strategi</span>
                            </div>
                            <h3 class="blog-title">Strategi Formasi Futsal untuk Pemula</h3>
                            <p class="blog-excerpt">Mengenal berbagai formasi futsal yang efektif untuk tim
                                pemula...</p>
                            <a href="blog-detail.html" class="blog-link">Baca Selengkapnya <i
                                    class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>
                    <article class="card blog-card">
                        <div class="card-image"><img
                                src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=600&q=80"
                                alt="Teknik"></div>
                        <div class="card-body">
                            <div class="blog-meta">
                                <span><i class="fas fa-calendar"></i> 02 Jan 2026</span>
                                <span><i class="fas fa-tag"></i> Teknik</span>
                            </div>
                            <h3 class="blog-title">Teknik Passing Akurat dalam Futsal</h3>
                            <p class="blog-excerpt">Passing adalah fundamental dalam futsal. Pelajari teknik passing
                                yang akurat...</p>
                            <a href="blog-detail.html" class="blog-link">Baca Selengkapnya <i
                                    class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>
                    <article class="card blog-card">
                        <div class="card-image"><img
                                src="https://images.unsplash.com/photo-1551958219-acbc608c6377?w=600&q=80"
                                alt="Fisik"></div>
                        <div class="card-body">
                            <div class="blog-meta">
                                <span><i class="fas fa-calendar"></i> 28 Des 2025</span>
                                <span><i class="fas fa-tag"></i> Fisik</span>
                            </div>
                            <h3 class="blog-title">Latihan Fisik untuk Pemain Futsal</h3>
                            <p class="blog-excerpt">Kondisi fisik yang prima adalah kunci performa maksimal di
                                lapangan...</p>
                            <a href="blog-detail.html" class="blog-link">Baca Selengkapnya <i
                                    class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>
                    <article class="card blog-card">
                        <div class="card-image"><img
                                src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=600&q=80"
                                alt="Mental"></div>
                        <div class="card-body">
                            <div class="blog-meta">
                                <span><i class="fas fa-calendar"></i> 25 Des 2025</span>
                                <span><i class="fas fa-tag"></i> Mental</span>
                            </div>
                            <h3 class="blog-title">Persiapan Mental Menghadapi Turnamen</h3>
                            <p class="blog-excerpt">Mental yang kuat sama pentingnya dengan skill teknis dalam
                                kompetisi...</p>
                            <a href="blog-detail.html" class="blog-link">Baca Selengkapnya <i
                                    class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>
                </div>
                <!-- Pagination -->
                <div style="display: flex; justify-content: center; gap: var(--space-sm);">
                    <button class="btn btn-sm" style="background: var(--color-primary); color: white;">1</button>
                    <button class="btn btn-outline btn-sm">2</button>
                    <button class="btn btn-outline btn-sm">3</button>
                    <button class="btn btn-outline btn-sm"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>

            <!-- Sidebar -->
            <aside>
                <div class="card" style="padding: var(--space-lg); margin-bottom: var(--space-lg);">
                    <h4 style="margin-bottom: var(--space-md);">Cari Artikel</h4>
                    <div style="position: relative;">
                        <input type="text" class="form-control" placeholder="Ketik kata kunci..."
                            style="padding-right: 48px;">
                        <button
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--color-primary);"><i
                                class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="card" style="padding: var(--space-lg); margin-bottom: var(--space-lg);">
                    <h4 style="margin-bottom: var(--space-md);">Kategori</h4>
                    <ul style="display: grid; gap: var(--space-sm);">
                        <li><a href="#"
                                style="display: flex; justify-content: space-between; color: var(--color-gray-700);">Tips
                                <span class="badge badge-primary">8</span></a></li>
                        <li><a href="#"
                                style="display: flex; justify-content: space-between; color: var(--color-gray-700);">Strategi
                                <span class="badge badge-primary">5</span></a></li>
                        <li><a href="#"
                                style="display: flex; justify-content: space-between; color: var(--color-gray-700);">Teknik
                                <span class="badge badge-primary">7</span></a></li>
                        <li><a href="#"
                                style="display: flex; justify-content: space-between; color: var(--color-gray-700);">Gear
                                <span class="badge badge-primary">4</span></a></li>
                        <li><a href="#"
                                style="display: flex; justify-content: space-between; color: var(--color-gray-700);">Fisik
                                <span class="badge badge-primary">6</span></a></li>
                    </ul>
                </div>
                <div class="card" style="padding: var(--space-lg);">
                    <h4 style="margin-bottom: var(--space-md);">Artikel Populer</h4>
                    <div style="display: grid; gap: var(--space-md);">
                        <a href="#" style="display: flex; gap: var(--space-md);">
                            <img src="https://images.unsplash.com/photo-1599058917212-d750089bc07e?w=100&q=80"
                                style="width: 60px; height: 45px; object-fit: cover; border-radius: var(--radius-md);">
                            <div>
                                <p
                                    style="font-size: var(--text-sm); font-weight: 500; color: var(--color-dark); line-height: 1.3;">
                                    5 Tips Pemanasan Sebelum Main Futsal</p>
                                <span style="font-size: 12px; color: var(--color-gray-500);">10 Jan 2026</span>
                            </div>
                        </a>
                        <a href="#" style="display: flex; gap: var(--space-md);">
                            <img src="https://images.unsplash.com/photo-1511886929837-354d827aae26?w=100&q=80"
                                style="width: 60px; height: 45px; object-fit: cover; border-radius: var(--radius-md);">
                            <div>
                                <p
                                    style="font-size: var(--text-sm); font-weight: 500; color: var(--color-dark); line-height: 1.3;">
                                    Cara Memilih Sepatu Futsal</p>
                                <span style="font-size: 12px; color: var(--color-gray-500);">08 Jan 2026</span>
                            </div>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection