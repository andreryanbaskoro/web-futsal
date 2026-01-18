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
           <a href="#"
             class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}">
             Daftar
           </a>
         </li>
     </div>

     <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle navigation">
     </button>
   </div>
 </nav>