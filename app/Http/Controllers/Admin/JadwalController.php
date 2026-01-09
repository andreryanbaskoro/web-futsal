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
        $jadwals = Jadwal::with('lapangan')->orderBy('tanggal')->orderBy('jam_mulai')->get();

        return view('admin.jadwal.index', [
            'title' => 'Data Jadwal',
            'jadwals' => $jadwals,
        ]);
    }

    public function create()
    {
        $lapangans = Lapangan::aktif()->get();
        return view('admin.jadwal.create', [
            'title' => 'Tambah Jadwal',
            'lapangans' => $lapangans,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_lapangan' => 'required|exists:lapangan,id_lapangan',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Pastikan tidak ada jadwal identik (satu lapangan, tanggal, jam_mulai sama)
        $exists = Jadwal::where('id_lapangan', $request->id_lapangan)
            ->where('tanggal', $request->tanggal)
            ->where('jam_mulai', $request->jam_mulai)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Jadwal sudah ada untuk slot tersebut');
        }

        Jadwal::create($request->only(['id_lapangan','tanggal','jam_mulai','jam_selesai']));

        return redirect()->route('admin.jadwal.index')->with('success','Jadwal berhasil dibuat');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $lapangans = Lapangan::aktif()->get();

        return view('admin.jadwal.edit', [
            'title' => 'Edit Jadwal',
            'jadwal' => $jadwal,
            'lapangans' => $lapangans,
        ]);
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'id_lapangan' => 'required|exists:lapangan,id_lapangan',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $jadwal->update($request->only(['id_lapangan','tanggal','jam_mulai','jam_selesai']));

        return redirect()->route('admin.jadwal.index')->with('success','Jadwal berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        // Bisa tambahkan pengecekan jika sudah dibooking
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')->with('success','Jadwal berhasil dihapus');
    }
}
