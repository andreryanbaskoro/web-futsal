<!-- ==================== FOOTER ==================== -->
<footer class="footer" id="footer">
  <div class="container">
    <div class="footer-grid">

      {{-- BRAND --}}
      <div class="footer-brand">
        <a href="{{ route('pelanggan.beranda') }}" class="navbar-brand">
          <i class="fas fa-futbol"></i>
          <span>FUSTAL ACR</span>
        </a>
        <p>
          Penyedia lapangan futsal premium dengan fasilitas terbaik dan sistem booking online 24/7.
          Nikmati pengalaman bermain futsal yang menyenangkan!
        </p>

        <div class="footer-social">
          <!-- Facebook -->
          <a href="https://www.facebook.com/yourpage" aria-label="Facebook" target="_blank">
            <i class="fab fa-facebook-f"></i>
          </a>

          <!-- Instagram -->
          <a href="https://www.instagram.com/yourprofile" aria-label="Instagram" target="_blank">
            <i class="fab fa-instagram"></i>
          </a>

          <!-- X (Twitter) -->
          <a href="https://twitter.com/yourprofile" aria-label="Twitter" target="_blank">
            <i class="fab fa-x"></i> <!-- Update this to 'fab fa-twitter' if you're using FontAwesome -->
          </a>

          <!-- YouTube -->
          <a href="https://www.youtube.com/c/yourchannel" aria-label="YouTube" target="_blank">
            <i class="fab fa-youtube"></i>
          </a>
        </div>

      </div>

      {{-- NAVIGASI --}}
      <div>
        <h4 class="footer-title">Navigasi</h4>
        <ul class="footer-links">
          <li><a href="{{ route('pelanggan.beranda') }}">Beranda</a></li>
          <li><a href="{{ route('pelanggan.lapangan') }}">Lapangan</a></li>
          <li><a href="{{ route('pelanggan.jadwal') }}">Cek Jadwal</a></li>
          <li><a href="{{ route('pelanggan.galeri') }}">Galeri</a></li>
          <li><a href="{{ route('pelanggan.blog') }}">Blog</a></li>
        </ul>
      </div>

      {{-- INFORMASI --}}
      <div>
        <h4 class="footer-title">Informasi</h4>
        <ul class="footer-links">
          <li><a href="{{ route('pelanggan.tentang') }}">Tentang Kami</a></li>
          <li><a href="{{ route('pelanggan.faq') }}">FAQ</a></li>
          <li><a href="{{ route('pelanggan.kontak') }}">Kontak</a></li>
          <li><a href="{{ route('pelanggan.syarat') }}">Syarat & Ketentuan</a></li>
        </ul>
      </div>

      {{-- KONTAK --}}
      <div>
        <h4 class="footer-title">Hubungi Kami</h4>
        <ul class="footer-contact">
          <li>
            <i class="fas fa-map-marker-alt"></i>
            <span>
              Jl. Youmakhe Kelurahan Hinekombe,Distrik Sentani, Kabupaten Jayapura<br>
              Papua 99351
            </span>
          </li>
          <li>
            <i class="fas fa-phone"></i>
            <span>0811481679 & 08134751554</span> <br>
          </li>
          <li>
            <i class="fas fa-envelope"></i>
            <span>acr@futsal.id</span>
          </li>
          <li>
            <i class="fas fa-clock"></i>
            <span>10:00 - 22:00 WIT</span>
          </li>
        </ul>
      </div>

    </div>

    {{-- BOTTOM --}}
    <div class="footer-bottom">
      <p>&copy; 2026 ARB. All rights reserved.</p>

      <div class="footer-legal">
        <a href="#">Syarat & Ketentuan Kebijakan Privasi</a>
      </div>
    </div>
  </div>
</footer>