<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class LapanganController extends Controller
{
    public function index()
    {
        $lapangan = Lapangan::orderBy('id_lapangan', 'desc')->get();

        return view('admin.lapangan.index', [
            'title'    => 'Data Lapangan',
            'lapangan' => $lapangan,
        ]);
    }

    public function create()
    {
        return view('admin.lapangan.create', [
            'title' => 'Tambah Lapangan',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lapangan' => 'required|string|max:30',
            'deskripsi'     => 'nullable|string',
            'dimensi'       => 'nullable|string|max:20',
            'kapasitas'     => 'nullable|string|max:20',
            'status'        => 'required|in:aktif,nonaktif',

            'image_type'    => 'required|in:upload,url',
            'image_file'    => 'required_if:image_type,upload|image|max:2048',
            'image_url'     => 'required_if:image_type,url|url|max:255',
        ], [
            'nama_lapangan.required' => 'Nama lapangan wajib diisi',
            'nama_lapangan.max'      => 'Nama lapangan maksimal 30 karakter',

            'image_type.required'    => 'Tipe gambar wajib dipilih',
            'image_file.required_if' => 'Gambar wajib diupload',
            'image_file.image'       => 'File harus berupa gambar',
            'image_file.max'         => 'Ukuran gambar maksimal 2MB',

            'image_url.required_if'  => 'URL gambar wajib diisi',
            'image_url.url'          => 'Format URL tidak valid',
        ]);

        /* =======================
     * LOGIKA BISNIS
     * ======================= */

        // nama lapangan tidak boleh duplikat
        if (Lapangan::where('nama_lapangan', $validated['nama_lapangan'])->exists()) {
            return back()
                ->withErrors(['nama_lapangan' => 'Nama lapangan sudah digunakan'])
                ->withInput();
        }

        // image_type upload tapi file kosong (double safety)
        if (
            $validated['image_type'] === 'upload'
            && !$request->hasFile('image_file')
        ) {
            return back()
                ->withErrors(['image_file' => 'File gambar wajib diupload'])
                ->withInput();
        }

        /* =======================
     * HANDLE IMAGE
     * ======================= */
        if ($validated['image_type'] === 'upload') {
            $validated['image'] = $request->file('image_file')
                ->store('lapangan', 'public');
        } else {
            $validated['image'] = $validated['image_url'];
        }

        Lapangan::create($validated);

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil ditambahkan');
    }




    public function edit($id_lapangan)
    {
        $lapangan = Lapangan::findOrFail($id_lapangan);

        return view('admin.lapangan.edit', [
            'title'    => 'Edit Lapangan',
            'lapangan' => $lapangan,
        ]);
    }

    public function update(Request $request, $id_lapangan)
    {
        $lapangan = Lapangan::findOrFail($id_lapangan);

        $validated = $request->validate([
            'nama_lapangan' => 'required|string|max:30',
            'deskripsi'     => 'nullable|string',
            'dimensi'       => 'nullable|string|max:20',
            'kapasitas'     => 'nullable|string|max:20',
            'status'        => 'required|in:aktif,nonaktif',

            'image_type'    => 'required|in:upload,url',
            'image_file'    => 'nullable|image|max:2048',
            'image_url'     => 'nullable|url|max:255',
        ], [
            'nama_lapangan.required' => 'Nama lapangan wajib diisi',
            'nama_lapangan.max'      => 'Nama lapangan maksimal 30 karakter',

            'image_file.image'       => 'File harus berupa gambar',
            'image_file.max'         => 'Ukuran gambar maksimal 2MB',

            'image_url.url'          => 'Format URL tidak valid',
        ]);

        /* =======================
     * LOGIKA BISNIS
     * ======================= */

        // nama lapangan tidak boleh duplikat
        $exists = Lapangan::where('nama_lapangan', $validated['nama_lapangan'])
            ->where('id_lapangan', '!=', $lapangan->id_lapangan)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['nama_lapangan' => 'Nama lapangan sudah digunakan'])
                ->withInput();
        }

        // jika pilih upload tapi belum ada gambar sama sekali
        if (
            $validated['image_type'] === 'upload'
            && !$request->hasFile('image_file')
            && empty($lapangan->image)
        ) {
            return back()
                ->withErrors(['image_file' => 'Silakan upload gambar lapangan'])
                ->withInput();
        }

        /* =======================
     * HANDLE IMAGE
     * ======================= */

        if ($validated['image_type'] === 'upload') {

            if ($request->hasFile('image_file')) {

                // HAPUS GAMBAR LAMA (JIKA ADA & UPLOAD)
                if (
                    $lapangan->image_type === 'upload' &&
                    $lapangan->image &&
                    Storage::disk('public')->exists($lapangan->image)
                ) {
                    Storage::disk('public')->delete($lapangan->image);
                }

                // SIMPAN GAMBAR BARU
                $validated['image'] = $request->file('image_file')
                    ->store('lapangan', 'public');
            } else {
                $validated['image'] = $lapangan->image;
            }
        } else {
            // URL IMAGE
            $validated['image'] = $validated['image_url'];
        }

        $lapangan->update($validated);

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil diperbarui');
    }



    public function destroy($id_lapangan)
    {
        $lapangan = Lapangan::findOrFail($id_lapangan);

        try {

            // HAPUS FILE JIKA UPLOAD
            if (
                $lapangan->image_type === 'upload' &&
                $lapangan->image &&
                Storage::disk('public')->exists($lapangan->image)
            ) {
                Storage::disk('public')->delete($lapangan->image);
            }

            $lapangan->delete();

            return redirect()->route('admin.lapangan.index')
                ->with('success', 'Lapangan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus lapangan: ' . $e->getMessage());
        }
    }
}
