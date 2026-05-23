<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JamOperasional;
use App\Models\Lapangan;
use Illuminate\Support\Facades\DB;
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

        $hariTerpakai = JamOperasional::select('id_lapangan', 'hari')
            ->get()
            ->groupBy('id_lapangan')
            ->map(function ($items) {
                return $items->pluck('hari')->values();
            });

        return view('admin.jam_operasional.create', [
            'title' => 'Tambah Jam Operasional',
            'lapangan' => $lapangan,
            'hariTerpakai' => $hariTerpakai,
        ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'jam_buka' => str_replace('.', ':', $request->jam_buka),
            'jam_tutup' => str_replace('.', ':', $request->jam_tutup),
        ]);

        $validated = $request->validate([
            'id_lapangan' => 'required|exists:lapangan,id_lapangan',
            'hari' => 'required|array|min:1',
            'hari.*' => 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'interval_menit' => 'required|integer|min:30',
            'harga' => 'required|integer|min:0',
        ], [
            'id_lapangan.required' => 'Lapangan wajib dipilih.',
            'id_lapangan.exists' => 'Lapangan tidak valid.',
            'hari.required' => 'Hari wajib dipilih.',
            'hari.array' => 'Format hari tidak valid.',
            'hari.min' => 'Minimal pilih satu hari.',
            'hari.*.in' => 'Hari tidak valid.',
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

        $start = strtotime($validated['jam_buka']);
        $end = strtotime($validated['jam_tutup']);
        $diffMinutes = ($end - $start) / 60;

        if ($diffMinutes % $validated['interval_menit'] !== 0) {
            return back()
                ->withInput()
                ->with('error', 'Interval harus habis membagi rentang jam buka dan tutup.');
        }

        // Cek dulu semua hari yang bentrok, sebelum ada data yang disimpan
        $existingHari = JamOperasional::where('id_lapangan', $validated['id_lapangan'])
            ->whereIn('hari', $validated['hari'])
            ->pluck('hari')
            ->toArray();

        if (!empty($existingHari)) {
            $hariTerpakai = collect($existingHari)
                ->map(fn($h) => ucfirst($h))
                ->implode(', ');

            return back()
                ->withInput()
                ->with('error', 'Hari berikut sudah ada: ' . $hariTerpakai . '. Silakan hilangkan centangnya.');
        }

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['hari'] as $hari) {
                    JamOperasional::create([
                        'id_lapangan' => $validated['id_lapangan'],
                        'hari' => $hari,
                        'jam_buka' => $validated['jam_buka'],
                        'jam_tutup' => $validated['jam_tutup'],
                        'interval_menit' => $validated['interval_menit'],
                        'harga' => $validated['harga'],
                    ]);
                }
            });

            return redirect()
                ->route('admin.jam-operasional.index')
                ->with('success', 'Jam operasional berhasil ditambahkan.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function edit($id_operasional)
    {
        $jamOperasional = JamOperasional::findOrFail($id_operasional);

        $lapangan = Lapangan::where('status', 'aktif')->get();

        // Ambil semua hari yang sudah dipakai
        $hariTerpakai = JamOperasional::select('id_lapangan', 'hari')
            ->get()
            ->groupBy('id_lapangan')
            ->map(function ($items) {
                return $items->pluck('hari')->values();
            });

        return view('admin.jam_operasional.edit', [
            'title' => 'Edit Jam Operasional',
            'jamOperasional' => $jamOperasional,
            'lapangan' => $lapangan,
            'hariTerpakai' => $hariTerpakai,
        ]);
    }

    public function update(Request $request, $id_operasional)
    {
        $jamOperasional = JamOperasional::findOrFail($id_operasional);

        $request->merge([
            'jam_buka' => str_replace('.', ':', $request->jam_buka),
            'jam_tutup' => str_replace('.', ':', $request->jam_tutup),
        ]);

        $validated = $request->validate([
            'id_lapangan' => 'required|exists:lapangan,id_lapangan',
            'hari' => 'required|array|min:1',
            'hari.*' => 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
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

        $exists = JamOperasional::where('id_lapangan', $validated['id_lapangan'])
            ->where('hari', $validated['hari'])
            ->where('id_operasional', '!=', $jamOperasional->id_operasional)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'Jam operasional untuk lapangan dan hari tersebut sudah ada.');
        }

        $start = strtotime($validated['jam_buka']);
        $end = strtotime($validated['jam_tutup']);

        $diffMinutes = ($end - $start) / 60;

        if ($diffMinutes % $validated['interval_menit'] !== 0) {
            return back()
                ->withInput()
                ->with('error', 'Interval harus habis membagi rentang jam buka dan tutup.');
        }

        // cek hari bentrok
        $existingHari = JamOperasional::where('id_lapangan', $validated['id_lapangan'])
            ->whereIn('hari', $validated['hari'])
            ->where('id_operasional', '!=', $jamOperasional->id_operasional)
            ->pluck('hari')
            ->toArray();

        if (!empty($existingHari)) {

            $hariTerpakai = collect($existingHari)
                ->map(fn($h) => ucfirst($h))
                ->implode(', ');

            return back()
                ->withInput()
                ->with('error', 'Hari berikut sudah ada: ' . $hariTerpakai);
        }

        try {

            DB::transaction(function () use ($validated, $jamOperasional) {

                // update data utama
                $jamOperasional->update([
                    'id_lapangan' => $validated['id_lapangan'],
                    'hari' => $validated['hari'][0],
                    'jam_buka' => $validated['jam_buka'],
                    'jam_tutup' => $validated['jam_tutup'],
                    'interval_menit' => $validated['interval_menit'],
                    'harga' => $validated['harga'],
                ]);

                // jika pilih lebih dari 1 hari
                foreach ($validated['hari'] as $index => $hari) {

                    if ($index == 0) continue;

                    JamOperasional::create([
                        'id_lapangan' => $validated['id_lapangan'],
                        'hari' => $hari,
                        'jam_buka' => $validated['jam_buka'],
                        'jam_tutup' => $validated['jam_tutup'],
                        'interval_menit' => $validated['interval_menit'],
                        'harga' => $validated['harga'],
                    ]);
                }
            });

            return redirect()
                ->route('admin.jam-operasional.index')
                ->with('success', 'Jam operasional berhasil diperbarui.');
        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function destroy($id_operasional)
    {
        $jamOperasional = JamOperasional::findOrFail($id_operasional);

        try {
            $jamOperasional->delete();

            return redirect()->route('admin.jam-operasional.index')
                ->with('success', 'Jam operasional berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus jam operasional: ' . $e->getMessage());
        }
    }
}
