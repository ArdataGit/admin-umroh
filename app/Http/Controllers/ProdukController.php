<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProdukService;

class ProdukController extends Controller
{
    protected $produkService;

    public function __construct(ProdukService $produkService)
    {
        $this->produkService = $produkService;
    }

    public function index()
    {
        $dataProduk = $this->produkService->getAll();
        return view('pages.data-produk.index', ['title' => 'Data Produk', 'dataProduk' => $dataProduk]);
    }

    public function create()
    {
        // Auto-generate kode_produk: PR-001, PR-002, etc.
        $lastProduk = \App\Models\Produk::orderBy('id', 'desc')->first();
        $lastNumber = $lastProduk ? intval(substr($lastProduk->kode_produk, 3)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $kodeProduk = 'PR-' . $newNumber;

        return view('pages.data-produk.create', [
            'title' => 'Tambah Data Produk',
            'kodeProduk' => $kodeProduk
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_produk' => 'required|string|unique:produks,kode_produk',
            'nama_produk' => 'required|string|max:255',
            'standar_stok' => 'required|integer|min:0',
            'aktual_stok' => 'required|integer|min:0',
            'satuan_unit' => 'required|in:Pcs,Set,Pack,Dus,Lot,Pax,Room,Seat',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'catatan_produk' => 'nullable|string',
        ]);

        $this->produkService->create($validated);

        return redirect()->route('data-produk')->with('success', 'Data produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $produk = $this->produkService->getById($id);

        if (!$produk) {
            return redirect()->route('data-produk')->with('error', 'Data produk tidak ditemukan');
        }

        return view('pages.data-produk.edit', [
            'title' => 'Edit Data Produk',
            'produk' => $produk
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'standar_stok' => 'required|integer|min:0',
            'aktual_stok' => 'required|integer|min:0',
            'satuan_unit' => 'required|in:Pcs,Set,Pack,Dus,Lot,Pax,Room,Seat',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'catatan_produk' => 'nullable|string',
        ]);

        $produk = $this->produkService->update($id, $validated);

        if (!$produk) {
            return redirect()->route('data-produk')->with('error', 'Data produk tidak ditemukan');
        }

        return redirect()->route('data-produk')->with('success', 'Data produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $deleted = $this->produkService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Data produk tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Data produk berhasil dihapus']);
    }

    public function show($id)
    {
        $produk = $this->produkService->getById($id);

        if (!$produk) {
            return redirect()->route('data-produk')->with('error', 'Data produk tidak ditemukan');
        }

        return view('pages.data-produk.show', [
            'title' => 'Detail Data Produk',
            'produk' => $produk
        ]);
    }

    public function printData()
    {
        $produks = $this->produkService->getAll();
        return view('pages.data-produk.print', [
            'produks' => $produks,
            'title' => 'Laporan Data Produk'
        ]);
    }
}
