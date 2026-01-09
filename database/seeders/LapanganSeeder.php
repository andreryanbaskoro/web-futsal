<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Lapangan;

class LapanganSeeder extends Seeder
{
    public function run(): void
    {
        // Inisialisasi Faker untuk generate data palsu
        $faker = Faker::create();

        // Menambahkan 10 data palsu untuk Lapangan
        foreach (range(1, 10) as $index) {
            Lapangan::create([
                'nama_lapangan' => $faker->word, // Nama lapangan palsu
                'harga' => $faker->randomFloat(2, 100000, 1000000), // Harga acak
                'deskripsi' => $faker->paragraph, // Deskripsi lapangan
            ]);
        }
    }
}
