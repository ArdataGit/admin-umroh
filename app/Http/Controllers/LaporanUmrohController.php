<?php

namespace App\Http\Controllers;

use App\Models\KeberangkatanUmroh;
use App\Models\KeberangkatanHaji;
use Illuminate\Http\Request;

class LaporanUmrohController extends Controller
{
    public function index()
    {
        // For now, we only focus on KeberangkatanUmroh as per user detailed request which matches Umroh data structure.
        
        $keberangkatans = KeberangkatanUmroh::with(['paketUmroh'])
            ->withCount('customerUmrohs')
            ->latest('tanggal_keberangkatan')
            ->get();

        // Calculate durations and map data if needed, or do it in view
        
        return view('pages.laporan-umroh.index', [
            'title' => 'Laporan Umroh',
            'keberangkatans' => $keberangkatans
        ]);
    }

    public function show($id)
    {
        $keberangkatan = KeberangkatanUmroh::with(['paketUmroh', 'customerUmrohs.jamaah'])
            ->findOrFail($id);

        return view('pages.laporan-umroh.show', [
            'title' => 'Detail Manifest Keberangkatan',
            'keberangkatan' => $keberangkatan
        ]);
    }
}
