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
Route::get('/jadwal/slots', [LandingController::class, 'slots'])->name('jadwal.slots');
Route::get('/galeri', [LandingController::class, 'galeri'])->name('galeri');

Route::get('/blog', [LandingController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [LandingController::class, 'blogDetail'])
    ->name('blog.detail');

Route::get('/kontak', [LandingController::class, 'kontak'])->name('kontak');
Route::post('/kontak/kirim', [LandingController::class, 'sendContact'])
    ->name('contact.send');

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
    PenggunaController,
    ArticleController,
    GalleryController,
    ProfileController,
    NotificationController,
    KontakController as AdminKontak
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

        // ================= PENGGUNA =================
        Route::resource('pengguna', PenggunaController::class);

        // ================= ARTIKEL =================
        Route::resource('articles', ArticleController::class);

        // ================= GALERI =================
        Route::resource('galleries', GalleryController::class);

        // ================= PROFILE =================
        Route::get('profile', [ProfileController::class, 'index'])
            ->name('profile.index');

        Route::put('profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::put('profile/password', [ProfileController::class, 'updatePassword'])
            ->name('profile.password');

        // ================= NOTIFIKASI =================
        Route::get('notifications', [NotificationController::class, 'index'])
            ->name('notifications.index'); // Halaman semua notifikasi

        Route::get('notifications/fetch', [NotificationController::class, 'fetch'])
            ->name('notifications.fetch'); // AJAX dropdown

        Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])
            ->name('notifications.read'); // Tandai satu notifikasi

        Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])
            ->name('notifications.readAll'); // Tandai semua

        Route::post('notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])
            ->name('notifications.markAsRead');

        Route::post('notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])
            ->name('notifications.markAllAsRead');

        // ================= KONTAK =================
        Route::get('kontak', [AdminKontak::class, 'index'])
            ->name('kontak.index');

        Route::get('kontak/{id}', [AdminKontak::class, 'show'])
            ->name('kontak.show');

        Route::patch('kontak/{id}/status/{status}', [AdminKontak::class, 'updateStatus'])
            ->name('kontak.updateStatus');

        Route::delete('kontak/{id}', [AdminKontak::class, 'destroy'])
            ->name('kontak.destroy');
    });



/*
|--------------------------------------------------------------------------
| OWNER (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Owner\{
    DashboardController as OwnerDashboard,
    LaporanJadwalController as OwnerLaporanJadwal,
    LaporanPemesananController as OwnerLaporanPemesanan,
    ProfileController as OwnerProfile
};

Route::middleware('auth')
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {

        // ================= DASHBOARD =================
        Route::get('/dashboard', [OwnerDashboard::class, 'index'])
            ->name('dashboard');

        // ================= LAPORAN JADWAL =================
        Route::get('/laporan-jadwal', [OwnerLaporanJadwal::class, 'index'])
            ->name('laporan.jadwal');

        Route::get('/laporan-jadwal/export', [OwnerLaporanJadwal::class, 'exportExcel'])
            ->name('laporan.jadwal.export');

        // ================= LAPORAN PEMESANAN =================
        Route::get('/laporan-pemesanan', [OwnerLaporanPemesanan::class, 'index'])
            ->name('laporan.pemesanan');

        Route::get('/laporan-pemesanan/export', [OwnerLaporanPemesanan::class, 'exportExcel'])
            ->name('laporan.pemesanan.export');

        // ================= PROFILE =================
        Route::get('/profile', [OwnerProfile::class, 'index'])
            ->name('profile.index');

        Route::put('/profile', [OwnerProfile::class, 'update'])
            ->name('profile.update');

        Route::put('/profile/password', [OwnerProfile::class, 'updatePassword'])
            ->name('profile.password');
    });


/*
|--------------------------------------------------------------------------
| PELANGGAN (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Pelanggan\{
    BlogController,
    DashboardController as PelangganBeranda,
    LapanganController as PelangganLapangan,
    JadwalController as PelangganJadwal,
    BookingController,
    PaymentController,
    GaleriController,
    TentangController,
    FaqController,
    KontakController,
    SyaratController,
    ProfileController as PelangganProfile
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
        Route::get(
            '/booking-history/{kode}',
            [BookingController::class, 'bookingHistoryDetail']
        )->name('booking.history.detail');
        Route::post('/booking/rating/{kode}', [BookingController::class, 'giveRating'])->name('booking.rating');
        




        Route::post('/payment/create-snap', [PaymentController::class, 'createSnap'])->name('payment.create_snap');

        Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');


        Route::get('/blog', [BlogController::class, 'blog'])->name('blog');
        Route::get('/blog/{slug}', [BlogController::class, 'blogDetail'])
            ->name('blog.detail');

        Route::get('/tentang', [TentangController::class, 'tentang'])->name('tentang');

        Route::get('/faq', [FaqController::class, 'faq'])->name('faq');

        Route::get('/kontak', [KontakController::class, 'kontak'])->name('kontak');
        Route::post('/kontak/kirim', [KontakController::class, 'sendContact'])
            ->name('contact.send');

        Route::get('/syarat', [SyaratController::class, 'syarat'])->name('syarat');

        // ================= PROFILE =================
        Route::get('/profil', [PelangganProfile::class, 'index'])
            ->name('profile.index');

        Route::put('/profil', [PelangganProfile::class, 'update'])
            ->name('profile.update');

        Route::put('/profil/password', [PelangganProfile::class, 'updatePassword'])
            ->name('profile.password');
    });
