<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;

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
        // Validasi input
        $validated = $request->validate([
            'nama_lapangan'  => 'required|string|max:100',
            'deskripsi'      => 'nullable|string',
            'harga_per_jam'  => 'required|numeric|min:0',
            'status'         => 'required|in:aktif,nonaktif',
        ]);

        try {
            Lapangan::create($validated);

            return redirect()->route('admin.lapangan.index')
                ->with('success', 'Lapangan berhasil ditambahkan');
        } catch (\Exception $e) {
            // Tangani error lain yang tidak tertangani oleh validasi
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan lapangan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $lapangan = Lapangan::findOrFail($id);

        return view('admin.lapangan.edit', [
            'title'    => 'Edit Lapangan',
            'lapangan' => $lapangan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $lapangan = Lapangan::findOrFail($id);

        $validated = $request->validate([
            'nama_lapangan'  => 'required|string|max:100',
            'deskripsi'      => 'nullable|string',
            'harga_per_jam'  => 'required|numeric|min:0',
            'status'         => 'required|in:aktif,nonaktif',
        ]);

        try {
            $lapangan->update($validated);

            return redirect()->route('admin.lapangan.index')
                ->with('success', 'Lapangan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui lapangan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $lapangan = Lapangan::findOrFail($id);

        try {
            $lapangan->delete();

            return redirect()->route('admin.lapangan.index')
                ->with('success', 'Lapangan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus lapangan: ' . $e->getMessage());
        }
    }
}
