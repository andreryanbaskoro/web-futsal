<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\LapanganController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\PemesananController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Owner\DashboardController as OwnerDashboard;
use App\Http\Controllers\Owner\LaporanController as OwnerLaporan;

use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboard;
use App\Http\Controllers\Pelanggan\BookingController;
use App\Http\Controllers\Pelanggan\ProfilController;

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])
        ->name('dashboard');

    Route::resource('lapangan', LapanganController::class);
    Route::resource('jadwal', JadwalController::class)->except(['show']);

    Route::get('/pemesanan', [PemesananController::class, 'index'])
        ->name('pemesanan.index');

    Route::get('/pemesanan/{id}', [PemesananController::class, 'show'])
        ->name('pemesanan.show');

    Route::put('/pemesanan/{id}/verifikasi', [PemesananController::class, 'verifikasi'])
        ->name('pemesanan.verifikasi');

    Route::get('/pembayaran', [PembayaranController::class, 'index'])
        ->name('pembayaran.index');

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan');
});

/*
|--------------------------------------------------------------------------
| OWNER
|--------------------------------------------------------------------------
*/
Route::prefix('owner')->name('owner.')->group(function () {

    Route::get('/dashboard', [OwnerDashboard::class, 'index'])
        ->name('dashboard');

    Route::get('/laporan/jadwal', [OwnerLaporan::class, 'jadwal'])
        ->name('laporan.jadwal');

    Route::get('/laporan/pemesanan', [OwnerLaporan::class, 'pemesanan'])
        ->name('laporan.pemesanan');

    Route::get('/laporan/transaksi', [OwnerLaporan::class, 'transaksi'])
        ->name('laporan.transaksi');
});

/*
|--------------------------------------------------------------------------
| PELANGGAN
|--------------------------------------------------------------------------
*/
Route::prefix('pelanggan')->name('pelanggan.')->group(function () {

    Route::get('/dashboard', [PelangganDashboard::class, 'index'])
        ->name('dashboard');

    Route::get('/jadwal', [BookingController::class, 'jadwal'])
        ->name('jadwal');

    Route::post('/pesan', [BookingController::class, 'pesan'])
        ->name('pesan');

    Route::get('/pemesanan', [BookingController::class, 'pemesanan'])
        ->name('pemesanan');

    Route::get('/pembayaran/{id}', [BookingController::class, 'pembayaran'])
        ->name('pembayaran');

    Route::get('/riwayat', [BookingController::class, 'riwayat'])
        ->name('riwayat');

    Route::get('/profil', [ProfilController::class, 'index'])
        ->name('profil.index');

    Route::put('/profil', [ProfilController::class, 'update'])
        ->name('profil.update');
});
