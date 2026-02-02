<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengeluaranUmumController extends Controller
{
    public function index()
    {
        $pengeluaran = PengeluaranUmum::latest()->get();
        return view('pages.pengeluaran-umum.index', [
            'title' => 'Data Pengeluaran Umum',
            'pengeluaran' => $pengeluaran
        ]);
    }

    public function create()
    {
        // Generate Auto Code CG-XXX
        $count = PengeluaranUmum::count() + 1;
        $kodePengeluaran = 'CG-' . str_pad($count, 6, '0', STR_PAD_LEFT);

        return view('pages.pengeluaran-umum.create', [
            'title' => 'Tambah Pengeluaran Umum',
            'kodePengeluaran' => $kodePengeluaran
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_pengeluaran' => 'required|unique:pengeluaran_umums,kode_pengeluaran',
            'tanggal_pengeluaran' => 'required|date',
            'jenis_pengeluaran' => 'required|string',
            'nama_pengeluaran' => 'required|string',
            'jumlah_pengeluaran' => 'required|numeric',
            'catatan_pengeluaran' => 'nullable|string',
            'bukti_pengeluaran' => 'nullable|image|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('bukti_pengeluaran')) {
            $path = $request->file('bukti_pengeluaran')->store('bukti_pengeluaran', 'public');
        }

        PengeluaranUmum::create([
            'kode_pengeluaran' => $validated['kode_pengeluaran'],
            'tanggal_pengeluaran' => $validated['tanggal_pengeluaran'],
            'jenis_pengeluaran' => $validated['jenis_pengeluaran'],
            'nama_pengeluaran' => $validated['nama_pengeluaran'],
            'jumlah_pengeluaran' => $validated['jumlah_pengeluaran'],
            'catatan_pengeluaran' => $validated['catatan_pengeluaran'],
            'bukti_pengeluaran' => $path
        ]);

        return redirect()->route('pengeluaran-umum.index')->with('success', 'Pengeluaran berhasil ditambahkan');
    }
}
