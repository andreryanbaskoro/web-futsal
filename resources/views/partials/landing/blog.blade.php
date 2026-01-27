@php
use Illuminate\Support\Str;
@endphp

<!-- ==================== BLOG SECTION ==================== -->
<section class="section bg-light" id="blog">
  <div class="container">
    <div class="section-header">
      <span class="subtitle">Blog</span>
      <h2>Artikel Terbaru</h2>
      <p>Tips, trik, dan informasi seputar futsal untuk Anda</p>
    </div>

    <div class="grid grid-3">
      @forelse ($articleList as $article)

      @php
      // featured image
      if (!empty($article->featured_image)) {
      if (Str::startsWith($article->featured_image, ['http://', 'https://'])) {
      $imageSrc = $article->featured_image;
      } else {
      $imageSrc = asset('storage/' . $article->featured_image);
      }
      } else {
      // default unsplash
      $imageSrc = 'https://images.unsplash.com/photo-1599058917212-d750089bc07e?w=600&q=80';
      }
      @endphp

      <article class="card blog-card">
        <div class="card-image">
          <img src="{{ $imageSrc }}" alt="{{ $article->judul }}">
        </div>

        <div class="card-body">
          <div class="blog-meta">
            <span>
              <i class="fas fa-calendar"></i>
              {{ $article->tanggal_post?->format('d M Y') }}
            </span>
            <span>
              <i class="fas fa-user"></i>
              {{ $article->author ?? 'Admin' }}
            </span>
          </div>

          <h3 class="blog-title">
            {{ Str::limit($article->judul, 60) }}
          </h3>

          <p class="blog-excerpt">
            {{ Str::limit(strip_tags($article->konten), 120) }}
          </p>

          <a href="{{ route('blog.detail', $article->slug) }}" class="blog-link">
            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </article>

      @empty
      <p class="text-center">Belum ada artikel.</p>
      @endforelse
    </div>

    {{-- tombol lihat semua --}}
    @if($articleRemaining > 0)
    <div class="text-center mt-xl">
      <a href="{{ route('blog') }}" class="btn btn-outline">
        +{{ $articleRemaining }} Artikel Lainnya
        <i class="fas fa-arrow-right"></i>
      </a>
    </div>
    @endif
  </div>
</section>