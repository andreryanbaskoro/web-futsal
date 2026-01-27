<!-- ==================== GALLERY SECTION ==================== -->
<section class="section" id="gallery">
  <div class="container">
    <div class="section-header">
      <span class="subtitle">Galeri</span>
      <h2>Lihat Fasilitas Kami</h2>
      <p>Intip suasana lapangan dan fasilitas yang kami sediakan</p>
    </div>

    <div class="gallery-grid">
      @forelse ($galleryList as $gallery)
      <div class="gallery-item">
        <img
          src="{{ $gallery->image_url }}"
          alt="{{ $gallery->title }}"
          loading="lazy">

        <div class="gallery-overlay">
          <span>{{ $gallery->title }}</span>
        </div>
      </div>
      @empty
      {{-- fallback kalau galeri kosong --}}
      <div class="gallery-item">
        <img src="https://images.unsplash.com/photo-1552667466-07770ae110d0?w=800&q=80"
          alt="Lapangan Futsal">
        <div class="gallery-overlay">
          <span>Lapangan Futsal</span>
        </div>
      </div>
      @endforelse

      {{-- tombol lihat semua --}}
      @if($galleryRemaining > 0)
      <div class="gallery-item gallery-more">
        <a href="{{ route('pelanggan.galeri') }}">
          +{{ $galleryRemaining }} Foto Lainnya
        </a>
      </div>
      @endif

    </div>
  </div>
</section>