<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        // Admin
        Pengguna::create([
            'nama' => 'Admin Futsal',
            'email' => 'admin@futsal.test',
            'password' => Hash::make('password123'), // hash password
            'no_hp' => '081234567890',
            'peran' => Pengguna::ADMIN,
        ]);

        // Owner
        Pengguna::create([
            'nama' => 'Owner Futsal',
            'email' => 'owner@futsal.test',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567891',
            'peran' => Pengguna::OWNER,
        ]);

        // Pelanggan
        Pengguna::create([
            'nama' => 'Pelanggan Demo',
            'email' => 'pelanggan@futsal.test',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567892',
            'peran' => Pengguna::PELANGGAN,
        ]);
    }
}
