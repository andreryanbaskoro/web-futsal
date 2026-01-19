<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Landing\LandingController;


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::middleware('guest')->group(function () {

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/password/reset', [ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'send'])->name('password.email');


    // Menampilkan form verifikasi dengan email
    Route::get('/verify/{email}', [VerificationController::class, 'show'])->name('verification.show');

    // Memproses kode verifikasi
    Route::post('/verify/{email}', [VerificationController::class, 'verify'])->name('verification.verify');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


/*
|--------------------------------------------------------------------------
| LANDING PAGE (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'beranda'])->name('beranda');
Route::get('/lapangan', [LandingController::class, 'lapangan'])->name('lapangan');
Route::get('/jadwal', [LandingController::class, 'jadwal'])->name('jadwal');
Route::get('/galeri', [LandingController::class, 'galeri'])->name('galeri');
Route::get('/blog', [LandingController::class, 'blog'])->name('blog');
Route::get('/blog-detail', [LandingController::class, 'blogDetail'])->name('blog.detail');
Route::get('/kontak', [LandingController::class, 'kontak'])->name('kontak');
Route::get('/syarat', [LandingController::class, 'syarat'])->name('syarat');
Route::get('/kebijakan', [LandingController::class, 'kebijakan'])->name('kebijakan');
Route::get('/faq', [LandingController::class, 'faq'])->name('faq');
Route::get('/tentang', [LandingController::class, 'tentang'])->name('tentang');


/*
|--------------------------------------------------------------------------
| ADMIN (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\{
    DashboardController,
    LapanganController,
    JamOperasionalController,
    JadwalController,
    PemesananController,
    PembayaranController,
    LaporanController,
    UserController
};

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ================= DASHBOARD =================
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // ================= MASTER DATA =================
        Route::resource('lapangan', LapanganController::class);
        Route::resource('jam-operasional', JamOperasionalController::class)
            ->except(['show']);

        // ================= JADWAL =================
        Route::get('jadwal', [JadwalController::class, 'index'])
            ->name('jadwal.index');

        Route::post('jadwal/generate', [JadwalController::class, 'generate'])
            ->name('jadwal.generate');

        Route::delete('jadwal/{jadwal}', [JadwalController::class, 'destroy'])
            ->name('jadwal.destroy');

        // ================= PEMESANAN =================
        Route::get('pemesanan', [PemesananController::class, 'index'])
            ->name('pemesanan.index');

        Route::get('pemesanan/{pemesanan}', [PemesananController::class, 'show'])
            ->name('pemesanan.show');

        // ================= PEMBAYARAN =================
        Route::get('pembayaran', [PembayaranController::class, 'index'])
            ->name('pembayaran.index');

        Route::get('pembayaran/{pembayaran}', [PembayaranController::class, 'show'])
            ->name('pembayaran.show');

        // ================= LAPORAN =================
        Route::get('laporan', [LaporanController::class, 'index'])
            ->name('laporan.index');

        // ================= USER =================
        Route::get('users', [UserController::class, 'index'])
            ->name('users.index');

        Route::patch('users/{user}/status', [UserController::class, 'updateStatus'])
            ->name('users.status');
    });


/*
|--------------------------------------------------------------------------
| OWNER (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Owner\{
    DashboardController as OwnerDashboard,
    LaporanController as OwnerLaporan
};

Route::middleware('auth')->prefix('owner')->name('owner.')->group(function () {

    Route::get('/dashboard', [OwnerDashboard::class, 'index'])->name('dashboard');

    Route::get('/laporan/jadwal', [OwnerLaporan::class, 'jadwal'])->name('laporan.jadwal');
    Route::get('/laporan/pemesanan', [OwnerLaporan::class, 'pemesanan'])->name('laporan.pemesanan');
    Route::get('/laporan/transaksi', [OwnerLaporan::class, 'transaksi'])->name('laporan.transaksi');
});


/*
|--------------------------------------------------------------------------
| PELANGGAN (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Pelanggan\{
    DashboardController as PelangganBeranda,
    LapanganController as PelangganLapangan,
    JadwalController as PelangganJadwal,
    BookingController,
    PaymentController
};

Route::prefix('pelanggan')
    ->name('pelanggan.')
    ->middleware(['auth', 'role:pelanggan'])
    ->group(function () {

        Route::get('/beranda', [PelangganBeranda::class, 'index'])->name('beranda');
        Route::get('/lapangan', [PelangganLapangan::class, 'index'])->name('lapangan');
        Route::get('/jadwal', [PelangganJadwal::class, 'index'])->name('jadwal');
        Route::get('/jadwal/slots', [PelangganJadwal::class, 'slots'])->name('jadwal.slots');
        Route::post('/jadwal/slots', [PelangganJadwal::class, 'slots'])->name('jadwal.slots');


        Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/booking-confirm/{kode}', [BookingController::class, 'bookingConfirm'])->name('booking.confirm');
        Route::get('/booking-history', [BookingController::class, 'bookingHistory'])->name('booking.history');

        Route::post('/payment/create-snap', [PaymentController::class, 'createSnap'])->name('payment.create_snap');
    });
