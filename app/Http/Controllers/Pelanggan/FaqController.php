<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;

class FaqController extends Controller
{

    public function faq()
    {
        return view('pelanggan.faq');
    }
}
