<?php

namespace Database\Seeders;

use App\Models\Pengguna; // Pastikan model yang benar
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed PenggunaSeeder
        $this->call([
            PenggunaSeeder::class,
        ]);
    }
}
