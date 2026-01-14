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
        $validated = $request->validate([
            'nama_lapangan' => 'required|string|max:30',
            'deskripsi'     => 'nullable|string',
            'dimensi'       => 'nullable|string|max:20',
            'kapasitas'     => 'nullable|string|max:20',
            'status'        => 'required|in:aktif,nonaktif',
        ], [
            'nama_lapangan.required' => 'Kolom nama lapangan wajib diisi.',
            'nama_lapangan.max'      => 'Kolom nama lapangan tidak boleh lebih dari 30 karakter.',
            'dimensi.max'            => 'Dimensi maksimal 20 karakter.',
            'kapasitas.max'          => 'Kapasitas maksimal 20 karakter.',
            'status.required'        => 'Kolom status wajib diisi.',
            'status.in'              => 'Status harus aktif atau nonaktif.',
        ]);

        try {
            Lapangan::create($validated);

            return redirect()->route('admin.lapangan.index')
                ->with('success', 'Lapangan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan lapangan: ' . $e->getMessage());
        }
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
            'nama_lapangan'  => 'required|string|max:30',
            'deskripsi'      => 'nullable|string',
            'dimensi'        => 'nullable|string|max:20',
            'kapasitas'      => 'nullable|string|max:20',
            'status'         => 'required|in:aktif,nonaktif',
        ], [
            'nama_lapangan.required' => 'Kolom nama lapangan wajib diisi.',
            'nama_lapangan.max'      => 'Kolom nama lapangan tidak boleh lebih dari 30 karakter.',
            'deskripsi.string'       => 'Kolom deskripsi harus berupa teks.',
            'status.required'        => 'Kolom status wajib diisi.',
            'status.in'              => 'Kolom status harus salah satu dari: aktif, nonaktif.',
            'dimensi.max'            => 'Dimensi maksimal 20 karakter.',
            'kapasitas.max'          => 'Kapasitas maksimal 20 karakter.',
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

    public function destroy($id_lapangan)
    {
        $lapangan = Lapangan::findOrFail($id_lapangan);

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
