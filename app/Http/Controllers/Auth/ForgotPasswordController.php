<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Menampilkan halaman reset password
     */
    public function show()
    {
        return view('auth.forgot-password', [
            'title' => 'Reset Password'
        ]);
    }

    /**
     * Mengirimkan link untuk reset password
     */
    public function send(Request $request)
    {
        // Validasi email yang dikirim
        $request->validate([
            'email' => 'required|email',
        ]);

        // Mengirimkan link reset password
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Mengembalikan response sesuai dengan status pengiriman
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
