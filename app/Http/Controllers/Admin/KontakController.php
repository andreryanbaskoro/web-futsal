<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class KontakController extends Controller
{
    public function index()
    {
        $kontak = ContactMessage::orderBy('created_at', 'desc')->get();

        // Translate subjek for each contact message
        foreach ($kontak as $message) {
            $message->translated_subjek = $this->getTranslatedSubjek($message->subjek);
        }

        return view('admin.kontak.index', [
            'title' => 'Pesan Kontak',
            'kontak' => $kontak,
        ]);
    }



    private function getTranslatedSubjek($subjek)
    {
        $translations = [
            'booking' => 'Pertanyaan Booking',
            'payment' => 'Masalah Pembayaran',
            'facility' => 'Fasilitas',
            'partnership' => 'Kerjasama',
            'other' => 'Lainnya',
        ];

        return $translations[$subjek] ?? $subjek;
    }


    public function show($id)
    {
        $kontak = ContactMessage::findOrFail($id);

        // Translate the subjek field for this specific contact message
        // Tidak perlu assign `translated_subjek`, karena otomatis diakses
        $kontak = ContactMessage::findOrFail($id);

        // Secara otomatis mark sebagai "dibaca" jika statusnya 'baru'
        if ($kontak->status === 'baru') {
            $kontak->markAsDibaca();
        }

        return view('admin.kontak.show', [
            'title' => 'Detail Pesan',
            'kontak' => $kontak,
        ]);
    }



    public function updateStatus($id, $status)
    {
        $kontak = ContactMessage::findOrFail($id);

        if (!in_array($status, ['baru', 'dibaca', 'dibalas'])) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $kontak->update(['status' => $status]);

        return redirect()
            ->route('admin.kontak.show', $kontak->id)
            ->with('success', 'Status pesan diperbarui');
    }

    public function destroy($id)
    {
        $kontak = ContactMessage::findOrFail($id);

        $kontak->delete();

        return redirect()
            ->route('admin.kontak.index')
            ->with('success', 'Pesan berhasil dihapus');
    }
}
