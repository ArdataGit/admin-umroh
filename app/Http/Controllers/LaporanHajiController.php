<?php

namespace App\Http\Controllers;

use App\Models\KeberangkatanHaji;
use Illuminate\Http\Request;

class LaporanHajiController extends Controller
{
    public function index()
    {
        $keberangkatans = KeberangkatanHaji::with(['paketHaji'])
            ->withCount('customerHajis')
            ->latest('tanggal_keberangkatan')
            ->get();
        
        return view('pages.laporan-haji.index', [
            'title' => 'Laporan Haji',
            'keberangkatans' => $keberangkatans
        ]);
    }

    public function show($id)
    {
        $keberangkatan = KeberangkatanHaji::with(['paketHaji', 'customerHajis.jamaah'])
            ->findOrFail($id);

        return view('pages.laporan-haji.show', [
            'title' => 'Detail Manifest Keberangkatan Haji',
            'keberangkatan' => $keberangkatan
        ]);
    }
}
