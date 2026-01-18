<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class RegisterController extends Controller
{
    public function show()
    {
        $testimonials = [
            [
                'quote' => 'Daftar gratis dan langsung bisa booking! Prosesnya cepat banget.',
                'name' => 'Andi Pratama',
                'year' => '2024',
                'avatar' => 15
            ],
            [
                'quote' => 'Aplikasi booking paling mudah yang pernah saya coba. Recommended!',
                'name' => 'Budi Santoso',
                'year' => '2023',
                'avatar' => 12
            ],
            [
                'quote' => 'Gak perlu ribet telepon-telepon lagi. Tinggal klik, bayar, main!',
                'name' => 'Rudi Hermawan',
                'year' => '2024',
                'avatar' => 33
            ],
            [
                'quote' => 'Sistemnya user-friendly, cocok banget buat yang sering main futsal.',
                'name' => 'Dimas Wijaya',
                'year' => '2023',
                'avatar' => 8
            ],
            [
                'quote' => 'Harga transparan, booking cepat. Mantap pokoknya!',
                'name' => 'Fajar Nugroho',
                'year' => '2024',
                'avatar' => 22
            ],
            [
                'quote' => 'Suka banget! Jadwal lapangan real-time, nggak pernah bentrok.',
                'name' => 'Eka Saputra',
                'year' => '2023',
                'avatar' => 45
            ],
            [
                'quote' => 'Aplikasi ini bikin ngatur jadwal main futsal jadi lebih gampang.',
                'name' => 'Gilang Ramadhan',
                'year' => '2024',
                'avatar' => 67
            ],
            [
                'quote' => 'Pelayanan oke, sistem booking modern. Top deh!',
                'name' => 'Hendra Gunawan',
                'year' => '2023',
                'avatar' => 19
            ],
        ];

        $randomTestimonial = $testimonials[array_rand($testimonials)];

        return view('auth.register', compact('randomTestimonial'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:3|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'required|email|max:255|unique:pengguna,email',
                'no_hp' => 'required|string|min:10|max:15|regex:/^[0-9]+$/|unique:pengguna,no_hp',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.min' => 'Nama lengkap minimal 3 karakter.',
                'name.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain atau login.',
                'no_hp.required' => 'Nomor telepon wajib diisi.',
                'no_hp.min' => 'Nomor telepon minimal 10 digit.',
                'no_hp.regex' => 'Nomor telepon hanya boleh berisi angka.',
                'no_hp.unique' => 'Nomor telepon sudah terdaftar.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);

            // Toggle berdasarkan config
            if (config('app.validation.stop_on_first_failure', false)) {
                $validator->stopOnFirstFailure();
            }

            if ($validator->fails()) {
                // Jika stop_on_first_failure = true, ambil 1 error saja
                if (config('app.validation.stop_on_first_failure', false)) {
                    return back()
                        ->withInput($request->except('password', 'password_confirmation'))
                        ->with('error', $validator->errors()->first());
                }

                // Jika false, tampilkan semua error
                return back()
                    ->withInput($request->except('password', 'password_confirmation'))
                    ->withErrors($validator);
            }

            // Normalisasi nomor HP
            $no_hp = $request->no_hp;
            if (str_starts_with($no_hp, '+62')) {
                $no_hp = '0' . substr($no_hp, 3);
            } elseif (str_starts_with($no_hp, '62')) {
                $no_hp = '0' . substr($no_hp, 2);
            }

            DB::beginTransaction();

            $user = Pengguna::create([
                'nama' => ucwords(strtolower($request->name)),
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
                'no_hp' => $no_hp,
                'peran' => 'pelanggan',
                'status' => 'active',
            ]);

            DB::commit();

            return redirect()
                ->route('login')
                ->with('success', 'Pendaftaran berhasil! Silakan login dengan akun Anda.');
        } catch (Exception $e) {
            DB::rollBack();

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }
}
