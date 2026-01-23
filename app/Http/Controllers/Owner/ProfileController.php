<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /* =======================
     * HALAMAN PROFILE
     * ======================= */
    public function index()
    {
        return view('owner.profile.index', [
            'title' => 'Profil Saya',
            'user'  => Auth::user(),
        ]);
    }

    /* =======================
     * UPDATE DATA PROFIL
     * ======================= */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate(
            [
                'nama'  => 'required|string|max:100',
                'no_hp' => 'nullable|string|max:20',

                // ADMIN boleh ubah email
                'email' => $user->isOwner()
                    ? [
                        'required',
                        'email',
                        Rule::unique('pengguna', 'email')
                            ->ignore($user->id_pengguna, 'id_pengguna'),
                    ]
                    : 'nullable',
            ],
            [
                'nama.required'  => 'Nama wajib diisi.',
                'nama.max'       => 'Nama maksimal 100 karakter.',
                'no_hp.max'      => 'Nomor HP maksimal 20 karakter.',

                'email.required' => 'Email wajib diisi.',
                'email.email'    => 'Format email tidak valid.',
                'email.unique'   => 'Email sudah digunakan.',
            ]
        );

        $data = [
            'nama'  => $validated['nama'],
            'no_hp' => $validated['no_hp'] ?? null,
        ];

        if ($user->isOwner()) {
            $data['email'] = $validated['email'];
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /* =======================
     * UPDATE PASSWORD
     * ======================= */
    public function updatePassword(Request $request)
    {
        $request->validate(
            [
                'password_lama' => 'required',
                'password'      => [
                    'required',
                    'confirmed',
                    Password::min(8)->letters()->numbers(),
                ],
            ],
            [
                'password_lama.required' => 'Password lama wajib diisi.',
                'password.required'      => 'Password baru wajib diisi.',
                'password.confirmed'     => 'Konfirmasi password tidak cocok.',
                'password.min'           => 'Password minimal 8 karakter.',
            ]
        );

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors([
                'password_lama' => 'Password lama tidak sesuai.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        Auth::logoutOtherDevices($request->password);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
