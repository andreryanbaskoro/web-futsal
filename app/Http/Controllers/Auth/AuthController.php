<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLogin()
    {
        return view('auth.login', [
            'title' => 'Login'
        ]);
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi email dan password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah email dan password cocok
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate(); // Regenerasi session untuk keamanan

            // Redirect berdasarkan role
            return $this->redirectByRole(Auth::user());
        }

        // Cek jika email tidak valid atau password salah
        $errors = [];
        // Cek jika email tidak terdaftar
        if (!\App\Models\Pengguna::where('email', $request->email)->exists()) {
            $errors['email'] = 'Email tidak terdaftar.';
        }

        // Cek jika password salah
        if (\App\Models\Pengguna::where('email', $request->email)->exists()) {
            $errors['password'] = 'Password yang Anda masukkan salah.';
        }

        // Menampilkan pesan error jika ada
        return back()->withErrors($errors);
    }


    /**
     * Redirect berdasarkan role user
     */
    public function redirectByRole($user)
    {
        \Log::info('Redirecting user with role: ' . $user->peran);


        switch ($user->peran) {
            case 'admin':
                \Log::info('Redirecting to admin dashboard');
                return redirect()->route('admin.dashboard');
            case 'owner':
                return redirect()->route('owner.dashboard');
            case 'pelanggan':
                return redirect()->route('pelanggan.beranda');
            default:
                return redirect()->route('login');
        }
    }



    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');  // Kembali ke halaman login
    }
}
