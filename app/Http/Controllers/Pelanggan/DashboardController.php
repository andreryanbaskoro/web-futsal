<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard pelanggan
     */
    public function index()
    {
        return view('pelanggan.dashboard', [
            'title' => 'Dashboard Pelanggan'
        ]);
    }
}
