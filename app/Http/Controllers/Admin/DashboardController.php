<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Untuk sementara tampilkan view dashboard admin.
        // Nanti bisa ditambahkan statistik (total user, pemesanan, omzet)
        return view('admin.dashboard.index', [
            'title' => 'Dashboard Admin'
        ]);
    }
}
