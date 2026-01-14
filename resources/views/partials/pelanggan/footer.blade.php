<!-- ==================== FOOTER ==================== -->
<footer class="footer" id="footer">
  <div class="container">
    <div class="footer-grid">

      {{-- BRAND --}}
      <div class="footer-brand">
        <a href="{{ route('beranda') }}" class="navbar-brand">
          <i class="fas fa-futbol"></i>
          <span>FUSTAL ACR</span>
        </a>
        <p>
          Penyedia lapangan futsal premium dengan fasilitas terbaik dan sistem booking online 24/7.
          Nikmati pengalaman bermain futsal yang menyenangkan!
        </p>

        <div class="footer-social">
          <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
          <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
        </div>
      </div>

      {{-- NAVIGASI --}}
      <div>
        <h4 class="footer-title">Navigasi</h4>
        <ul class="footer-links">
          <li><a href="{{ route('beranda') }}">Beranda</a></li>
          <li><a href="{{ route('lapangan') }}">Lapangan</a></li>
          <li><a href="{{ route('jadwal') }}">Cek Jadwal</a></li>
          <li><a href="{{ route('galeri') }}">Galeri</a></li>
          <li><a href="{{ route('blog') }}">Blog</a></li>
        </ul>
      </div>

      {{-- INFORMASI --}}
      <div>
        <h4 class="footer-title">Informasi</h4>
        <ul class="footer-links">
          <li><a href="{{ route('tentang') }}">Tentang Kami</a></li>
          <li><a href="{{ route('faq') }}">FAQ</a></li>
          <li><a href="{{ route('kontak') }}">Kontak</a></li>
          <li><a href="{{ route('syarat') }}">Syarat & Ketentuan</a></li>
        </ul>
      </div>

      {{-- KONTAK --}}
      <div>
        <h4 class="footer-title">Hubungi Kami</h4>
        <ul class="footer-contact">
          <li>
            <i class="fas fa-map-marker-alt"></i>
            <span>
              Jl. Sentani No. 123,<br>
              Setani, Jayapura,<br>
              Papua 99351
            </span>
          </li>
          <li>
            <i class="fas fa-phone"></i>
            <span>+62 812-3456-7890</span>
          </li>
          <li>
            <i class="fas fa-envelope"></i>
            <span>admin.futsalacr.id</span>
          </li>
          <li>
            <i class="fas fa-clock"></i>
            <span>Buka 24 Jam</span>
          </li>
        </ul>
      </div>

    </div>

    {{-- BOTTOM --}}
    <div class="footer-bottom">
      <p>&copy; 2026 ARB. All rights reserved.</p>

      <div class="footer-legal">
        <a href="{{ route('syarat') }}">Syarat & Ketentuan</a>
        <a href="#">Kebijakan Privasi</a>
      </div>
    </div>
  </div>
</footer>