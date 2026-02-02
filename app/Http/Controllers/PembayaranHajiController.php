<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PembayaranHajiController extends Controller
{
    public function index()
    {
        $pembayarans = \App\Models\PembayaranHaji::with(['customerHaji.jamaah', 'customerHaji.keberangkatanHaji'])
            ->latest()
            ->get();

        return view('pages.pembayaran-haji.index', [
            'title' => 'Data Pembayaran Haji',
            'pembayarans' => $pembayarans
        ]);
    }
}
