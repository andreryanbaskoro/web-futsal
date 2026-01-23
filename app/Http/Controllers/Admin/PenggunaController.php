<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index()
    {
        $users = Pengguna::orderBy('created_at', 'desc')->get();

        return view('admin.pengguna.index', [
            'title' => 'Data Pengguna',
            'users' => $users,
        ]);
    }

    /** ================= CREATE ================= */
    public function create()
    {
        return view('admin.pengguna.create', [
            'title' => 'Tambah Pengguna',
        ]);
    }

    public function store(Request $request)
    {
        // VALIDASI FORM DASAR + PESAN KHUSUS
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:pengguna,email',
            'password' => 'required|min:6',
            'no_hp'    => 'nullable|digits_between:10,15',
            'peran'    => 'required|in:admin,owner,pelanggan',
            'status'   => 'required|in:active,inactive,suspended',
        ], [
            'nama.required'     => 'Kolom nama wajib diisi.',
            'nama.max'          => 'Kolom nama maksimal 255 karakter.',
            'email.required'    => 'Kolom email wajib diisi.',
            'email.email'       => 'Email tidak valid.',
            'email.unique'      => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'no_hp.digits_between' => 'Nomor HP harus antara 10-15 digit.',
            'peran.required'    => 'Kolom peran wajib diisi.',
            'peran.in'          => 'Peran tidak valid.',
            'status.required'   => 'Kolom status wajib diisi.',
            'status.in'         => 'Status tidak valid.',
        ]);

        // LOGIKA BISNIS: Maksimal 5 admin
        if ($validated['peran'] === 'admin') {
            $adminCount = Pengguna::where('peran', 'admin')->count();
            if ($adminCount >= 5) {
                return back()->withInput()->with('error', 'Jumlah admin maksimum sudah tercapai.');
            }
        }

        try {
            Pengguna::create([
                'nama'     => $validated['nama'],
                'email'    => $validated['email'],
                'password' => bcrypt($validated['password']),
                'no_hp'    => $validated['no_hp'] ?? null,
                'peran'    => $validated['peran'],
                'status'   => $validated['status'],
            ]);

            return redirect()->route('admin.pengguna.index')
                ->with('success', 'Pengguna berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage());
        }
    }


    /** ================= EDIT ================= */
    public function edit(Pengguna $pengguna)
    {
        return view('admin.pengguna.edit', [
            'title' => 'Edit Pengguna',
            'pengguna' => $pengguna,
        ]);
    }

    /** ================= EDIT ================= */
    public function update(Request $request, Pengguna $pengguna)
    {
        // VALIDASI FORM DASAR + PESAN KHUSUS
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|unique:pengguna,email,' . $pengguna->id_pengguna . ',id_pengguna',
            'password' => 'nullable|min:6', // opsional
            'no_hp'  => 'nullable|digits_between:10,15',
            'peran'  => 'required|in:admin,owner,pelanggan',
            'status' => 'required|in:active,inactive,suspended',
        ], [
            'nama.required'     => 'Kolom nama wajib diisi.',
            'nama.max'          => 'Kolom nama maksimal 255 karakter.',
            'email.required'    => 'Kolom email wajib diisi.',
            'email.email'       => 'Email tidak valid.',
            'email.unique'      => 'Email sudah digunakan.',
            'password.min'      => 'Password minimal 6 karakter.',
            'no_hp.digits_between' => 'Nomor HP harus antara 10-15 digit.',
            'peran.required'    => 'Kolom peran wajib diisi.',
            'peran.in'          => 'Peran tidak valid.',
            'status.required'   => 'Kolom status wajib diisi.',
            'status.in'         => 'Status tidak valid.',
        ]);

        // LOGIKA BISNIS: Minimal 1 admin tersisa
        if ($pengguna->peran === 'admin' && $validated['peran'] !== 'admin') {
            $adminCount = Pengguna::where('peran', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->withInput()->with('error', 'Minimal harus ada 1 admin tersisa.');
            }
        }

        try {
            // ambil data kecuali password
            $data = [
                'nama'   => $validated['nama'],
                'email'  => $validated['email'],
                'no_hp'  => $validated['no_hp'] ?? null,
                'peran'  => $validated['peran'],
                'status' => $validated['status'],
            ];

            // jika password diisi
            if (!empty($validated['password'])) {
                $data['password'] = bcrypt($validated['password']);
            }

            $pengguna->update($data);

            return redirect()->route('admin.pengguna.index')
                ->with('success', 'Pengguna berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }


    /** ================= DELETE ================= */
    public function destroy(Pengguna $pengguna)
    {
        // VALIDASI BISNIS
        if ($pengguna->peran === 'admin') {
            $adminCount = Pengguna::where('peran', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Minimal harus ada 1 admin tersisa.');
            }
        }

        try {
            $pengguna->delete();

            return redirect()->route('admin.pengguna.index')
                ->with('success', 'Pengguna berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}
