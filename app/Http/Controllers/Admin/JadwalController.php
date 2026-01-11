<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        // Urut berdasarkan id_jadwal (string)
        $jadwal = Jadwal::orderBy('id_jadwal', 'desc')->get();

        return view('admin.jadwal.index', [
            'title' => 'Data Jadwal',
            'jadwal' => $jadwal,
        ]);
    }

    public function create()
    {
        // Ambil data lapangan untuk pilihan pada form
        $lapangan = Lapangan::all();

        return view('admin.jadwal.create', [
            'title' => 'Tambah Jadwal',
            'lapangan' => $lapangan,
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input dengan pesan khusus bahasa Indonesia
        $validated = $request->validate([
            'id_lapangan'  => 'required|exists:lapangan,id_lapangan',
            'tanggal'       => 'required|date',
            'jam_mulai'     => 'required|date_format:H:i',
            'jam_selesai'   => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'id_lapangan.required' => 'Lapangan harus dipilih.',
            'id_lapangan.exists'   => 'Lapangan yang dipilih tidak valid.',
            'tanggal.required'     => 'Tanggal jadwal harus diisi.',
            'tanggal.date'         => 'Tanggal harus valid.',
            'jam_mulai.required'   => 'Jam mulai harus diisi.',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid (gunakan H:i).',
            'jam_selesai.required' => 'Jam selesai harus diisi.',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid (gunakan H:i).',
            'jam_selesai.after'    => 'Jam selesai harus lebih dari jam mulai.',
        ]);

        try {
            Jadwal::create($validated);

            return redirect()->route('admin.jadwal.index')
                ->with('success', 'Jadwal berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan jadwal: ' . $e->getMessage());
        }
    }

    public function edit($id_jadwal)
    {
        $jadwal = Jadwal::findOrFail($id_jadwal);
        $lapangan = Lapangan::all(); // Ambil data lapangan untuk pilihan pada form

        return view('admin.jadwal.edit', [
            'title'  => 'Edit Jadwal',
            'jadwal' => $jadwal,
            'lapangan' => $lapangan,
        ]);
    }

    public function update(Request $request, $id_jadwal)
    {
        $jadwal = Jadwal::findOrFail($id_jadwal);

        // Validasi input dengan pesan khusus bahasa Indonesia
        $validated = $request->validate([
            'id_lapangan'  => 'required|exists:lapangan,id_lapangan',
            'tanggal'       => 'required|date',
            'jam_mulai'     => 'required|date_format:H:i',
            'jam_selesai'   => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'id_lapangan.required' => 'Lapangan harus dipilih.',
            'id_lapangan.exists'   => 'Lapangan yang dipilih tidak valid.',
            'tanggal.required'     => 'Tanggal jadwal harus diisi.',
            'tanggal.date'         => 'Tanggal harus valid.',
            'jam_mulai.required'   => 'Jam mulai harus diisi.',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid (gunakan H:i).',
            'jam_selesai.required' => 'Jam selesai harus diisi.',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid (gunakan H:i).',
            'jam_selesai.after'    => 'Jam selesai harus lebih dari jam mulai.',
        ]);

        try {
            $jadwal->update($validated);

            return redirect()->route('admin.jadwal.index')
                ->with('success', 'Jadwal berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui jadwal: ' . $e->getMessage());
        }
    }

    public function destroy($id_jadwal)
    {
        $jadwal = Jadwal::findOrFail($id_jadwal);

        try {
            $jadwal->delete();

            return redirect()->route('admin.jadwal.index')
                ->with('success', 'Jadwal berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus jadwal: ' . $e->getMessage());
        }
    }
}
