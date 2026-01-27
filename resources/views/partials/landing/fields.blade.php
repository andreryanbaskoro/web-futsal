@php
use Illuminate\Support\Str;
@endphp

<!-- ==================== FIELDS SECTION ==================== -->
<section class="section" id="fields">
  <div class="container">
    <div class="section-header">
      <span class="subtitle">Pilihan Lapangan</span>
      <h2>Pilih Lapangan Favorit Anda</h2>
      <p>Berbagai pilihan lapangan dengan fasilitas lengkap siap untuk pertandingan Anda</p>
    </div>

    <div class="grid grid-3">
      @forelse ($lapanganList as $index => $lapangan)
      @php
      $hargaTermurah = $lapangan->jamOperasional->min('harga');
      @endphp

      <div
        class="card field-card animate-fadeInUp stagger-{{ $index + 1 }}"
        style="display:flex;flex-direction:column;height:100%;">
        <div class="card-image">
          @php
          if ($lapangan->image_type === 'upload' && !empty($lapangan->image)) {
          $imageSrc = asset('storage/' . $lapangan->image);
          } elseif ($lapangan->image_type === 'url' && !empty($lapangan->image)) {
          $imageSrc = $lapangan->image;
          } else {
          // DEFAULT UNSPLASH
          $imageSrc = 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=600&q=80';
          }
          @endphp

          <img
            src="{{ $imageSrc }}"
            alt="{{ $lapangan->nama_lapangan }}"
            loading="lazy">


          <span class="field-badge badge badge-success">
            <i class="fas fa-check-circle"></i> Tersedia
          </span>
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
              {{ $lapangan->kapasitas ?? '-' }}
            </span>

            <span class="field-rating">
              <i class="fas fa-star"></i>
              {{ number_format($lapangan->rating, 1) }}
            </span>
          </div>

          <p class="card-text">
            {{ Str::limit($lapangan->deskripsi, 120) }}
          </p>

          <div
            class="field-footer"
            style="margin-top:auto;display:flex;justify-content:space-between;align-items:center;">
            <div class="field-price">
              Rp {{ number_format($hargaTermurah ?? 0, 0, ',', '.') }}
              <span>/jam</span>
            </div>

            <a href="{{ route('jadwal') }}"
              class="btn btn-outline btn-sm">
              Lihat Jadwal
            </a>
          </div>
        </div>
      </div>
      @empty
      <p class="text-center">Belum ada lapangan tersedia.</p>
      @endforelse
    </div>

    <div class="text-center mt-xl">
      <a href="{{ route('lapangan') }}" class="btn btn-primary">
        Lihat Semua Lapangan <i class="fas fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>