@extends('layouts.pelanggan')

@section('title', 'Cek Jadwal & Booking Lapangan')

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
                            <span>
                                <span style="display:inline-block;width:12px;height:12px;background:#10b981;border-radius:3px;margin-right:6px;vertical-align:middle"></span>
                                Tersedia
                            </span>
                            <span>
                                <span style="display:inline-block;width:12px;height:12px;background:#9ca3af;border-radius:3px;margin-right:6px;vertical-align:middle"></span>
                                Dipesan
                            </span>
                            <span>
                                <span style="display:inline-block;width:12px;height:12px;background:#3b82f6;border-radius:3px;margin-right:6px;vertical-align:middle"></span>
                                Dipilih
                            </span>
                            <span>
                                <span style="display:inline-block;width:12px;height:12px;background:#9ca3af;border-radius:3px;margin-right:6px;vertical-align:middle"></span>
                                Waktu Lewat
                            </span>

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
    function hitungDurasiMenit(jamMulai, jamSelesai) {
        const [mh, mm] = jamMulai.split(':').map(Number);
        const [sh, sm] = jamSelesai.split(':').map(Number);

        let start = mh * 60 + mm;
        let end = sh * 60 + sm;

        // lewat tengah malam
        if (end <= start) {
            end += 24 * 60;
        }

        return end - start;
    }

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

        if (!field || !date) {
            alert('Lengkapi pilihan');
            return;
        }

        timeSlots.innerHTML = 'Loading...'; // Menampilkan loading saat menunggu data
        selectedSlots = [];
        totalPrice = 0;
        resetSummary();

        const url = new URL("{{ route('pelanggan.jadwal.slots') }}", location.origin);
        url.searchParams.append('id_lapangan', field);
        url.searchParams.append('tanggal', date);

        const res = await fetch(url);
        const slots = await res.json();

        const fieldText = fieldSelect.options[fieldSelect.selectedIndex].text;
        scheduleInfo.innerText = new Date(date).toLocaleDateString('id-ID') + ' - ' + fieldText;

        timeSlots.innerHTML = ''; // Reset tampilan slots

        if (!slots.length) {
            timeSlots.innerHTML = '<div class="text-muted">Tidak ada jadwal</div>';
            return;
        }

        // Mengecek apakah slot sudah ada di selectedSlots sebelum menambahkannya
        slots.forEach(s => {
            const existingSlot = selectedSlots.find(slot => slot.id_jadwal === s.id_jadwal);
            if (existingSlot) {
                return; // Jangan menambahkan slot yang sama
            }

            const el = document.createElement('div');
            el.classList.add('time-slot');

            el.innerHTML = `
        <div class="time">${s.jam}</div>
        <div class="price">${rupiah(s.harga)}</div>
    `;

            // Mark slot as booked
            if (s.booked) {
                el.classList.add('booked');
                el.innerHTML += `
            <div style="margin-top:6px; font-size:11px; color:#ef4444; font-weight:600;">
                <i class="fas fa-times-circle" style="color:#ef4444; margin-right:5px;"></i>Sudah dipesan
            </div>
        `;
            }
            // Mark slot as past
            else if (s.past) {
                el.classList.add('past');
                el.innerHTML += `
            <div style="margin-top:6px; font-size:11px; color:#9ca3af; font-weight:600;">
                <i class="fas fa-clock" style="color:#9ca3af; margin-right:5px;"></i>Waktu Lewat
            </div>
        `;
            }
            // Available slot
            // Available slot
            else {
                el.classList.add('available');
                el.innerHTML += `
        <div class="available-text" style="margin-top:6px; font-size:11px; color:#10b981; font-weight:600;">
            <i class="fas fa-check-circle" style="color:#10b981; margin-right:5px;"></i>Tersedia
        </div>
    `;

                // Jika sudah dipilih, ganti teks menjadi "Dipilih"
                if (existingSlot) {
                    const availableText = el.querySelector('.available-text');
                    if (availableText) {
                        availableText.innerText = 'Dipilih';
                        availableText.style.color = '#3b82f6'; // Warna biru untuk status "Dipilih"
                    }
                }

                el.addEventListener('click', () => toggle(el, s));
            }


            timeSlots.appendChild(el);
        });

    };



    function toggle(el, s) {
        if (s.booked || s.past) return;

        const key = s.jam_mulai;
        const availableText = el.querySelector('.available-text');

        const index = selectedSlots.findIndex(x => x.jam_mulai === key);

        // UNSELECT
        if (index !== -1) {
            el.classList.remove('selected');
            totalPrice -= selectedSlots[index].harga;
            selectedSlots.splice(index, 1);

            availableText.innerHTML = `
            <i class="fas fa-check-circle"></i> Tersedia
        `;
            availableText.style.color = '#10b981';

            updateSummary();
            return;
        }

        // SELECT
        const durasi = hitungDurasiMenit(s.jam_mulai, s.jam_selesai);

        el.classList.add('selected');
        selectedSlots.push({
            ...s,
            durasi_menit: durasi
        });

        totalPrice += s.harga;

        availableText.innerHTML = `
        <i class="fas fa-circle"></i> Dipilih
    `;
        availableText.style.color = '#3b82f6';

        updateSummary();
    }




    function updateSummary() {
        if (!selectedSlots.length) {
            resetSummary();
            return;
        }

        summaryEmpty.style.display = 'none';
        summaryFilled.style.display = 'block';

        sumField.innerText = fieldSelect.options[fieldSelect.selectedIndex].text;
        sumDate.innerText = new Date(dateSelect.value).toLocaleDateString('id-ID');

        const slots = [...selectedSlots].sort((a, b) =>
            a.jam_mulai.localeCompare(b.jam_mulai)
        );

        sumTime.innerText = slots.map(s => s.jam).join(', ');
        const menit = selectedSlots.reduce((t, s) => t + s.durasi_menit, 0);
        sumDuration.innerText = menit + ' Menit';
        sumTotal.innerText = rupiah(totalPrice);
    }


    function resetSummary() {
        summaryEmpty.style.display = 'block';
        summaryFilled.style.display = 'none';
    }

    document.getElementById('bookingBtn').onclick = async () => {

        if (!selectedSlots.length) {
            alert('Pilih slot terlebih dahulu');
            return;
        }

        try {
            const res = await fetch("{{ route('pelanggan.booking.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id_lapangan: fieldSelect.value,
                    tanggal: dateSelect.value,
                    slots: selectedSlots.map(s => ({
                        jam_mulai: s.jam_mulai,
                        jam_selesai: s.jam_selesai,
                        harga: s.harga,
                        durasi_menit: s.durasi_menit // ⬅️ PENTING
                    }))
                })


            });

            const data = await res.json();

            if (!res.ok) {
                // tunjukkan pesan error dari server jika ada
                alert(data.error || 'Terjadi kesalahan saat booking');
                return;
            }

            if (!data.kode) {
                alert('Response server tidak mengembalikan kode pemesanan. Silakan coba lagi.');
                return;
            }

            window.location.href = "{{ url('/pelanggan/booking-confirm') }}/" + data.kode;
        } catch (err) {
            console.error(err);
            alert('Gagal menghubungi server. Periksa koneksi Anda.');
        }
    };
</script>



@endsection