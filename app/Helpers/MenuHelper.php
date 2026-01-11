<?php

namespace App\Helpers;

class MenuHelper
{
    /* =====================================================
     | MENU DATA
     ===================================================== */

    private static function adminMenu(): array
    {
        return [
            ['icon' => 'fa-solid fa-house', 'name' => 'Dashboard', 'path' => '/admin/dashboard'],
            ['icon' => 'fa-solid fa-futbol', 'name' => 'Data Lapangan', 'path' => '/admin/lapangan'],
            ['icon' => 'fa-solid fa-clock', 'name' => 'Jam Operasional', 'path' => '/admin/jam-operasional'], // << Tambahan
            ['icon' => 'fa-solid fa-calendar', 'name' => 'Data Jadwal', 'path' => '/admin/jadwal'],
            ['icon' => 'fa-solid fa-file-invoice', 'name' => 'Data Pemesanan', 'path' => '/admin/pemesanan'],
            ['icon' => 'fa-solid fa-money-check-dollar', 'name' => 'Data Pembayaran', 'path' => '/admin/pembayaran'],
            ['icon' => 'fa-solid fa-circle-check', 'name' => 'Verifikasi Pemesanan', 'path' => '/admin/verifikasi'],
            ['icon' => 'fa-solid fa-chart-line', 'name' => 'Laporan', 'path' => '/admin/laporan'],
            ['icon' => 'fa-solid fa-users', 'name' => 'Manajemen User', 'path' => '/admin/users'],
            ['icon' => 'fa-solid fa-right-from-bracket', 'name' => 'Logout', 'path' => '/logout'],
        ];
    }


    private static function ownerMenu(): array
    {
        return [
            ['icon' => 'fa-solid fa-house', 'name' => 'Dashboard', 'path' => '/owner/dashboard'],
            ['icon' => 'fa-solid fa-calendar-check', 'name' => 'Laporan Jadwal Lapangan', 'path' => '/owner/laporan-jadwal'],
            ['icon' => 'fa-solid fa-clipboard', 'name' => 'Laporan Pemesanan', 'path' => '/owner/laporan-pemesanan'],
            ['icon' => 'fa-solid fa-coins', 'name' => 'Laporan Transaksi', 'path' => '/owner/laporan-transaksi'],
            ['icon' => 'fa-solid fa-user', 'name' => 'Profil', 'path' => '/profil'],
            ['icon' => 'fa-solid fa-right-from-bracket', 'name' => 'Logout', 'path' => '/logout'],
        ];
    }

    private static function pelangganMenu(): array
    {
        return [
            ['icon' => 'fa-solid fa-house', 'name' => 'Dashboard', 'path' => '/pelanggan/dashboard'],
            ['icon' => 'fa-solid fa-calendar-days', 'name' => 'Jadwal Lapangan', 'path' => '/pelanggan/jadwal'],
            ['icon' => 'fa-solid fa-clipboard-list', 'name' => 'Pemesanan', 'path' => '/pelanggan/pemesanan'],
            ['icon' => 'fa-solid fa-credit-card', 'name' => 'Pembayaran', 'path' => '/pelanggan/pembayaran'],
            ['icon' => 'fa-solid fa-clock-rotate-left', 'name' => 'Riwayat Pemesanan', 'path' => '/pelanggan/riwayat'],
            ['icon' => 'fa-solid fa-user', 'name' => 'Profil', 'path' => '/pelanggan/profil'],
            ['icon' => 'fa-solid fa-right-from-bracket', 'name' => 'Logout', 'path' => '/logout'],
        ];
    }

    /* =====================================================
     | MAIN NAV (AUTO BY URL PREFIX)
     ===================================================== */

    public static function getMainNavItems(): array
    {
        $prefix = request()->segment(1);

        return match ($prefix) {
            'admin' => self::adminMenu(),
            'owner' => self::ownerMenu(),
            'pelanggan' => self::pelangganMenu(),
            default => [],
        };
    }

    /* =====================================================
     | OTHERS
     ===================================================== */

    public static function getOthersItems(): array
    {
        return [
            [
                'icon' => 'fa-solid fa-chart-column',
                'name' => 'Charts',
                'subItems' => [
                    ['name' => 'Line Chart', 'path' => '/line-chart', 'pro' => false],
                    ['name' => 'Bar Chart', 'path' => '/bar-chart', 'pro' => false],
                ],
            ],
        ];
    }

    public static function getMenuGroups(): array
    {
        return [
            [
                'title' => 'Main Menu',
                'items' => self::getMainNavItems(),
            ],
            [
                'title' => 'Others',
                'items' => self::getOthersItems(),
            ],
        ];
    }

    /* =====================================================
     | UI HELPERS
     ===================================================== */

    public static function renderIcon(?string $icon): string
    {
        if (!$icon) {
            return '';
        }

        return '<i class="' . e($icon) . ' text-lg w-6 text-center mt-1"></i>';
    }

    public static function isActive(array $item): bool
    {
        if (!isset($item['path'])) {
            return false;
        }

        return request()->is(ltrim($item['path'], '/') . '*');
    }
}
