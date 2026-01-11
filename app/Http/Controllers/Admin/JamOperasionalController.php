<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JamOperasional;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class JamOperasionalController extends Controller
{
    public function index()
    {
        $jamOperasional = JamOperasional::with('lapangan')
            ->orderBy('id_lapangan')
            ->orderByRaw("FIELD(hari,'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
            ->get();

        return view('admin.jam_operasional.index', [
            'title' => 'Data Jam Operasional',
            'jamOperasional' => $jamOperasional,
        ]);
    }

    public function create()
    {
        $lapangan = Lapangan::where('status', 'aktif')->get();

        return view('admin.jam_operasional.create', [
            'title' => 'Tambah Jam Operasional',
            'lapangan' => $lapangan,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_lapangan' => 'required|exists:lapangan,id_lapangan',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'interval_menit' => 'required|integer|min:30',
            'harga' => 'required|integer|min:0',
        ], [
            'id_lapangan.required' => 'Lapangan wajib dipilih.',
            'id_lapangan.exists' => 'Lapangan tidak valid.',
            'hari.required' => 'Hari wajib diisi.',
            'hari.in' => 'Hari harus antara Senin sampai Minggu.',
            'jam_buka.required' => 'Jam buka wajib diisi.',
            'jam_buka.date_format' => 'Format jam buka harus HH:MM.',
            'jam_tutup.required' => 'Jam tutup wajib diisi.',
            'jam_tutup.date_format' => 'Format jam tutup harus HH:MM.',
            'jam_tutup.after' => 'Jam tutup harus lebih besar dari jam buka.',
            'interval_menit.required' => 'Interval wajib diisi.',
            'interval_menit.integer' => 'Interval harus berupa angka.',
            'interval_menit.min' => 'Interval minimal 30 menit.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.integer' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
        ]);

        /** =============================
         *  VALIDASI BISNIS
         *  ============================= */

        // ❌ Cegah duplikasi lapangan + hari
        $exists = JamOperasional::where('id_lapangan', $validated['id_lapangan'])
            ->where('hari', $validated['hari'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'Jam operasional untuk lapangan dan hari tersebut sudah ada.');
        }

        // ⏱ Validasi interval membagi waktu
        $start = strtotime($validated['jam_buka']);
        $end = strtotime($validated['jam_tutup']);
        $diffMinutes = ($end - $start) / 60;

        if ($diffMinutes % $validated['interval_menit'] !== 0) {
            return back()
                ->withInput()
                ->with('error', 'Interval harus habis membagi rentang jam buka dan tutup.');
        }

        try {
            JamOperasional::create($validated);

            return redirect()
                ->route('admin.jam-operasional.index')
                ->with('success', 'Jam operasional berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }


    public function edit($id_operasional)
    {
        $jamOperasional = JamOperasional::findOrFail($id_operasional);
        $lapangan = Lapangan::where('status', 'aktif')->get();

        return view('admin.jam_operasional.edit', [
            'title' => 'Edit Jam Operasional',
            'jamOperasional' => $jamOperasional,
            'lapangan' => $lapangan,
        ]);
    }

    public function update(Request $request, $id_operasional)
    {
        $jamOperasional = JamOperasional::findOrFail($id_operasional);

        $validated = $request->validate([
            'id_lapangan' => 'required|exists:lapangan,id_lapangan',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'interval_menit' => 'required|integer|min:30',
            'harga' => 'required|integer|min:0',
        ], [
            'id_lapangan.required' => 'Lapangan wajib dipilih.',
            'id_lapangan.exists' => 'Lapangan tidak valid.',
            'hari.required' => 'Hari wajib diisi.',
            'hari.in' => 'Hari harus antara Senin sampai Minggu.',
            'jam_buka.required' => 'Jam buka wajib diisi.',
            'jam_buka.date_format' => 'Format jam buka harus HH:MM.',
            'jam_tutup.required' => 'Jam tutup wajib diisi.',
            'jam_tutup.date_format' => 'Format jam tutup harus HH:MM.',
            'jam_tutup.after' => 'Jam tutup harus lebih besar dari jam buka.',
            'interval_menit.required' => 'Interval wajib diisi.',
            'interval_menit.integer' => 'Interval harus berupa angka.',
            'interval_menit.min' => 'Interval minimal 30 menit.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.integer' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
        ]);

        /** =============================
         *  VALIDASI BISNIS
         *  ============================= */

        // ❌ Cegah duplikasi (kecuali data sendiri)
        $exists = JamOperasional::where('id_lapangan', $validated['id_lapangan'])
            ->where('hari', $validated['hari'])
            ->where('id_operasional', '!=', $jamOperasional->id_operasional)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'Jam operasional untuk lapangan dan hari tersebut sudah ada.');
        }

        // ⏱ Validasi interval
        $start = strtotime($validated['jam_buka']);
        $end = strtotime($validated['jam_tutup']);
        $diffMinutes = ($end - $start) / 60;

        if ($diffMinutes % $validated['interval_menit'] !== 0) {
            return back()
                ->withInput()
                ->with('error', 'Interval harus habis membagi rentang jam buka dan tutup.');
        }

        try {
            $jamOperasional->update($validated);

            return redirect()
                ->route('admin.jam-operasional.index')
                ->with('success', 'Jam operasional berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }


    public function destroy($id_operasional)
    {
        $jamOperasional = JamOperasional::findOrFail($id_operasional);

        try {
            $jamOperasional->delete();

            return redirect()->route('admin.jam_operasional.index')
                ->with('success', 'Jam operasional berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus jam operasional: ' . $e->getMessage());
        }
    }
}
