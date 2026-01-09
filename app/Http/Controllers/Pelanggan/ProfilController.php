<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        // Jika pakai auth -> pakai auth user, kalau tidak coba ambil user pertama
        $user = auth()->check() ? auth()->user() : User::first();

        return view('pelanggan.profil.index', [
            'title' => 'Profil Saya',
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->check() ? auth()->user() : User::first();

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:pengguna,email,'.$user->id_pengguna.',id_pengguna',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user->update($request->only(['nama','email','no_hp']));

        return redirect()->route('pelanggan.profil.index')->with('success','Profil diperbarui');
    }
}
