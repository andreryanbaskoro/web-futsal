@extends('layouts.pelanggan')

@section('title', 'Blog | Futsal ACR')

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
                    @forelse ($articles as $article)
                    <article class="card blog-card">
                        <div class="card-image">
                            <img src="{{ $article->featured_image_url }}"
                                alt="{{ $article->judul }}">
                        </div>

                        <div class="card-body">
                            <div class="blog-meta">
                                <span>
                                    <i class="fas fa-calendar"></i>
                                    {{ $article->tanggal_post->format('d M Y') }}
                                </span>
                                <span>
                                    <i class="fas fa-tag"></i>
                                    {{ $article->kategori }}
                                </span>
                            </div>

                            <h3 class="blog-title">{{ $article->judul }}</h3>

                            <p class="blog-excerpt">
                                {{ Str::limit(strip_tags($article->konten), 120) }}
                            </p>

                            <a href="{{ route('pelanggan.blog.detail', $article->slug) }}"
                                class="blog-link">
                                Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                    @empty
                    <p>Belum ada artikel.</p>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div style="display:flex; justify-content:center;">
                    {{ $articles->links() }}
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
                        @foreach ($categories as $cat)
                        <li>
                            <a href="{{ route('blog', ['kategori' => $cat->kategori]) }}"
                                style="display:flex; justify-content:space-between; color:var(--color-gray-700);">
                                {{ $cat->kategori }}
                                <span class="badge badge-primary">{{ $cat->total }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>

                </div>
                <div class="card" style="padding: var(--space-lg);">
                    <h4 style="margin-bottom: var(--space-md);">Artikel Populer</h4>
                    <div style="display: grid; gap: var(--space-md);">
                        @foreach ($popularArticles as $pop)
                        <a href="{{ route('blog.detail', $pop->slug) }}"
                            style="display: flex; gap: var(--space-md);">
                            <img src="{{ $pop->featured_image_url }}"
                                style="width:60px; height:45px; object-fit:cover; border-radius:var(--radius-md);">

                            <div>
                                <p style="font-size: var(--text-sm); font-weight: 500; line-height: 1.3;">
                                    {{ Str::limit($pop->judul, 50) }}
                                </p>
                                <span style="font-size:12px; color:var(--color-gray-500);">
                                    {{ $pop->tanggal_post->format('d M Y') }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>

                </div>
            </aside>
        </div>
    </div>
</section>
@endsection