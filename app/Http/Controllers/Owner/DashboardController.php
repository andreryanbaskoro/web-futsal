<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard owner
     */
    public function index()
    {
        return view('owner.dashboard', [
            'title' => 'Dashboard Owner'
        ]);
    }
}
