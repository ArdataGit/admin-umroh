<?php

namespace App\Http\Controllers;

use App\Models\PembayaranTiket;
use Illuminate\Http\Request;

class PembayaranTiketController extends Controller
{
    public function index()
    {
        $pembayarans = PembayaranTiket::with(['transaksiTiket.pelanggan'])
            ->latest()
            ->get();

        return view('pages.pembayaran-tiket.index', [
            'title' => 'Data Pembayaran Tiket',
            'pembayarans' => $pembayarans
        ]);
    }
}
