@extends('layouts.app')

@section('title', 'Booking Lapangan Futsal Online')

@push('styles')
<style>
    .article-content {
        max-width: 800px;
        margin: 0 auto;
    }

    .article-header {
        text-align: center;
        margin-bottom: var(--space-2xl);
    }

    .article-meta {
        display: flex;
        justify-content: center;
        gap: var(--space-lg);
        color: var(--color-gray-600);
        font-size: var(--text-sm);
        margin-bottom: var(--space-lg);
        flex-wrap: wrap;
    }

    .article-featured {
        width: 100%;
        border-radius: var(--radius-xl);
        margin-bottom: var(--space-2xl);
    }

    .article-body {
        font-size: var(--text-lg);
        line-height: 1.8;
    }

    .article-body h2 {
        margin: var(--space-2xl) 0 var(--space-md);
    }

    .article-body p {
        margin-bottom: var(--space-lg);
    }

    .article-body ul,
    .article-body ol {
        margin-bottom: var(--space-lg);
        padding-left: var(--space-xl);
    }

    .article-body li {
        margin-bottom: var(--space-sm);
    }

    .article-body blockquote {
        background: var(--color-gray-100);
        border-left: 4px solid var(--color-primary);
        padding: var(--space-lg);
        margin: var(--space-xl) 0;
        font-style: italic;
        border-radius: 0 var(--radius-lg) var(--radius-lg) 0;
    }

    .article-tags {
        display: flex;
        gap: var(--space-sm);
        flex-wrap: wrap;
        margin: var(--space-2xl) 0;
    }

    .article-share {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-lg);
        background: var(--color-gray-100);
        border-radius: var(--radius-lg);
    }

    .share-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
</style>
@endpush


@section('content')
<section style="padding: 120px 0 60px;">
    <div class="container">
        <div class="article-content">
            <a href="blog.html"
                style="display: inline-flex; align-items: center; gap: var(--space-sm); color: var(--color-primary); margin-bottom: var(--space-lg);"><i
                    class="fas fa-arrow-left"></i> Kembali ke Blog</a>

            <article>
                <header class="article-header">
                    <span class="badge badge-primary" style="margin-bottom: var(--space-md);">Tips</span>
                    <h1>5 Tips Pemanasan Sebelum Main Futsal</h1>
                    <div class="article-meta">
                        <span><i class="fas fa-calendar"></i> 10 Januari 2026</span>
                        <span><i class="fas fa-user"></i> Admin</span>
                        <span><i class="fas fa-clock"></i> 5 menit baca</span>
                    </div>
                </header>

                <img src="https://images.unsplash.com/photo-1599058917212-d750089bc07e?w=1200&q=80"
                    alt="Tips Pemanasan Futsal" class="article-featured">

                <div class="article-body">
                    <p>Pemanasan yang benar sangat penting untuk mencegah cedera saat bermain futsal. Banyak pemain
                        yang meremehkan pentingnya pemanasan dan langsung bermain tanpa persiapan yang cukup.
                        Akibatnya, risiko cedera meningkat dan performa permainan tidak optimal.</p>

                    <p>Berikut adalah 5 tips pemanasan yang efektif sebelum bermain futsal:</p>

                    <h2>1. Jogging Ringan (5-10 Menit)</h2>
                    <p>Mulailah dengan jogging ringan mengelilingi lapangan selama 5-10 menit. Ini akan meningkatkan
                        detak jantung secara bertahap dan mengalirkan darah ke seluruh tubuh, terutama ke otot-otot
                        yang akan digunakan saat bermain.</p>

                    <h2>2. Stretching Dinamis</h2>
                    <p>Lakukan stretching dinamis seperti leg swings, arm circles, dan lunge walks. Stretching
                        dinamis lebih efektif dibandingkan stretching statis untuk persiapan olahraga karena
                        membantu meningkatkan fleksibilitas sambil menjaga otot tetap hangat.</p>
                    <ul>
                        <li>Leg swings (10 kali setiap kaki)</li>
                        <li>Arm circles (10 kali ke depan dan ke belakang)</li>
                        <li>High knees (20 langkah)</li>
                        <li>Butt kicks (20 langkah)</li>
                    </ul>

                    <h2>3. Latihan Koordinasi dengan Bola</h2>
                    <p>Setelah tubuh mulai terasa hangat, lanjutkan dengan latihan koordinasi menggunakan bola.
                        Passing ringan dengan rekan tim atau kontrol bola sederhana akan membantu Anda mempersiapkan
                        feel terhadap bola.</p>

                    <blockquote>
                        "Pemanasan yang baik adalah investasi untuk performa yang optimal dan tubuh yang bebas
                        cedera." - Pelatih Futsal Profesional
                    </blockquote>

                    <h2>4. Sprint Pendek</h2>
                    <p>Lakukan beberapa sprint pendek (10-15 meter) dengan intensitas yang meningkat secara
                        bertahap. Ini akan mempersiapkan otot Anda untuk gerakan eksplosif yang sering terjadi dalam
                        permainan futsal.</p>

                    <h2>5. Latihan Spesifik Posisi</h2>
                    <p>Jika Anda bermain di posisi tertentu, lakukan latihan spesifik sesuai posisi Anda. Misalnya:
                    </p>
                    <ul>
                        <li><strong>Kiper:</strong> Latihan tangkapan dan diving ringan</li>
                        <li><strong>Pivot:</strong> Latihan pivot dan turning</li>
                        <li><strong>Fixo:</strong> Latihan passing akurat dan positioning</li>
                    </ul>

                    <h2>Kesimpulan</h2>
                    <p>Pemanasan yang tepat tidak hanya mencegah cedera tetapi juga meningkatkan performa Anda di
                        lapangan. Luangkan waktu 15-20 menit untuk pemanasan sebelum setiap sesi bermain futsal.
                        Tubuh yang siap akan memberikan hasil permainan yang lebih baik!</p>

                    <p>Selamat bermain dan jangan lupa untuk selalu melakukan pemanasan! 🎯⚽</p>
                </div>

                <div class="article-tags">
                    <span>Tags:</span>
                    <span class="badge badge-dark">Tips</span>
                    <span class="badge badge-dark">Pemanasan</span>
                    <span class="badge badge-dark">Kesehatan</span>
                    <span class="badge badge-dark">Futsal</span>
                </div>

                <div class="article-share">
                    <span style="font-weight: 500;">Bagikan:</span>
                    <a href="#" class="share-btn" style="background: #1877F2;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="share-btn" style="background: #1DA1F2;"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="share-btn" style="background: #25D366;"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="share-btn" style="background: var(--color-dark);"><i
                            class="fas fa-link"></i></a>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <h3 style="margin-bottom: var(--space-xl);">Artikel Terkait</h3>
        <div class="grid grid-3">
            <article class="card blog-card">
                <div class="card-image"><img
                        src="https://images.unsplash.com/photo-1517466787929-bc90951d0974?w=600&q=80"
                        alt="Strategi"></div>
                <div class="card-body">
                    <div class="blog-meta"><span><i class="fas fa-calendar"></i> 05 Jan 2026</span></div>
                    <h3 class="blog-title">Strategi Formasi Futsal untuk Pemula</h3>
                    <a href="#" class="blog-link">Baca <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>
            <article class="card blog-card">
                <div class="card-image"><img
                        src="https://images.unsplash.com/photo-1551958219-acbc608c6377?w=600&q=80" alt="Fisik">
                </div>
                <div class="card-body">
                    <div class="blog-meta"><span><i class="fas fa-calendar"></i> 28 Des 2025</span></div>
                    <h3 class="blog-title">Latihan Fisik untuk Pemain Futsal</h3>
                    <a href="#" class="blog-link">Baca <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>
            <article class="card blog-card">
                <div class="card-image"><img
                        src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=600&q=80" alt="Teknik">
                </div>
                <div class="card-body">
                    <div class="blog-meta"><span><i class="fas fa-calendar"></i> 02 Jan 2026</span></div>
                    <h3 class="blog-title">Teknik Passing Akurat dalam Futsal</h3>
                    <a href="#" class="blog-link">Baca <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>
        </div>
    </div>
</section>
@endsection