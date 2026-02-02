<?php

namespace App\Http\Controllers;

use App\Models\PemasukanUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PemasukanUmumController extends Controller
{
    public function index()
    {
        $pemasukan = PemasukanUmum::latest()->get();
        return view('pages.pemasukan-umum.index', [
            'title' => 'Data Pemasukan Umum',
            'pemasukan' => $pemasukan
        ]);
    }

    public function create()
    {
        // Generate Auto Code IG-XXX
        $count = PemasukanUmum::count() + 1;
        $kodePemasukan = 'IG-' . str_pad($count, 6, '0', STR_PAD_LEFT);

        return view('pages.pemasukan-umum.create', [
            'title' => 'Tambah Pemasukan Umum',
            'kodePemasukan' => $kodePemasukan
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_pemasukan' => 'required|unique:pemasukan_umums,kode_pemasukan',
            'tanggal_pemasukan' => 'required|date',
            'jenis_pemasukan' => 'required|string',
            'nama_pemasukan' => 'required|string',
            'jumlah_pemasukan' => 'required|numeric',
            'catatan_pemasukan' => 'nullable|string',
            'bukti_pemasukan' => 'nullable|image|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('bukti_pemasukan')) {
            $path = $request->file('bukti_pemasukan')->store('bukti_pemasukan', 'public');
        }

        PemasukanUmum::create([
            'kode_pemasukan' => $validated['kode_pemasukan'],
            'tanggal_pemasukan' => $validated['tanggal_pemasukan'],
            'jenis_pemasukan' => $validated['jenis_pemasukan'],
            'nama_pemasukan' => $validated['nama_pemasukan'],
            'jumlah_pemasukan' => $validated['jumlah_pemasukan'],
            'catatan_pemasukan' => $validated['catatan_pemasukan'],
            'bukti_pemasukan' => $path
        ]);

        return redirect()->route('pemasukan-umum.index')->with('success', 'Pemasukan berhasil ditambahkan');
    }
}
