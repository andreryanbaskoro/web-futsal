<!-- ==================== TESTIMONIALS SECTION ==================== -->
@if($ulasanList->count() > 0)
<section class="section testimonials" id="testimonials">
  <div class="container">
    <div class="section-header">
      <span class="subtitle" style="color: var(--color-accent);">Testimoni</span>
      <h2 style="color: var(--color-white);">Apa Kata Mereka?</h2>
      <p style="color: rgba(255,255,255,0.7);">Dengarkan pengalaman pelanggan kami bermain di FUSTAL ACR</p>
    </div>
    <div class="testimonial-slider">
      @foreach ($ulasanList as $index => $ulasan)
      <div class="testimonial-card" id="testimonial-pelanggan-{{ $index + 1 }}" style="{{ $index > 0 ? 'display: none;' : '' }}">
        <p class="testimonial-quote">
          {{ $ulasan->komentar }}
        </p>
        <div class="testimonial-author">
          <div class="testimonial-avatar">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($ulasan->pengguna->nama_lengkap ?? 'User') }}&background=random" alt="{{ $ulasan->pengguna->nama_lengkap ?? 'User' }}">
          </div>
          <div class="testimonial-info">
            <div class="testimonial-name">{{ $ulasan->pengguna->nama_lengkap ?? 'Pengguna' }}</div>
            <div class="testimonial-role">{{ \Carbon\Carbon::parse($ulasan->created_at)->translatedFormat('d F Y, H:i') }}</div>
            <div class="testimonial-rating">
              @for($i = 1; $i <= 5; $i++)
                @if($i <= $ulasan->rating)
                  <i class="fas fa-star" style="color: #facc15;"></i>
                @else
                  <i class="fas fa-star" style="color: #e5e7eb;"></i>
                @endif
              @endfor
            </div>
          </div>
        </div>
      </div>
      @endforeach

      @if($ulasanList->count() > 1)
      <div class="testimonial-nav">
        @foreach($ulasanList as $index => $ulasan)
        <span class="testimonial-dot {{ $index == 0 ? 'active' : '' }}" data-slide="{{ $index + 1 }}" onclick="showTestimonialPelanggan({{ $index + 1 }})" style="cursor: pointer;"></span>
        @endforeach
      </div>
      @endif
      <script>
        let currentTestimonialPelanggan = 1;
        const totalTestimonialsPelanggan = {{ $ulasanList->count() }};

        function showTestimonialPelanggan(index) {
            currentTestimonialPelanggan = index;
            document.querySelectorAll('#testimonials .testimonial-card').forEach(card => {
                card.style.display = 'none';
                card.classList.remove('fade-in-slider');
            });
            document.querySelectorAll('#testimonials .testimonial-dot').forEach(dot => {
                dot.classList.remove('active');
            });
            
            const targetCard = document.getElementById('testimonial-pelanggan-' + index);
            if (targetCard) {
                targetCard.style.display = 'block';
                void targetCard.offsetWidth;
                targetCard.classList.add('fade-in-slider');
            }
            
            const targetDot = document.querySelector(`#testimonials .testimonial-dot[data-slide="${index}"]`);
            if (targetDot) targetDot.classList.add('active');
        }

        setInterval(() => {
            let next = currentTestimonialPelanggan + 1;
            if(next > totalTestimonialsPelanggan) next = 1;
            showTestimonialPelanggan(next);
        }, 5000);
      </script>
    </div>
  </div>
</section>
@endif