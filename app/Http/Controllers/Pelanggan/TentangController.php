<?php


namespace App\Http\Controllers\Pelanggan;


use App\Http\Controllers\Controller;


class TentangController extends Controller
{
    public function tentang()
    {
        return view('pelanggan.tentang');
    }
}
