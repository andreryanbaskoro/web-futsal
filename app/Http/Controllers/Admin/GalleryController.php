<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /* =======================
     * INDEX
     * ======================= */
    public function index()
    {
        $galleries = Gallery::orderBy('created_at', 'desc')->get();

        return view('admin.galleries.index', [
            'title'     => 'Daftar Galeri',
            'galleries' => $galleries,
        ]);
    }

    /* =======================
     * CREATE
     * ======================= */
    public function create()
    {
        return view('admin.galleries.create', [
            'title' => 'Tambah Galeri',
        ]);
    }

    /* =======================
     * STORE
     * ======================= */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'required|string|max:50',
            'is_active'   => 'nullable|boolean',

            'image_type'  => 'required|in:upload,url',
            'image_file'  => 'nullable|image|max:2048', // max 2MB
            'image_url'   => 'nullable|url|max:255',
        ]);

        /* =======================
         * HANDLE IMAGE
         * ======================= */
        if ($request->image_type === 'upload' && $request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('galleries', 'public');
            $validated['image'] = $path;
        } elseif ($request->image_type === 'url' && $request->image_url) {
            $validated['image'] = $request->image_url;
        } else {
            return back()
                ->withErrors(['image' => 'Gambar wajib diisi'])
                ->withInput();
        }

        // Default aktif
        $validated['is_active'] = $request->boolean('is_active');

        Gallery::create($validated);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil ditambahkan');
    }

    /* =======================
     * EDIT
     * ======================= */
    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);

        return view('admin.galleries.edit', [
            'title'   => 'Edit Galeri',
            'gallery' => $gallery,
        ]);
    }

    /* =======================
     * UPDATE
     * ======================= */
    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'required|string|max:50',
            'is_active'   => 'nullable|boolean',

            'image_type'  => 'required|in:upload,url',
            'image_file'  => 'nullable|image|max:2048',
            'image_url'   => 'nullable|url|max:255',
        ]);

        /* =======================
         * HANDLE IMAGE UPDATE
         * ======================= */
        if ($request->image_type === 'upload' && $request->hasFile('image_file')) {
            // hapus file lama jika bukan URL
            if ($gallery->image && !str_starts_with($gallery->image, 'http')) {
                Storage::disk('public')->delete($gallery->image);
            }

            $path = $request->file('image_file')->store('galleries', 'public');
            $validated['image'] = $path;
        } elseif ($request->image_type === 'url' && $request->image_url) {
            $validated['image'] = $request->image_url;
        } else {
            // pakai gambar lama
            $validated['image'] = $gallery->image;
        }

        $validated['is_active'] = $request->boolean('is_active');

        $gallery->update($validated);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil diperbarui');
    }

    /* =======================
     * DESTROY
     * ======================= */
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        try {
            // hapus file jika bukan URL
            if ($gallery->image && !str_starts_with($gallery->image, 'http')) {
                Storage::disk('public')->delete($gallery->image);
            }

            $gallery->delete();

            return redirect()->route('admin.galleries.index')
                ->with('success', 'Galeri berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus galeri: ' . $e->getMessage());
        }
    }
}
