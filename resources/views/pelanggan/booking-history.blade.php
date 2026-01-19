@extends('layouts.pelanggan')

@section('title', 'Riwayat Pemesanan - Futsal ACR')


@section('content')
<!-- Page Header -->
<section style="background: linear-gradient(135deg,#0b1020 0%, #111827 100%); padding: 80px 0 40px;">
    <div class="container text-center text-white">
        <h1 style="color: #fff; margin-bottom: 8px;">Riwayat Pemesanan</h1>
        <p style="color: rgba(255,255,255,0.75); max-width:600px; margin:0 auto;">Lihat riwayat pemesanan Anda di sini. Pilih lapangan dan tanggal untuk melihat jadwal yang tersedia.</p>
    </div>
</section>


<section style="padding: 40px 0 40px; background: var(--color-gray-100); min-height: 100vh;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 280px 1fr; gap: var(--space-2xl);">
            <!-- User Sidebar -->
            <div>
                <div class="user-sidebar" style="background: var(--color-white); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); padding: var(--space-xl); position: sticky; top: 100px;">
                    <!-- Ganti gambar dengan foto profil pengguna -->
                    <img src="https://i.pravatar.cc/150?img={{ auth()->user()->id }}" alt="User" class="user-avatar" style="width: 80px; height: 80px; border-radius: 50%; margin: 0 auto var(--space-md); display: block;">

                    <!-- Tampilkan Nama Lengkap dan Email -->
                    <h3 class="user-name" style="text-align: center; font-weight: 600; margin-bottom: 4px;">{{ auth()->user()->nama }}</h3>
                    <p class="user-email" style="text-align: center; font-size: var(--text-sm); color: var(--color-gray-600); margin-bottom: var(--space-lg);">{{ auth()->user()->email }}</p>

                    <!-- Menu Sidebar -->
                    <div class="user-menu" style="border-top: 1px solid var(--color-gray-200); padding-top: var(--space-lg);">
                        <a href="{{ route('pelanggan.booking.history') }}" class="active" style="display: flex; align-items: center; gap: var(--space-md); padding: var(--space-md); border-radius: var(--radius-md); color: var(--color-gray-700); transition: var(--transition-base); background: rgba(29, 185, 84, 0.1); color: var(--color-primary);">
                            <i class="fas fa-history" style="width: 20px; text-align: center;"></i> Riwayat Booking
                        </a>
                        <a href="#" style="display: flex; align-items: center; gap: var(--space-md); padding: var(--space-md); border-radius: var(--radius-md); color: var(--color-gray-700); transition: var(--transition-base);">
                            <i class="fas fa-user" style="width: 20px; text-align: center;"></i> Profil Saya
                        </a>
                        <a href="{{ route('logout') }}" style="color: var(--color-error); display: flex; align-items: center; gap: var(--space-md); padding: var(--space-md); border-radius: var(--radius-md); transition: var(--transition-base);">
                            <i class="fas fa-sign-out-alt" style="width: 20px; text-align: center;"></i> Keluar
                        </a>
                    </div>
                </div>
            </div>


            <!-- Main Content -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl); flex-wrap: wrap; gap: var(--space-md);">
                    <h2>Riwayat Pemesanan</h2>
                    <div style="display: flex; gap: var(--space-md);">
                        <select class="form-control form-select" style="width: auto;">
                            <option value="">Semua Status</option>
                            <option value="paid">Lunas</option>
                            <option value="pending">Pending</option>
                            <option value="expired">Kadaluarsa</option>
                        </select>
                        <select class="form-control form-select" style="width: auto;">
                            <option value="">Bulan Ini</option>
                            <option value="last">Bulan Lalu</option>
                            <option value="all">Semua</option>
                        </select>
                    </div>
                </div>

                <!-- Booking - Paid -->
                @if($bookings->isEmpty())
                <div class="empty-booking" style="text-align: center; padding: var(--space-lg); color: var(--color-gray-600);">
                    <i class="fas fa-exclamation-circle" style="font-size: 2em;"></i>
                    <h3 style="margin-top: var(--space-md); font-weight: 600;">Tidak ada Riwayat Pemesanan</h3>
                    <p>Anda belum melakukan pemesanan apapun. Ayo lakukan pemesanan sekarang!</p>
                    <a href="{{ route('pelanggan.lapangan') }}" class="btn btn-primary" style="background: var(--color-primary); color: var(--color-white); margin-top: var(--space-md);">
                        Lihat Lapangan
                    </a>
                </div>
                @else
                @foreach($bookings as $pemesanan)
                @php
                $details = $pemesanan->detailJadwal;

                // lapangan (aman untuk pending & paid)
                $lapangan = optional($details->first()?->jadwal)->lapangan ?? $pemesanan->lapangan ?? null;

                // tanggal (ambil dari detail)
                $tanggal = $details->first()?->tanggal;

                // total durasi
                $totalDurasi = $details->sum('durasi_menit');

                // list jam
                $jamList = [];
                @endphp

                @foreach ($details as $d)
                @php
                $mulai = substr($d->jadwal->jam_mulai ?? $d->jam_mulai, 0, 5);
                $selesai = substr($d->jadwal->jam_selesai ?? $d->jam_selesai, 0, 5);
                $jamList[] = $mulai . ' - ' . $selesai;
                @endphp
                @endforeach



                <div class="booking-card" style="background: var(--color-white); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; margin-bottom: var(--space-lg);">
                    <div class="booking-card-header" style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-lg); border-bottom: 1px solid var(--color-gray-200);">
                        <span class="booking-code" style="font-family: var(--font-primary); font-weight: 600;">{{ $pemesanan->kode_pemesanan }}</span>
                        <span class="badge badge-success" style="background: var(--color-success); color: var(--color-white); padding: 0.2em 0.5em; border-radius: var(--radius-md);">
                            <i class="fas fa-check-circle"></i>
                            {{ ucfirst($pemesanan->status_pemesanan) }}
                        </span>
                    </div>


                    <div class="booking-card-body" style="padding: var(--space-lg); display: grid; grid-template-columns: auto 1fr auto; gap: var(--space-lg); align-items: center;">
                        <img src="{{ $lapangan->image_url ?? 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=200&q=80' }}"
                            alt="Lapangan"
                            class="booking-image"
                            style="width: 100px; height: 75px; border-radius: var(--radius-md); object-fit: cover;">

                        <div class="booking-details">
                            <h4 style="margin-bottom: var(--space-sm);">
                                {{ $lapangan->nama_lapangan ?? 'Lapangan tidak tersedia' }}
                                <span style="font-size: 13px; color: #6b7280;">
                                    ({{ $lapangan->dimensi ?? '-' }}, {{ $lapangan->kapasitas ?? '-' }})
                                </span>
                            </h4>

                            <div class="booking-meta" style="display: flex; flex-wrap: wrap; gap: var(--space-md); font-size: var(--text-sm); color: var(--color-gray-600);">
                                <span style="display: flex; align-items: center; gap: 4px;">
                                    <i class="fas fa-calendar" style="color: var(--color-primary);"></i>
                                    {{ $tanggal ? \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') : '-' }}
                                </span>

                                <span style="display: flex; align-items: center; gap: 4px;">
                                    <i class="fas fa-clock" style="color: var(--color-primary);"></i>
                                    {{ implode(', ', $jamList) }}
                                </span>

                                <span style="display: flex; align-items: center; gap: 4px;">
                                    <i class="fas fa-stopwatch" style="color: var(--color-primary);"></i>
                                    {{ $totalDurasi }} Menit
                                </span>

                            </div>
                            <div style="margin-top: var(--space-md);">
                                <span style="font-weight: 600; color: var(--color-primary);">
                                    Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="booking-actions" style="display: flex; flex-direction: column; gap: var(--space-sm);">
                            <a href="{{ route('pelanggan.booking.confirm', $pemesanan->kode_pemesanan) }}" class="btn btn-outline btn-sm" style="border-color: var(--color-primary); color: var(--color-primary);">Lihat Detail</a>
                            <a href="{{ route('pelanggan.lapangan') }}" class="btn btn-primary btn-sm" style="background: var(--color-primary); color: var(--color-white);">Booking Lagi</a>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</section>


@endsection