 <!-- ==================== NAVBAR ==================== -->
 <nav class="navbar" id="navbar">
   <div class="container navbar-container">
     <a href="{{ route('pelanggan.beranda') }}" class="navbar-brand">
       <i class="fas fa-futbol"></i>
       <span>Futsal ACR</span>
     </a>

     <div class="navbar-menu" id="navbarMenu">
       <ul class="navbar-nav">
         <li>
           <a href="{{ route('pelanggan.beranda') }}"
             class="nav-link {{ request()->routeIs('pelanggan.beranda') ? 'active' : '' }}">
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
           <a href="{{ route('pelanggan.booking.history') }}"
             class="nav-link {{ request()->routeIs('pelanggan.booking.history') ? 'active' : '' }}">
             Pemesanan Saya
           </a>
         </li>

       </ul>

       <div class="profile-dropdown">
         <button class="profile-btn" id="profileToggle">
           <i class="fas fa-user"></i>
           <span>{{ explode(' ', Auth::user()->nama)[0] }}</span> <!-- Ambil nama depan -->
           <i class="fas fa-chevron-down arrow"></i>
         </button>

         <ul class="profile-menu" id="profileMenu">
           <li>
             <a href="{{ route('pelanggan.profile.index') }}">
               <i class="fas fa-id-card"></i>
               Profil Saya
             </a>
           </li>

           <li>
             <a href="{{ route('pelanggan.booking.history') }}">
               <i class="fas fa-receipt"></i>
               Riwayat Pemesanan
             </a>
           </li>

           <li class="divider"></li>

           <li>
             <form method="POST" action="{{ route('logout') }}">
               @csrf
               <button type="submit" class="logout-btn">
                 <i class="fas fa-sign-out-alt"></i>
                 Logout
               </button>
             </form>
           </li>
         </ul>
       </div>





     </div>

     <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle navigation">
     </button>
   </div>
 </nav>