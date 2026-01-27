<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class KontakController extends Controller
{

    public function kontak()
    {
        return view('pelanggan.kontak');
    }

    public function sendContact(Request $request)
    {
        // 1. Validasi
        $validated = $request->validate([
            'nama'        => 'required|string|max:150',
            'email'       => 'required|email|max:150',
            'no_telepon'  => 'nullable|string|max:20',
            'subjek'      => 'required|string|max:50',
            'pesan'       => 'required|string',
        ]);

        // 2. Simpan ke database
        ContactMessage::create($validated);

        // 3. Redirect + anchor
        return redirect()
            ->to(route('pelanggan.kontak') . '#contact-section')
            ->with('success', 'Pesan berhasil dikirim. Terima kasih!');
    }
}
