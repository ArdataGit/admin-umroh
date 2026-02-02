<?php

namespace App\Http\Controllers;

use App\Models\PembayaranLayanan;
use Illuminate\Http\Request;

class PembayaranLayananController extends Controller
{
    public function index()
    {
        $pembayarans = PembayaranLayanan::with(['transaksiLayanan.pelanggan'])
            ->latest()
            ->get();

        return view('pages.pembayaran-layanan.index', [
            'title' => 'Data Pembayaran Layanan',
            'pembayarans' => $pembayarans
        ]);
    }
}
