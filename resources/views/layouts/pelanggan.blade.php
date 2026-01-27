<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Booking lapangan futsal online lebih mudah dan cepat. Fasilitas premium, booking 24/7, pembayaran digital. FUSTAL ACR - Pilih, Booking, Main!">
    <meta name="keywords" content="futsal, booking lapangan, sewa lapangan futsal, FUSTAL ACR, booking online">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'FUSTAL ACR - Booking Lapangan Futsal Online')</title> <!-- Judul dinamis -->

    {{-- CSS --}}
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚽</text></svg>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



</head>

<body>
    @stack('styles')

    {{-- NAVBAR --}}
    @include('partials.pelanggan.navbar')

    {{-- PAGE CONTENT --}}
    @yield('content')

    {{-- FOOTER --}}
    @include('partials.pelanggan.footer')

    @stack('scripts')

    <script src="{{ asset('js/main.js') }}"></script>
    @stack('modals')

</body>

</html>