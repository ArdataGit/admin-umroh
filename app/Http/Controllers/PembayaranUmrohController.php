<?php

namespace App\Http\Controllers;

use App\Models\PembayaranUmroh;
use Illuminate\Http\Request;

class PembayaranUmrohController extends Controller
{
    public function index()
    {
        $pembayarans = PembayaranUmroh::with(['customerUmroh.jamaah', 'customerUmroh.keberangkatanUmroh', 'customerUmroh.agent'])
            ->latest()
            ->get();

        return view('pages.pembayaran-umroh.index', [
            'title' => 'Data Pembayaran Umroh',
            'pembayarans' => $pembayarans
        ]);
    }
}
