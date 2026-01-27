 <!-- ==================== HERO SECTION ==================== -->
 <section class="hero" id="hero">
   <div class="hero-bg">
     <img src="https://images.unsplash.com/photo-1552667466-07770ae110d0?w=1920&q=80" alt="FUSTAL ACR Background">
   </div>
   <div class="container">
     <div class="hero-content animate-fadeInUp">
       <h1 class="hero-title">
         Booking Lapangan Futsal <span>Lebih Mudah & Cepat</span>
       </h1>
       <p class="hero-subtitle">
         Nikmati pengalaman bermain futsal terbaik dengan fasilitas premium dan sistem booking online. Pilih jadwal, bayar online, langsung main!
       </p>
       <div class="hero-cta">
         <a href="{{ route('pelanggan.jadwal') }}" class="btn btn-primary btn-lg">
           <i class="fas fa-calendar-check"></i> Booking Sekarang
         </a>

         <a href="{{ route('pelanggan.lapangan') }}" class="btn btn-secondary btn-lg">
           <i class="fas fa-eye"></i> Lihat Lapangan
         </a>

       </div>
       <div class="hero-stats">
         <div class="stat-item">
           <div class="stat-value">
             {{ $totalLapangan }}+
           </div>
           <div class="stat-label">Lapangan Premium</div>
         </div>

         <div class="stat-item">
           <div class="stat-value">
             {{ number_format($bookingBulanIni) }}+
           </div>
           <div class="stat-label">Booking/Bulan</div>
         </div>

         <div class="stat-item">
           <div class="stat-value">
             {{ number_format($ratingRataRata, 1) }}
           </div>
           <div class="stat-label">Rating Pelanggan</div>
         </div>
       </div>
     </div>
   </div>
   </div>
 </section>