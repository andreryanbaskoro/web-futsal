@extends('layouts.pelanggan')


@section('title', 'Blog Detail | Futsal ACR')

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

    .article-featured {
        width: 100%;
        max-width: 100%;
        height: auto;
        max-height: 250px;
        /* desktop limit */
        object-fit: cover;
        border-radius: var(--radius-xl);
        margin-bottom: var(--space-2xl);
    }

    .article-image-wrapper {
        width: 100%;
        overflow: hidden;
        border-radius: var(--radius-xl);
    }


    /* Tablet */
    @media (max-width: 1024px) {
        .article-featured {
            max-height: 360px;
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .article-featured {
            max-height: 260px;
            border-radius: var(--radius-lg);
        }
    }
</style>
@endpush


@section('content')
<section style="padding: 120px 0 60px;">
    <div class="container">
        <div class="article-content">
            <a href="{{ route('pelanggan.blog') }}"
                style="display:inline-flex; align-items:center; gap:var(--space-sm); color:var(--color-primary); margin-bottom:var(--space-lg);">
                <i class="fas fa-arrow-left"></i> Kembali ke Blog
            </a>


            <article>
                <header class="article-header">
                    <span class="badge badge-primary" style="margin-bottom: var(--space-md);">
                        {{ $article->kategori }}
                    </span>

                    <h1>{{ $article->judul }}</h1>

                    <div class="article-meta">
                        <span>
                            <i class="fas fa-calendar"></i>
                            {{ $article->tanggal_post->format('d F Y') }}
                        </span>
                        <span>
                            <i class="fas fa-user"></i>
                            {{ $article->author ?? 'Admin' }}
                        </span>
                        <span>
                            <i class="fas fa-clock"></i>
                            {{ $article->waktu_baca ?? '-' }} baca
                        </span>
                    </div>
                </header>


                <div class="article-image-wrapper">
                    <img src="{{ $article->featured_image_url }}"
                        alt="{{ $article->judul }}"
                        class="article-featured">
                </div>


                <div class="article-body">
                    {!! $article->konten !!}
                </div>


                @if ($article->tags)
                <div class="article-tags">
                    <span>Tags:</span>
                    @foreach ($article->tags as $tag)
                    <span class="badge badge-dark">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif


                @php
                $url = url()->current();
                @endphp

                <div class="article-share">
                    <span style="font-weight: 500;">Bagikan:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}"
                        target="_blank" class="share-btn" style="background:#1877F2;">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://wa.me/?text={{ $url }}"
                        target="_blank" class="share-btn" style="background:#25D366;">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="{{ $url }}"
                        class="share-btn" style="background:var(--color-dark);">
                        <i class="fas fa-link"></i>
                    </a>
                </div>

            </article>
        </div>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <h3 style="margin-bottom: var(--space-xl);">Artikel Terkait</h3>

        <div class="grid grid-3">
            @forelse ($relatedArticles as $related)
            <article class="card blog-card">
                <div class="card-image">
                    <img src="{{ $related->featured_image_url }}"
                        alt="{{ $related->judul }}">
                </div>

                <div class="card-body">
                    <div class="blog-meta">
                        <span>
                            <i class="fas fa-calendar"></i>
                            {{ $related->tanggal_post->format('d M Y') }}
                        </span>
                    </div>

                    <h3 class="blog-title">
                        {{ Str::limit($related->judul, 60) }}
                    </h3>

                    <a href="{{ route('pelanggan.blog.detail', $related->slug) }}"
                        class="blog-link">
                        Baca <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </article>
            @empty
            <p>Tidak ada artikel terkait.</p>
            @endforelse
        </div>
    </div>
</section>

@endsection