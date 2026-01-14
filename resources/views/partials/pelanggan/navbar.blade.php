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
           <a href="#"
             class="nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}">
             Beranda
           </a>
         </li>

         <li>
           <a href="{{ route('pelanggan.lapangan') }}"
             class="nav-link {{ request()->is('pelanggan/lapangan*') ? 'active' : '' }}">
             Lapangan
           </a>
         </li>


         <li>
           <a href="{{ route('pelanggan.jadwal') }}"
             class="nav-link {{ request()->routeIs('pelanggan.jadwal*') ? 'active' : '' }}">
             Jadwal
           </a>
         </li>
         <li>
           <a href="#"
             class="nav-link {{ request()->routeIs('pelanggan.pemesanan*') ? 'active' : '' }}">
             Pemesanan Saya
           </a>
         </li>
       </ul>

       <div class="profile-dropdown">
         <button class="profile-btn" id="profileToggle">
           <i class="fas fa-user"></i>
           <span>Profile</span>
           <i class="fas fa-chevron-down arrow"></i>
         </button>

         <ul class="profile-menu" id="profileMenu">
           <li>
             <a href="#">
               <i class="fas fa-id-card"></i> Profil Saya
             </a>
           </li>
           <li>
             <a href="#">
               <i class="fas fa-receipt"></i> Riwayat Pemesanan
             </a>
           </li>
           <li class="divider"></li>
           <li>
             <a href="#" class="logout">
               <i class="fas fa-sign-out-alt"></i> Logout
             </a>
           </li>
         </ul>
       </div>



     </div>

     <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle navigation">
     </button>
   </div>
 </nav>