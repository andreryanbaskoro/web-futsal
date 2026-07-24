<!-- ==================== TESTIMONIALS SECTION ==================== -->
<section class="section testimonials" id="testimonials">
  <div class="container">
    <div class="section-header">
      <span class="subtitle" style="color: var(--color-accent);">Testimoni</span>
      <h2 style="color: var(--color-white);">Apa Kata Mereka?</h2>
      <p style="color: rgba(255,255,255,0.7);">Dengarkan pengalaman pelanggan kami bermain di FUSTAL ACR</p>
    </div>
    <div class="testimonial-slider">
      @forelse ($ulasanList as $index => $ulasan)
      <div class="testimonial-card" id="testimonial-{{ $index + 1 }}" style="{{ $index > 0 ? 'display: none;' : '' }}">
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
      @empty
      <div class="testimonial-card" id="testimonial-1">
        <p class="testimonial-quote" style="text-align: center;">
          Belum ada ulasan dari pelanggan. Jadilah yang pertama memberikan ulasan setelah bermain!
        </p>
      </div>
      @endforelse

      @if($ulasanList->count() > 1)
      <div class="testimonial-nav">
        @foreach($ulasanList as $index => $ulasan)
        <span class="testimonial-dot {{ $index == 0 ? 'active' : '' }}" data-slide="{{ $index + 1 }}" onclick="showTestimonial({{ $index + 1 }})" style="cursor: pointer;"></span>
        @endforeach
      </div>
      <script>
        function showTestimonial(index) {
            document.querySelectorAll('.testimonial-card').forEach(card => {
                card.style.display = 'none';
            });
            document.querySelectorAll('.testimonial-dot').forEach(dot => {
                dot.classList.remove('active');
            });
            
            const targetCard = document.getElementById('testimonial-' + index);
            if (targetCard) targetCard.style.display = 'block';
            
            const targetDot = document.querySelector(`.testimonial-dot[data-slide="${index}"]`);
            if (targetDot) targetDot.classList.add('active');
        }
      </script>
      @endif
    </div>
  </div>
</section>