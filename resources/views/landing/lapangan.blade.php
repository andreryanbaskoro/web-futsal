@extends('layouts.app')

@section('title', 'Booking Lapangan Futsal Online')

@section('content')
<!-- Page Header -->
<section class="page-header" style="background: var(--gradient-dark); padding: 140px 0 60px;">
    <div class="container text-center">
        <h1 style="color: var(--color-white); margin-bottom: var(--space-md);">Daftar Lapangan</h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">
            Pilih lapangan favorit Anda dari berbagai pilihan yang tersedia dengan fasilitas premium
        </p>
    </div>
</section>

<!-- Filter Section -->
<section style="padding: var(--space-xl) 0; background: var(--color-gray-100);">
    <div class="container">
        <div class="flex justify-between items-center" style="flex-wrap: wrap; gap: var(--space-md);">
            <div class="flex gap-md" style="flex-wrap: wrap;">
                <select class="form-control form-select" style="width: auto; min-width: 150px;">
                    <option value="">Semua Tipe</option>
                    <option value="vinyl">Vinyl</option>
                    <option value="rumput">Rumput Sintetis</option>
                    <option value="parquet">Parquet</option>
                </select>
                <select class="form-control form-select" style="width: auto; min-width: 150px;">
                    <option value="">Semua Status</option>
                    <option value="available">Tersedia</option>
                    <option value="limited">Hampir Penuh</option>
                </select>
            </div>
            <div class="flex gap-md items-center">
                <span style="color: var(--color-gray-600);">Urutkan:</span>
                <select class="form-control form-select" style="width: auto; min-width: 150px;">
                    <option value="popular">Paling Populer</option>
                    <option value="price-low">Harga Terendah</option>
                    <option value="price-high">Harga Tertinggi</option>
                    <option value="rating">Rating Tertinggi</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Fields List -->
<section class="section">
    <div class="container">
        <div class="grid grid-3">
            <!-- Field 1 -->
            <div class="card field-card">
                <div class="card-image">
                    <img src="https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=600&q=80"
                        alt="Lapangan A">
                    <span class="field-badge badge badge-success"><i class="fas fa-check-circle"></i>
                        Tersedia</span>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Lapangan A - Vinyl Premium</h3>
                    <div class="field-info">
                        <span><i class="fas fa-ruler-combined"></i> 25x15m</span>
                        <span><i class="fas fa-users"></i> 10-12 orang</span>
                        <span class="field-rating"><i class="fas fa-star"></i> 4.8 (120)</span>
                    </div>
                    <p class="card-text">Lapangan vinyl premium dengan kualitas terbaik, dilengkapi pencahayaan LED
                        dan AC full.</p>
                    <ul
                        style="margin-bottom: var(--space-md); font-size: var(--text-sm); color: var(--color-gray-600);">
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i> AC
                            & Ventilasi</li>
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i> LED
                            Lighting</li>
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Tribun Penonton</li>
                    </ul>
                    <div class="field-footer">
                        <div class="field-price">Rp 150.000 <span>/jam</span></div>
                        <a href="jadwal.html?field=1" class="btn btn-primary btn-sm">Booking</a>
                    </div>
                </div>
            </div>

            <!-- Field 2 -->
            <div class="card field-card">
                <div class="card-image">
                    <img src="https://images.unsplash.com/photo-1624880357913-a8539238245b?w=600&q=80"
                        alt="Lapangan B">
                    <span class="field-badge badge badge-success"><i class="fas fa-check-circle"></i>
                        Tersedia</span>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Lapangan B - Rumput Sintetis</h3>
                    <div class="field-info">
                        <span><i class="fas fa-ruler-combined"></i> 30x18m</span>
                        <span><i class="fas fa-users"></i> 14-16 orang</span>
                        <span class="field-rating"><i class="fas fa-star"></i> 4.9 (95)</span>
                    </div>
                    <p class="card-text">Lapangan rumput sintetis standar FIFA dengan ukuran luas untuk permainan
                        profesional.</p>
                    <ul
                        style="margin-bottom: var(--space-md); font-size: var(--text-sm); color: var(--color-gray-600);">
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Rumput FIFA Quality</li>
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i> LED
                            Lighting</li>
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Scoreboard Digital</li>
                    </ul>
                    <div class="field-footer">
                        <div class="field-price">Rp 200.000 <span>/jam</span></div>
                        <a href="jadwal.html?field=2" class="btn btn-primary btn-sm">Booking</a>
                    </div>
                </div>
            </div>

            <!-- Field 3 -->
            <div class="card field-card">
                <div class="card-image">
                    <img src="https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=600&q=80"
                        alt="Lapangan C">
                    <span class="field-badge badge badge-warning"><i class="fas fa-clock"></i> Hampir Penuh</span>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Lapangan C - Parquet Indoor</h3>
                    <div class="field-info">
                        <span><i class="fas fa-ruler-combined"></i> 25x15m</span>
                        <span><i class="fas fa-users"></i> 10-12 orang</span>
                        <span class="field-rating"><i class="fas fa-star"></i> 4.7 (80)</span>
                    </div>
                    <p class="card-text">Lapangan parquet indoor dengan suasana nyaman dan AC, cocok untuk latihan
                        rutin.</p>
                    <ul
                        style="margin-bottom: var(--space-md); font-size: var(--text-sm); color: var(--color-gray-600);">
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Full AC</li>
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Sound System</li>
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Locker Room</li>
                    </ul>
                    <div class="field-footer">
                        <div class="field-price">Rp 175.000 <span>/jam</span></div>
                        <a href="jadwal.html?field=3" class="btn btn-primary btn-sm">Booking</a>
                    </div>
                </div>
            </div>

            <!-- Field 4 -->
            <div class="card field-card">
                <div class="card-image">
                    <img src="https://images.unsplash.com/photo-1552667466-07770ae110d0?w=600&q=80"
                        alt="Lapangan D">
                    <span class="field-badge badge badge-success"><i class="fas fa-check-circle"></i>
                        Tersedia</span>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Lapangan D - Outdoor Premium</h3>
                    <div class="field-info">
                        <span><i class="fas fa-ruler-combined"></i> 35x20m</span>
                        <span><i class="fas fa-users"></i> 16-20 orang</span>
                        <span class="field-rating"><i class="fas fa-star"></i> 4.6 (65)</span>
                    </div>
                    <p class="card-text">Lapangan outdoor dengan rumput sintetis terbaik, cocok untuk turnamen dan
                        acara besar.</p>
                    <ul
                        style="margin-bottom: var(--space-md); font-size: var(--text-sm); color: var(--color-gray-600);">
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Ukuran Besar</li>
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Floodlight</li>
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Tribun 100 Orang</li>
                    </ul>
                    <div class="field-footer">
                        <div class="field-price">Rp 250.000 <span>/jam</span></div>
                        <a href="jadwal.html?field=4" class="btn btn-primary btn-sm">Booking</a>
                    </div>
                </div>
            </div>

            <!-- Field 5 -->
            <div class="card field-card">
                <div class="card-image">
                    <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=600&q=80"
                        alt="Lapangan E">
                    <span class="field-badge badge badge-success"><i class="fas fa-check-circle"></i>
                        Tersedia</span>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Lapangan E - Training Court</h3>
                    <div class="field-info">
                        <span><i class="fas fa-ruler-combined"></i> 20x12m</span>
                        <span><i class="fas fa-users"></i> 8-10 orang</span>
                        <span class="field-rating"><i class="fas fa-star"></i> 4.5 (45)</span>
                    </div>
                    <p class="card-text">Lapangan latihan dengan ukuran compact, cocok untuk training personal atau
                        tim kecil.</p>
                    <ul
                        style="margin-bottom: var(--space-md); font-size: var(--text-sm); color: var(--color-gray-600);">
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Training Equipment</li>
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Video Recording</li>
                        <li><i class="fas fa-check" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Coach Room</li>
                    </ul>
                    <div class="field-footer">
                        <div class="field-price">Rp 100.000 <span>/jam</span></div>
                        <a href="jadwal.html?field=5" class="btn btn-primary btn-sm">Booking</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection