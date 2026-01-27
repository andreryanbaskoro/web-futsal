<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;

class SyaratController extends Controller
{

    public function syarat()
    {
        return view('pelanggan.syarat');
    }
}
