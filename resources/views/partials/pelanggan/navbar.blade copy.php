 <!-- ==================== NAVBAR ==================== -->
 <nav class="navbar" id="navbar">
   <div class="container navbar-container">
     <a href="{{ route('beranda') }}" class="navbar-brand">
       <i class="fas fa-futbol"></i>
       <span>Futsal ACR</span>
     </a>

     <div class="navbar-menu" id="navbarMenu">
       <ul class="navbar-nav">

         <li>
           <a href="{{ route('beranda') }}"
             class="nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}">
             Beranda
           </a>
         </li>

         <li>
           <a href="{{ route('lapangan') }}"
             class="nav-link {{ request()->routeIs('lapangan') ? 'active' : '' }}">
             Lapangan
           </a>
         </li>

         <li>
           <a href="{{ route('jadwal') }}"
             class="nav-link {{ request()->routeIs('jadwal') ? 'active' : '' }}">
             Jadwal
           </a>
         </li>

         <li>
           <a href="{{ route('galeri') }}"
             class="nav-link {{ request()->routeIs('galeri') ? 'active' : '' }}">
             Galeri
           </a>
         </li>

         <li>
           <a href="{{ route('blog') }}"
             class="nav-link {{ request()->routeIs('blog*') ? 'active' : '' }}">
             Blog
           </a>
         </li>


         <li>
           <a href="{{ route('kontak') }}"
             class="nav-link {{ request()->routeIs('kontak') ? 'active' : '' }}">
             Kontak
           </a>
         </li>
       </ul>

       <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
         <i class="fas fa-user"></i> Masuk
       </a>


     </div>

     <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle navigation">
       <span></span>
       <span></span>
       <span></span>
     </button>
   </div>
 </nav>