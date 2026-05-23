@php
use Illuminate\Support\Str;
@endphp

<!-- ==================== FIELDS SECTION ==================== -->
<section class="section" id="fields">
  <div class="container">

    <div class="section-header">
      <span class="subtitle">Pilihan Lapangan</span>
      <h2>Pilih Lapangan Favorit Anda</h2>
      <p>
        Berbagai pilihan lapangan dengan fasilitas lengkap siap untuk pertandingan Anda
      </p>
    </div>

    <div class="grid grid-3">

      @forelse ($lapanganList as $index => $lapangan)

      @php
      $hargaTermurah = $lapangan->jamOperasional->min('harga');

      $isAvailable = $lapangan->status === 'aktif';

      // IMAGE
      if ($lapangan->image_type === 'upload' && !empty($lapangan->image)) {
      $imageSrc = asset('storage/' . $lapangan->image);
      } elseif ($lapangan->image_type === 'url' && !empty($lapangan->image)) {
      $imageSrc = $lapangan->image;
      } else {
      $imageSrc = 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=600&q=80';
      }

      // STATUS BADGE
      $status = strtolower($lapangan->status);

      $badge = match($status) {

      'aktif' => [
      'bg' => '#16a34a',
      'icon' => 'fa-check-circle',
      'text' => 'Tersedia'
      ],

      'maintenance' => [
      'bg' => '#f59e0b',
      'icon' => 'fa-tools',
      'text' => 'Maintenance'
      ],

      'perbaikan' => [
      'bg' => '#dc2626',
      'icon' => 'fa-wrench',
      'text' => 'Perbaikan'
      ],

      'event' => [
      'bg' => '#2563eb',
      'icon' => 'fa-calendar',
      'text' => 'Dipakai Event'
      ],

      default => [
      'bg' => '#6b7280',
      'icon' => 'fa-info-circle',
      'text' => ucfirst($status)
      ]
      };
      @endphp

      <div
        class="card field-card animate-fadeInUp stagger-{{ $index + 1 }}"
        style="
          display:flex;
          flex-direction:column;
          height:100%;
          transition:.3s;
          {{ !$isAvailable ? 'opacity:.72; filter:grayscale(.2);' : '' }}
        ">

        {{-- IMAGE --}}
        <div class="card-image" style="position:relative;">

          <img
            src="{{ $imageSrc }}"
            alt="{{ $lapangan->nama_lapangan }}"
            loading="lazy">

          {{-- OVERLAY --}}
          @if (!$isAvailable)
          <div
            style="
              position:absolute;
              inset:0;
              background:rgba(0,0,0,.35);
              border-radius:inherit;
            ">
          </div>
          @endif

          {{-- BADGE --}}
          <span
            class="field-badge"
            style="
              background: {{ $badge['bg'] }};
              color:white;
              padding:6px 12px;
              border-radius:999px;
              font-size:12px;
              font-weight:600;
              display:inline-flex;
              align-items:center;
              gap:6px;
              box-shadow:0 4px 10px rgba(0,0,0,.15);
            ">

            <i class="fas {{ $badge['icon'] }}"></i>
            {{ $badge['text'] }}

          </span>
        </div>

        {{-- BODY --}}
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

          {{-- FOOTER --}}
          <div
            class="field-footer"
            style="
              margin-top:auto;
              display:flex;
              justify-content:space-between;
              align-items:center;
            ">

            <div class="field-price">
              Rp {{ number_format($hargaTermurah ?? 0, 0, ',', '.') }}
              <span>/jam</span>
            </div>

            @if ($isAvailable)

            <a href="{{ route('pelanggan.jadwal', ['id_lapangan' => $lapangan->id_lapangan]) }}"
              class="btn btn-primary btn-sm">
              <i class="fas fa-calendar-check me-1"></i>
              Booking
            </a>

            @else

            <button
              class="btn btn-secondary btn-sm"
              disabled
              style="cursor:not-allowed;opacity:.7;">
              <i class="fas fa-ban me-1"></i>
              Tidak Tersedia
            </button>

            @endif

          </div>
        </div>
      </div>

      @empty
      <p class="text-center">
        Belum ada lapangan tersedia.
      </p>
      @endforelse

    </div>

    <div class="text-center mt-xl">
      <a href="{{ route('pelanggan.lapangan') }}" class="btn btn-primary">
        Lihat Semua Lapangan
        <i class="fas fa-arrow-right"></i>
      </a>
    </div>

  </div>
</section>