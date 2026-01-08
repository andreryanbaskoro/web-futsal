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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate(); // Regenerasi session untuk keamanan

            // Redirect berdasarkan role
            return $this->redirectByRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Email atau password salah'
        ]);
    }


    /**
     * Redirect berdasarkan role user
     */
    public function redirectByRole($user)
    {
        \Log::info('Redirecting user with role: ' . $user->role);

        
        switch ($user->role) {
            case 'admin':
                \Log::info('Redirecting to admin dashboard');
                return redirect()->route('admin.dashboard');
            case 'owner':
                return redirect()->route('owner.dashboard');
            case 'pelanggan':
                return redirect()->route('pelanggan.dashboard');
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
