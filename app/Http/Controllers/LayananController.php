<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LayananService;

class LayananController extends Controller
{
    protected $layananService;

    public function __construct(LayananService $layananService)
    {
        $this->layananService = $layananService;
    }

    public function index()
    {
        $dataLayanan = $this->layananService->getAll();
        return view('pages.data-layanan.index', ['title' => 'Data Layanan', 'dataLayanan' => $dataLayanan]);
    }

    public function create()
    {
        // Auto-generate kode_layanan: SR-001, SR-002, etc.
        $lastLayanan = \App\Models\Layanan::orderBy('id', 'desc')->first();
        $lastNumber = $lastLayanan ? intval(substr($lastLayanan->kode_layanan, 3)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $kodeLayanan = 'SR-' . $newNumber;

        return view('pages.data-layanan.create', [
            'title' => 'Tambah Data Layanan',
            'kodeLayanan' => $kodeLayanan
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_layanan' => 'required|string|unique:layanans,kode_layanan',
            'jenis_layanan' => 'required|in:Pesawat,Hotel,Visa,Transport,Handling,Tour,Layanan,Lainnya',
            'nama_layanan' => 'required|string|max:255',
            'satuan_unit' => 'required|in:Pcs,Set,Pack,Dus,Lot,Pax,Room,Seat',
            'harga_modal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'status_layanan' => 'required|in:Active,Non Active',
            'catatan_layanan' => 'nullable|string',
            'foto_layanan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $this->layananService->create($validated);

        return redirect()->route('data-layanan')->with('success', 'Data layanan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $layanan = $this->layananService->getById($id);

        if (!$layanan) {
            return redirect()->route('data-layanan')->with('error', 'Data layanan tidak ditemukan');
        }

        return view('pages.data-layanan.edit', [
            'title' => 'Edit Data Layanan',
            'layanan' => $layanan
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis_layanan' => 'required|in:Pesawat,Hotel,Visa,Transport,Handling,Tour,Layanan,Lainnya',
            'nama_layanan' => 'required|string|max:255',
            'satuan_unit' => 'required|in:Pcs,Set,Pack,Dus,Lot,Pax,Room,Seat',
            'harga_modal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'status_layanan' => 'required|in:Active,Non Active',
            'catatan_layanan' => 'nullable|string',
            'foto_layanan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $layanan = $this->layananService->update($id, $validated);

        if (!$layanan) {
            return redirect()->route('data-layanan')->with('error', 'Data layanan tidak ditemukan');
        }

        return redirect()->route('data-layanan')->with('success', 'Data layanan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $deleted = $this->layananService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Data layanan tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Data layanan berhasil dihapus']);
    }

    public function show($id)
    {
        $layanan = $this->layananService->getById($id);

        if (!$layanan) {
            return redirect()->route('data-layanan')->with('error', 'Data layanan tidak ditemukan');
        }

        return view('pages.data-layanan.show', [
            'title' => 'Detail Data Layanan',
            'layanan' => $layanan
        ]);
    }

    public function printData()
    {
        $layanans = $this->layananService->getAll();
        return view('pages.data-layanan.print', [
            'layanans' => $layanans,
            'title' => 'Laporan Data Layanan'
        ]);
    }
}
