<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Inisialisasi Faker untuk generate data palsu
        $faker = Faker::create();

        // Menambahkan 10 data palsu untuk User
        foreach (range(1, 10) as $index) {
            User::create([
                'nama' => $faker->name, // Nama palsu
                'email' => $faker->unique()->safeEmail, // Email unik
                'password' => Hash::make('password'), // Password default
                'role' => $faker->randomElement(['admin', 'pelanggan', 'owner']), // Role acak
            ]);
        }
    }
}
