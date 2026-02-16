<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranUmroh;
use App\Models\KeberangkatanUmroh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengeluaranUmrohController extends Controller
{
    public function index()
    {
        $pengeluaran = PengeluaranUmroh::with('keberangkatanUmroh')->latest()->get();
        return view('pages.pengeluaran-umroh.index', [
            'title' => 'Data Pengeluaran Umroh',
            'pengeluaran' => $pengeluaran
        ]);
    }

    public function create()
    {
        $keberangkatans = KeberangkatanUmroh::with('paketUmroh')->where('status_keberangkatan', 'active')->get();
        // Generate Auto Code CU-XXX
        $count = PengeluaranUmroh::count() + 1;
        $kodePengeluaran = 'CU-' . str_pad($count, 6, '0', STR_PAD_LEFT);

        return view('pages.pengeluaran-umroh.create', [
            'title' => 'Tambah Pengeluaran Umroh',
            'keberangkatans' => $keberangkatans,
            'kodePengeluaran' => $kodePengeluaran
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'keberangkatan_umroh_id' => 'required|exists:keberangkatan_umrohs,id',
            'kode_pengeluaran' => 'required|unique:pengeluaran_umrohs,kode_pengeluaran',
            'tanggal_pengeluaran' => 'required|date',
            'jenis_pengeluaran' => 'required|string',
            'nama_pengeluaran' => 'required|string',
            'jumlah_pengeluaran' => 'required|numeric',
            'catatan_pengeluaran' => 'nullable|string',
            'bukti_pengeluaran' => 'nullable|image'
        ]);

        $path = null;
        if ($request->hasFile('bukti_pengeluaran')) {
            $path = $request->file('bukti_pengeluaran')->store('bukti_pengeluaran', 'public');
        }

        PengeluaranUmroh::create([
            'keberangkatan_umroh_id' => $validated['keberangkatan_umroh_id'],
            'kode_pengeluaran' => $validated['kode_pengeluaran'],
            'tanggal_pengeluaran' => $validated['tanggal_pengeluaran'],
            'jenis_pengeluaran' => $validated['jenis_pengeluaran'],
            'nama_pengeluaran' => $validated['nama_pengeluaran'],
            'jumlah_pengeluaran' => $validated['jumlah_pengeluaran'],
            'catatan_pengeluaran' => $validated['catatan_pengeluaran'],
            'bukti_pengeluaran' => $path
        ]);

        return redirect()->route('pengeluaran-umroh.index')->with('success', 'Pengeluaran berhasil ditambahkan');
    }
}
