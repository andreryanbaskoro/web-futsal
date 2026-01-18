<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VerificationController extends Controller
{
    /**
     * Menampilkan halaman verifikasi
     */
    public function show($email)
    {
        return view('auth.verify-email', compact('email'));  // Mengirimkan email ke view
    }

    /**
     * Memproses kode verifikasi
     */
    public function verify(Request $request, $email)
    {
        // Validasi kode verifikasi
        $request->validate([
            'verification_code' => 'required|numeric|digits:6',
        ]);

        // Cari pengguna berdasarkan email
        $user = Pengguna::where('email', $email)->first();

        // Cek apakah pengguna ada
        if (!$user) {
            return redirect()->route('register')->withErrors('Pengguna tidak ditemukan!');
        }

        // Cek apakah kode verifikasi masih berlaku (misalnya dalam 15 menit)
        $verificationSentAt = Carbon::parse($user->verification_sent_at);
        if ($verificationSentAt->diffInMinutes(Carbon::now()) > 15) {
            return back()->withErrors('Kode verifikasi telah kadaluarsa! Silakan minta kode baru.');
        }

        // Verifikasi kode
        if ($user->verification_code !== $request->verification_code) {
            return back()->withErrors('Kode verifikasi salah!');
        }

        // Tandai pengguna sebagai terverifikasi
        $user->email_verified_at = now();
        $user->status = 'active';
        $user->save();

        // Login pengguna otomatis setelah verifikasi
        Auth::login($user);

        // Arahkan pengguna ke dashboard
        return redirect()->route('dashboard'); // Atau halaman lain setelah berhasil verifikasi
    }
}
