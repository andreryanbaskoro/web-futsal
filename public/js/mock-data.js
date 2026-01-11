/**
 * FUSTAL ACR - Mock Data
 * Sample data for demo purposes
 */

const MOCK_DATA = {
    // Fields/Lapangan
    fields: [
        {
            id: 1,
            name: "Lapangan A",
            type: "Vinyl Premium",
            size: "25x15m",
            capacity: "10-12 orang",
            rating: 4.8,
            reviews: 120,
            pricePerHour: 150000,
            priceWeekend: 175000,
            pricePeakHour: 175000,
            status: "available",
            features: ["AC & Ventilasi", "LED Lighting", "Tribun Penonton"],
            image: "https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=600&q=80",
        },
        {
            id: 2,
            name: "Lapangan B",
            type: "Rumput Sintetis",
            size: "30x18m",
            capacity: "14-16 orang",
            rating: 4.9,
            reviews: 95,
            pricePerHour: 200000,
            priceWeekend: 225000,
            pricePeakHour: 225000,
            status: "available",
            features: [
                "Rumput FIFA Quality",
                "LED Lighting",
                "Scoreboard Digital",
            ],
            image: "https://images.unsplash.com/photo-1624880357913-a8539238245b?w=600&q=80",
        },
        {
            id: 3,
            name: "Lapangan C",
            type: "Parquet Indoor",
            size: "25x15m",
            capacity: "10-12 orang",
            rating: 4.7,
            reviews: 80,
            pricePerHour: 175000,
            priceWeekend: 200000,
            pricePeakHour: 200000,
            status: "limited",
            features: ["Full AC", "Sound System", "Locker Room"],
            image: "https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=600&q=80",
        },
        {
            id: 4,
            name: "Lapangan D",
            type: "Outdoor Premium",
            size: "35x20m",
            capacity: "16-20 orang",
            rating: 4.6,
            reviews: 65,
            pricePerHour: 250000,
            priceWeekend: 300000,
            pricePeakHour: 275000,
            status: "available",
            features: ["Ukuran Besar", "Floodlight", "Tribun 100 Orang"],
            image: "https://images.unsplash.com/photo-1552667466-07770ae110d0?w=600&q=80",
        },
        {
            id: 5,
            name: "Lapangan E",
            type: "Training Court",
            size: "20x12m",
            capacity: "8-10 orang",
            rating: 4.5,
            reviews: 45,
            pricePerHour: 100000,
            priceWeekend: 125000,
            pricePeakHour: 125000,
            status: "available",
            features: ["Training Equipment", "Video Recording", "Coach Room"],
            image: "https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=600&q=80",
        },
    ],

    // Time Slots Template
    timeSlots: [
        { time: "08:00", endTime: "09:00", type: "regular" },
        { time: "09:00", endTime: "10:00", type: "regular" },
        { time: "10:00", endTime: "11:00", type: "regular" },
        { time: "11:00", endTime: "12:00", type: "regular" },
        { time: "12:00", endTime: "13:00", type: "regular" },
        { time: "13:00", endTime: "14:00", type: "regular" },
        { time: "14:00", endTime: "15:00", type: "regular" },
        { time: "15:00", endTime: "16:00", type: "regular" },
        { time: "16:00", endTime: "17:00", type: "peak" },
        { time: "17:00", endTime: "18:00", type: "peak" },
        { time: "18:00", endTime: "19:00", type: "peak" },
        { time: "19:00", endTime: "20:00", type: "peak" },
        { time: "20:00", endTime: "21:00", type: "peak" },
        { time: "21:00", endTime: "22:00", type: "peak" },
        { time: "22:00", endTime: "23:00", type: "regular" },
        { time: "23:00", endTime: "00:00", type: "regular" },
    ],

    // Sample Bookings
    bookings: [
        {
            id: "FTS-2026011001",
            fieldId: 1,
            fieldName: "Lapangan A - Vinyl Premium",
            date: "2026-01-10",
            startTime: "08:00",
            endTime: "10:00",
            duration: 2,
            totalPrice: 302500,
            status: "paid",
            createdAt: "2026-01-09T10:30:00",
        },
        {
            id: "FTS-2026010901",
            fieldId: 2,
            fieldName: "Lapangan B - Rumput Sintetis",
            date: "2026-01-12",
            startTime: "19:00",
            endTime: "21:00",
            duration: 2,
            totalPrice: 402500,
            status: "pending",
            expiredAt: "2026-01-10T20:30:00",
            createdAt: "2026-01-09T19:30:00",
        },
        {
            id: "FTS-2026010501",
            fieldId: 3,
            fieldName: "Lapangan C - Parquet Indoor",
            date: "2026-01-05",
            startTime: "15:00",
            endTime: "16:00",
            duration: 1,
            totalPrice: 177500,
            status: "expired",
            createdAt: "2026-01-05T14:00:00",
        },
    ],

    // Testimonials
    testimonials: [
        {
            id: 1,
            name: "Ahmad Fadillah",
            role: "Tim Futsal Garuda FC",
            avatar: "https://i.pravatar.cc/100?img=11",
            rating: 5,
            quote: "Lapangan bersih, rumput bagus, dan booking sangat mudah! Gak perlu telepon-telepon, langsung booking dari HP. Recommended banget untuk yang mau main futsal bareng teman!",
        },
        {
            id: 2,
            name: "Budi Santoso",
            role: "Tim Futsal Elang Jaya",
            avatar: "https://i.pravatar.cc/100?img=12",
            rating: 5,
            quote: "Sudah 2 tahun langganan di sini. Pelayanannya ramah, lapangannya terawat, dan yang paling penting booking-nya gampang banget. Tinggal pilih, bayar, selesai!",
        },
        {
            id: 3,
            name: "Reza Pratama",
            role: "Anggota Komunitas Futsal",
            avatar: "https://i.pravatar.cc/100?img=13",
            rating: 5,
            quote: "Fasilitasnya lengkap, ada ruang ganti, kamar mandi bersih, dan parkir luas. Harga juga terjangkau untuk kualitas lapangan sebagus ini. Top!",
        },
    ],

    // Blog Articles
    articles: [
        {
            id: 1,
            title: "5 Tips Pemanasan Sebelum Main Futsal",
            slug: "tips-pemanasan-futsal",
            excerpt:
                "Pemanasan yang benar sangat penting untuk mencegah cedera saat bermain futsal...",
            category: "Tips",
            author: "Admin",
            date: "2026-01-10",
            readTime: "5 menit",
            image: "https://images.unsplash.com/photo-1599058917212-d750089bc07e?w=600&q=80",
        },
        {
            id: 2,
            title: "Cara Memilih Sepatu Futsal yang Tepat",
            slug: "memilih-sepatu-futsal",
            excerpt:
                "Sepatu futsal yang tepat dapat meningkatkan performa permainan Anda...",
            category: "Gear",
            author: "Admin",
            date: "2026-01-08",
            readTime: "7 menit",
            image: "https://images.unsplash.com/photo-1511886929837-354d827aae26?w=600&q=80",
        },
        {
            id: 3,
            title: "Strategi Formasi Futsal untuk Pemula",
            slug: "strategi-formasi-futsal",
            excerpt:
                "Mengenal berbagai formasi futsal yang efektif untuk tim pemula...",
            category: "Strategi",
            author: "Admin",
            date: "2026-01-05",
            readTime: "8 menit",
            image: "https://images.unsplash.com/photo-1517466787929-bc90951d0974?w=600&q=80",
        },
    ],

    // Payment Methods
    paymentMethods: [
        {
            id: "bca",
            name: "BCA Virtual Account",
            type: "bank",
            icon: "BCA",
            color: "#0066AE",
        },
        {
            id: "mandiri",
            name: "Mandiri Virtual Account",
            type: "bank",
            icon: "MDR",
            color: "#003876",
        },
        {
            id: "bni",
            name: "BNI Virtual Account",
            type: "bank",
            icon: "BNI",
            color: "#F15A22",
        },
        {
            id: "qris",
            name: "QRIS",
            type: "ewallet",
            icon: "fas fa-qrcode",
            color: "#1DB954",
        },
        {
            id: "gopay",
            name: "GoPay",
            type: "ewallet",
            icon: "fas fa-wallet",
            color: "#00AED6",
        },
        {
            id: "ovo",
            name: "OVO",
            type: "ewallet",
            icon: "fas fa-wallet",
            color: "#4C3494",
        },
    ],

    // Booking Statuses
    bookingStatuses: {
        pending: {
            label: "Menunggu Pembayaran",
            color: "warning",
            icon: "fas fa-clock",
        },
        paid: { label: "Lunas", color: "success", icon: "fas fa-check-circle" },
        expired: {
            label: "Kadaluarsa",
            color: "error",
            icon: "fas fa-times-circle",
        },
        cancelled: { label: "Dibatalkan", color: "error", icon: "fas fa-ban" },
        completed: {
            label: "Selesai",
            color: "success",
            icon: "fas fa-check-circle",
        },
    },
};

// Helper Functions
const formatCurrency = (amount) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(amount);
};

const formatDate = (dateString) => {
    return new Intl.DateTimeFormat("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    }).format(new Date(dateString));
};

const formatShortDate = (dateString) => {
    return new Intl.DateTimeFormat("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
    }).format(new Date(dateString));
};

// Export for use
if (typeof module !== "undefined" && module.exports) {
    module.exports = MOCK_DATA;
}
