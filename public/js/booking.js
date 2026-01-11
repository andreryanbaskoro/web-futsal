// =============================
// Booking Slot Logic
// =============================

// Selected slots storage
let selectedSlots = [];
let totalPrice = 0;

function selectSlot(element) {
    if (element.classList.contains('booked')) return;

    element.classList.toggle('selected');

    const time = element.dataset.time;
    const price = parseInt(element.dataset.price);

    if (element.classList.contains('selected')) {
        selectedSlots.push({ time, price });
        totalPrice += price;
    } else {
        const index = selectedSlots.findIndex(s => s.time === time);
        if (index > -1) {
            selectedSlots.splice(index, 1);
            totalPrice -= price;
        }
    }

    updateSummary();
}

function updateSummary() {
    const content = document.getElementById('summaryContent');
    const details = document.getElementById('summaryDetails');

    if (!content || !details) return;

    if (selectedSlots.length === 0) {
        content.style.display = 'block';
        details.style.display = 'none';
        return;
    }

    content.style.display = 'none';
    details.style.display = 'block';

    // Sort slots by time
    selectedSlots.sort((a, b) => a.time.localeCompare(b.time));

    const times = selectedSlots.map(s => s.time);
    const firstTime = times[0];
    const lastHour = parseInt(times[times.length - 1].split(':')[0]) + 1;
    const lastTime = `${lastHour.toString().padStart(2, '0')}:00`;

    document.getElementById('sumField').textContent = 'Lapangan A';
    document.getElementById('sumDate').textContent = '10 Januari 2026';
    document.getElementById('sumTime').textContent = `${firstTime} - ${lastTime}`;
    document.getElementById('sumDuration').textContent = `${selectedSlots.length} Jam`;
    document.getElementById('sumTotal').textContent = formatCurrency(totalPrice);
}

function checkSchedule() {
    const field = document.getElementById('fieldSelect')?.value;
    const date = document.getElementById('dateSelect')?.value;

    if (!field || !date) {
        alert('Silakan pilih lapangan dan tanggal terlebih dahulu');
        return;
    }

    alert('Jadwal berhasil dimuat!');
}

// Set minimum date to today
document.addEventListener('DOMContentLoaded', () => {
    const dateInput = document.getElementById('dateSelect');
    if (dateInput) {
        dateInput.valueAsDate = new Date();
    }
});
