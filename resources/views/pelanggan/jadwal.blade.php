@extends('layouts.pelanggan')

@section('title', 'Cek Jadwal & Booking Lapangan')

@section('styles')

<style>
    .time-slots {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 14px;
    }



    .time-slot {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px;
        cursor: pointer;
        transition: .2s;
        background: #fff;
    }

    .time-slot .time {
        font-weight: 600;
        margin-bottom: 6px;
    }

    .time-slot .price {
        font-size: 13px;
        color: #6b7280;
    }

    .time-slot:hover {
        border-color: #3b82f6;
    }

    .time-slot.booked {
        background: #f3f4f6;
        color: #9ca3af;
        cursor: not-allowed;
    }

    .time-slot.booked .price {
        color: #9ca3af;
    }

    .time-slot.selected {
        background: #eff6ff;
        border-color: #3b82f6;
    }

    .time-slot.selected .price {
        color: #2563eb;
        font-weight: 600;
    }

    .booking-summary {
        position: sticky;
        top: 120px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #e5e7eb;
        font-size: 14px;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-row .label {
        color: #6b7280;
    }

    .summary-row .value {
        font-weight: 600;
    }

    .summary-row.total {
        margin-top: 12px;
        font-size: 16px;
    }

    .summary-row.total .value {
        color: #2563eb;
        font-weight: 700;
    }


    @media (max-width: 1024px) {
        .time-slots {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 640px) {
        .time-slots {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

@section('content')
@php
use Carbon\Carbon;
$today = Carbon::now()->toDateString();
@endphp

<!-- Page Header -->
<section style="background: linear-gradient(135deg,#0b1020 0%, #111827 100%); padding: 140px 0 60px;">
    <div class="container text-center text-white">
        <h1 style="color: #fff; margin-bottom: 16px;">Cek Jadwal & Booking</h1>
        <p style="color: rgba(255,255,255,0.75); max-width:600px; margin:0 auto;">Pilih lapangan dan tanggal untuk melihat jadwal yang tersedia</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 360px;gap:32px">

            <div>
                <div class="card p-xl mb-xl" style="padding:24px;">
                    <h3 style="margin-bottom:12px">Pilih Lapangan & Tanggal</h3>
                    <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:12px;align-items:end">
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Lapangan</label>
                            <select id="fieldSelect" class="form-control">
                                <option value="">-- Pilih Lapangan --</option>
                                @foreach($lapangans as $lap)
                                <option value="{{ $lap->id_lapangan }}">{{ $lap->nama_lapangan }} @if($lap->harga) - (Rp {{ number_format($lap->harga,0,',','.') }}/jam)@endif</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Tanggal</label>
                            <input type="date" id="dateSelect" class="form-control" min="{{ $today }}" value="{{ $today }}">
                        </div>

                        <button class="btn btn-primary" id="checkBtn">
                            <i class="fas fa-search"></i> Cek Jadwal
                        </button>

                    </div>
                </div>

                <div class="card p-xl" style="padding:24px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                        <div>
                            <h3 style="margin:0 0 6px 0">Jadwal Tersedia</h3>
                            <p style="color: #6b7280; font-size:13px; margin:0" id="scheduleInfo">Pilih lapangan & tanggal</p>
                        </div>

                        <div style="display:flex;gap:10px; font-size:13px; align-items:center">
                            <span><span style="display:inline-block;width:12px;height:12px;background:#10b981;border-radius:3px;margin-right:6px;vertical-align:middle"></span>Tersedia</span>
                            <span><span style="display:inline-block;width:12px;height:12px;background:#9ca3af;border-radius:3px;margin-right:6px;vertical-align:middle"></span>Dipesan</span>
                            <span><span style="display:inline-block;width:12px;height:12px;background:#3b82f6;border-radius:3px;margin-right:6px;vertical-align:middle"></span>Dipilih</span>
                        </div>
                    </div>

                    <div id="timeSlots" class="time-slots mt-lg">
                        <div style="text-align:center;color:#6b7280;padding:34px 0">Pilih lapangan & tanggal</div>
                    </div>

                    <div class="alert alert-warning mt-lg" style="background: rgba(255, 215, 0, 0.06); border-left:4px solid #f59e0b; margin-top:16px; padding:12px;">
                        <i class="fas fa-info-circle" style="margin-right:8px;color:#f59e0b"></i>
                        <span>Harga dapat berbeda pada jam sibuk (16:00 - 22:00) dan akhir pekan.</span>
                    </div>
                </div>
            </div>

            <div class="card booking-summary" style="padding:24px;">
                <h3 style="margin-bottom:16px">Ringkasan Booking</h3>

                <!-- EMPTY -->
                <div id="summaryEmpty" style="text-align:center;padding:32px 0;color:#6b7280">
                    <i class="fas fa-calendar-alt"
                        style="font-size:48px;margin-bottom:12px;opacity:.3"></i>
                    <p>Pilih jadwal untuk melihat ringkasan booking</p>
                </div>

                <!-- FILLED -->
                <div id="summaryFilled" style="display:none">
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

                    <button
                        id="bookingBtn"
                        class="btn btn-primary w-full mt-lg">
                        Booking
                    </button>

                </div>
            </div>


        </div>
    </div>
</section>

<script>
    let selectedSlots = [];
    let totalPrice = 0;

    const rupiah = n => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(n);


    document.getElementById('checkBtn').onclick = async () => {

        const field = fieldSelect.value;
        const date = dateSelect.value;

        if (!field || !date) return alert('Lengkapi pilihan');

        timeSlots.innerHTML = 'Loading...';
        selectedSlots = [];
        totalPrice = 0;
        resetSummary();

        const url = new URL("{{ route('pelanggan.jadwal.slots') }}", location.origin);
        url.searchParams.append('id_lapangan', field);
        url.searchParams.append('tanggal', date);

        const res = await fetch(url);
        const slots = await res.json();

        // update header info
        const fieldText = fieldSelect.options[fieldSelect.selectedIndex].text;
        document.getElementById('scheduleInfo').innerText = new Date(date).toLocaleDateString('id-ID') + ' - ' + fieldText;

        timeSlots.innerHTML = '';

        slots.forEach(s => {
            const el = document.createElement('div');
            el.className = 'time-slot ' + (s.status !== 'tersedia' ? 'booked' : '');
            el.innerHTML = `<div class="time">${s.jam}</div><div class="price">${rupiah(s.harga)}${s.status !== 'tersedia' ? '' : ''}</div>`;

            if (s.status === 'tersedia') {
                el.onclick = () => toggle(el, s);
            }

            timeSlots.appendChild(el);
        });
    };

    function toggle(el, s) {

        if (el.classList.contains('selected')) {
            el.classList.remove('selected');
            selectedSlots = selectedSlots.filter(x => x.id_jadwal !== s.id_jadwal);
            totalPrice -= s.harga;
            updateSummary();
            return;
        }

        el.classList.add('selected');
        selectedSlots.push(s);
        totalPrice += s.harga;
        updateSummary();
    }


    function updateSummary() {
        if (!selectedSlots.length) return resetSummary();

        document.getElementById('summaryEmpty').style.display = 'none';
        document.getElementById('summaryFilled').style.display = 'block';

        sumField.innerText = fieldSelect.options[fieldSelect.selectedIndex].text;
        sumDate.innerText = new Date(dateSelect.value).toLocaleDateString('id-ID');

        // Urutkan slot berdasarkan jam_mulai
        const slots = [...selectedSlots].sort((a, b) => a.jam_mulai.localeCompare(b.jam_mulai));

        // Gabungkan slot berurutan
        const merged = [];
        let current = null;

        for (const s of slots) {
            if (!current) {
                current = {
                    start: s.jam_mulai,
                    end: s.jam_selesai
                };
            } else {
                // jika s.jam_mulai == current.end → berurutan, gabungkan
                if (s.jam_mulai === current.end) {
                    current.end = s.jam_selesai;
                } else {
                    // tidak berurutan, simpan current dan mulai baru
                    merged.push(current);
                    current = {
                        start: s.jam_mulai,
                        end: s.jam_selesai
                    };
                }
            }
        }
        if (current) merged.push(current);

        // tampilkan ringkasan jam
        const times = merged.map(m => `${m.start} - ${m.end}`).join(', ');
        sumTime.innerText = times;

        const menit = selectedSlots.reduce((t, s) => t + s.durasi_menit, 0);
        sumDuration.innerText = menit + ' Menit';

        sumTotal.innerText = rupiah(totalPrice);
        form_jadwal_ids.value = selectedSlots.map(s => s.id_jadwal).join(',');
    }



    function resetSummary() {
        summaryEmpty.style.display = 'block';
        summaryFilled.style.display = 'none';
    }

    document.getElementById('bookingBtn').addEventListener('click', function() {

        if (!selectedSlots.length) {
            alert('Pilih slot terlebih dahulu');
            return;
        }

        fetch("{{ route('pelanggan.booking.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    jadwal_ids: selectedSlots.map(s => s.id_jadwal)
                })
            }).then(res => res.json())
            .then(data => {
                window.location.href =
                    "{{ url('/pelanggan/booking-confirm') }}/" + data.kode;
            });


    });
</script>

@endsection