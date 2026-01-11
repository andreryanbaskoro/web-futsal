@extends('layouts.app')

@section('title', 'Booking Lapangan Futsal Online')

@section('content')
<!-- Page Header -->
<section style="background: var(--gradient-dark); padding: 140px 0 60px;">
    <div class="container text-center">
        <h1 style="color: var(--color-white); margin-bottom: var(--space-md);">Cek Jadwal & Booking</h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">
            Pilih lapangan dan tanggal untuk melihat jadwal yang tersedia
        </p>
    </div>
</section>

<!-- Booking Section -->
<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 350px; gap: var(--space-2xl);">
            <!-- Main Content -->
            <div>
                <!-- Search Form -->
                <div class="card" style="padding: var(--space-xl); margin-bottom: var(--space-xl);">
                    <h3 style="margin-bottom: var(--space-lg);">Pilih Lapangan & Tanggal</h3>
                    <div
                        style="display: grid; grid-template-columns: 1fr 1fr auto; gap: var(--space-md); align-items: end;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Lapangan</label>
                            <select class="form-control form-select" id="fieldSelect">
                                <option value="">Pilih Lapangan</option>
                                <option value="1">Lapangan A - Vinyl Premium (Rp 150.000/jam)</option>
                                <option value="2">Lapangan B - Rumput Sintetis (Rp 200.000/jam)</option>
                                <option value="3">Lapangan C - Parquet Indoor (Rp 175.000/jam)</option>
                                <option value="4">Lapangan D - Outdoor Premium (Rp 250.000/jam)</option>
                                <option value="5">Lapangan E - Training Court (Rp 100.000/jam)</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="dateSelect" min="2026-01-10">
                        </div>
                        <button class="btn btn-primary" onclick="checkSchedule()">
                            <i class="fas fa-search"></i> Cek Jadwal
                        </button>
                    </div>
                </div>

                <!-- Schedule Result -->
                <div id="scheduleResult">
                    <div class="card" style="padding: var(--space-xl);">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg);">
                            <div>
                                <h3 style="margin-bottom: var(--space-sm);">Jadwal Tersedia</h3>
                                <p style="color: var(--color-gray-600); font-size: var(--text-sm);">
                                    <i class="fas fa-calendar"></i> Sabtu, 10 Januari 2026 - Lapangan A
                                </p>
                            </div>
                            <div style="display: flex; gap: var(--space-md); font-size: var(--text-sm);">
                                <span><span
                                        style="display: inline-block; width: 12px; height: 12px; background: var(--color-success); border-radius: 3px; margin-right: 4px;"></span>
                                    Tersedia</span>
                                <span><span
                                        style="display: inline-block; width: 12px; height: 12px; background: var(--color-gray-400); border-radius: 3px; margin-right: 4px;"></span>
                                    Dipesan</span>
                                <span><span
                                        style="display: inline-block; width: 12px; height: 12px; background: var(--color-primary); border-radius: 3px; margin-right: 4px;"></span>
                                    Dipilih</span>
                            </div>
                        </div>

                        <!-- Time Slots -->
                        <div class="time-slots" id="timeSlots">
                            <div class="time-slot" data-time="08:00" data-price="150000" onclick="selectSlot(this)">
                                <div class="time">08:00 - 09:00</div>
                                <div class="price">Rp 150.000</div>
                            </div>
                            <div class="time-slot booked">
                                <div class="time">09:00 - 10:00</div>
                                <div class="price"><i class="fas fa-lock"></i> Dipesan</div>
                            </div>
                            <div class="time-slot" data-time="10:00" data-price="150000" onclick="selectSlot(this)">
                                <div class="time">10:00 - 11:00</div>
                                <div class="price">Rp 150.000</div>
                            </div>
                            <div class="time-slot" data-time="11:00" data-price="150000" onclick="selectSlot(this)">
                                <div class="time">11:00 - 12:00</div>
                                <div class="price">Rp 150.000</div>
                            </div>
                            <div class="time-slot booked">
                                <div class="time">12:00 - 13:00</div>
                                <div class="price"><i class="fas fa-lock"></i> Dipesan</div>
                            </div>
                            <div class="time-slot booked">
                                <div class="time">13:00 - 14:00</div>
                                <div class="price"><i class="fas fa-lock"></i> Dipesan</div>
                            </div>
                            <div class="time-slot" data-time="14:00" data-price="150000" onclick="selectSlot(this)">
                                <div class="time">14:00 - 15:00</div>
                                <div class="price">Rp 150.000</div>
                            </div>
                            <div class="time-slot" data-time="15:00" data-price="150000" onclick="selectSlot(this)">
                                <div class="time">15:00 - 16:00</div>
                                <div class="price">Rp 150.000</div>
                            </div>
                            <div class="time-slot" data-time="16:00" data-price="175000" onclick="selectSlot(this)">
                                <div class="time">16:00 - 17:00</div>
                                <div class="price">Rp 175.000</div>
                            </div>
                            <div class="time-slot booked">
                                <div class="time">17:00 - 18:00</div>
                                <div class="price"><i class="fas fa-lock"></i> Dipesan</div>
                            </div>
                            <div class="time-slot" data-time="18:00" data-price="200000" onclick="selectSlot(this)">
                                <div class="time">18:00 - 19:00</div>
                                <div class="price">Rp 200.000</div>
                            </div>
                            <div class="time-slot" data-time="19:00" data-price="200000" onclick="selectSlot(this)">
                                <div class="time">19:00 - 20:00</div>
                                <div class="price">Rp 200.000</div>
                            </div>
                            <div class="time-slot booked">
                                <div class="time">20:00 - 21:00</div>
                                <div class="price"><i class="fas fa-lock"></i> Dipesan</div>
                            </div>
                            <div class="time-slot" data-time="21:00" data-price="200000" onclick="selectSlot(this)">
                                <div class="time">21:00 - 22:00</div>
                                <div class="price">Rp 200.000</div>
                            </div>
                            <div class="time-slot" data-time="22:00" data-price="175000" onclick="selectSlot(this)">
                                <div class="time">22:00 - 23:00</div>
                                <div class="price">Rp 175.000</div>
                            </div>
                        </div>

                        <div class="alert alert-warning mt-lg"
                            style="background: rgba(255, 215, 0, 0.1); border-left-color: var(--color-accent);">
                            <i class="fas fa-info-circle" style="color: var(--color-accent);"></i>
                            <span>Harga dapat berbeda pada jam sibuk (16:00 - 22:00) dan akhir pekan.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Summary Sidebar -->
            <div>
                <div class="booking-summary" id="bookingSummary">
                    <h3>Ringkasan Booking</h3>
                    <div id="summaryContent">
                        <div style="text-align: center; padding: var(--space-xl) 0; color: var(--color-gray-600);">
                            <i class="fas fa-calendar-alt"
                                style="font-size: 48px; margin-bottom: var(--space-md); opacity: 0.3;"></i>
                            <p>Pilih jadwal untuk melihat ringkasan booking</p>
                        </div>
                    </div>
                    <div id="summaryDetails" style="display: none;">
                        <div class="summary-row">
                            <span class="label">Lapangan</span>
                            <span class="value" id="sumField">-</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Tanggal</span>
                            <span class="value" id="sumDate">-</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Jam</span>
                            <span class="value" id="sumTime">-</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Durasi</span>
                            <span class="value" id="sumDuration">-</span>
                        </div>
                        <div class="summary-row total">
                            <span class="label">Total</span>
                            <span class="value" id="sumTotal">Rp 0</span>
                        </div>
                        <a href="login.html" class="btn btn-primary w-full mt-lg" id="bookingBtn">
                            <i class="fas fa-lock"></i> Login untuk Booking
                        </a>
                        <p
                            style="text-align: center; font-size: var(--text-sm); color: var(--color-gray-600); margin-top: var(--space-md);">
                            Belum punya akun? <a href="register.html" style="color: var(--color-primary);">Daftar di
                                sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('js/booking.js') }}"></script>
@endpush