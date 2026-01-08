<?php

namespace App\Helpers;

class MenuHelper
{
    /**
     * Mengambil menu sesuai dengan role user yang login
     */
    public static function getMenuItems()
    {
        $role = auth()->user()->role ?? 'guest';  // Menyediakan fallback ke 'guest' jika belum login

        switch ($role) {
            case 'pelanggan':
                return self::menuPelanggan();
            case 'admin':
                return self::menuAdmin();
            case 'owner':
                return self::menuOwner();
            default:
                return [];
        }
    }

    /* =========================
        MENU PELANGGAN
    ==========================*/
    private static function menuPelanggan()
    {
        return [
            [
                'icon' => 'fa-solid fa-house',
                'name' => 'Dashboard',
                'path' => '/dashboard'
            ],
            [
                'icon' => 'fa-solid fa-calendar-days',
                'name' => 'Jadwal Lapangan',
                'path' => '/jadwal'
            ],
            [
                'icon' => 'fa-solid fa-clipboard-list',
                'name' => 'Pemesanan',
                'path' => '/pemesanan'
            ],
            [
                'icon' => 'fa-solid fa-credit-card',
                'name' => 'Pembayaran',
                'path' => '/pembayaran'
            ],
            [
                'icon' => 'fa-solid fa-clock-rotate-left',
                'name' => 'Riwayat Pemesanan',
                'path' => '/riwayat'
            ],
            [
                'icon' => 'fa-solid fa-user',
                'name' => 'Profil',
                'path' => '/profil'
            ],
            [
                'icon' => 'fa-solid fa-right-from-bracket',
                'name' => 'Logout',
                'path' => '/logout'
            ],
        ];
    }

    /* =========================
        MENU ADMIN
    ==========================*/
    private static function menuAdmin()
    {
        return [
            [
                'icon' => 'fa-solid fa-house',
                'name' => 'Dashboard',
                'path' => '/admin/dashboard'
            ],
            [
                'icon' => 'fa-solid fa-futbol',
                'name' => 'Data Lapangan',
                'path' => '/admin/lapangan'
            ],
            [
                'icon' => 'fa-solid fa-calendar',
                'name' => 'Data Jadwal',
                'path' => '/admin/jadwal'
            ],
            [
                'icon' => 'fa-solid fa-file-invoice',
                'name' => 'Data Pemesanan',
                'path' => '/admin/pemesanan'
            ],
            [
                'icon' => 'fa-solid fa-money-check-dollar',
                'name' => 'Data Pembayaran',
                'path' => '/admin/pembayaran'
            ],
            [
                'icon' => 'fa-solid fa-circle-check',
                'name' => 'Verifikasi Pemesanan',
                'path' => '/admin/verifikasi'
            ],
            [
                'icon' => 'fa-solid fa-chart-line',
                'name' => 'Laporan',
                'path' => '/admin/laporan'
            ],
            [
                'icon' => 'fa-solid fa-users',
                'name' => 'Manajemen User',
                'path' => '/admin/users'
            ],
            [
                'icon' => 'fa-solid fa-right-from-bracket',
                'name' => 'Logout',
                'path' => '/logout'
            ],
        ];
    }

    /* =========================
        MENU OWNER
    ==========================*/
    private static function menuOwner()
    {
        return [
            [
                'icon' => 'fa-solid fa-house',
                'name' => 'Dashboard',
                'path' => '/owner/dashboard'
            ],
            [
                'icon' => 'fa-solid fa-calendar-check',
                'name' => 'Laporan Jadwal Lapangan',
                'path' => '/owner/laporan-jadwal'
            ],
            [
                'icon' => 'fa-solid fa-clipboard',
                'name' => 'Laporan Pemesanan',
                'path' => '/owner/laporan-pemesanan'
            ],
            [
                'icon' => 'fa-solid fa-coins',
                'name' => 'Laporan Transaksi',
                'path' => '/owner/laporan-transaksi'
            ],
            [
                'icon' => 'fa-solid fa-user',
                'name' => 'Profil',
                'path' => '/profil'
            ],
            [
                'icon' => 'fa-solid fa-right-from-bracket',
                'name' => 'Logout',
                'path' => '/logout'
            ],
        ];
    }

    /* =========================
        Cek Menu yang Aktif
    ==========================*/
    public static function isActive($path)
    {
        return request()->is(ltrim($path, '/'));
    }
}
