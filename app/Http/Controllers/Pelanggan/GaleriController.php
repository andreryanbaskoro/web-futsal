<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Gallery;

class GaleriController extends Controller
{

    public function index()
    {
        $galleries = Gallery::active()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pelanggan.galeri', compact('galleries'));
    }
}
