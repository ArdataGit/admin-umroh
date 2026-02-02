<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PelangganService;

class PelangganController extends Controller
{
    protected $pelangganService;

    public function __construct(PelangganService $pelangganService)
    {
        $this->pelangganService = $pelangganService;
    }

    public function index()
    {
        $dataPelanggan = $this->pelangganService->getAll();
        return view('pages.data-pelanggan.index', ['title' => 'Data Pelanggan', 'dataPelanggan' => $dataPelanggan]);
    }

    public function create()
    {
        // Auto-generate kode_pelanggan: M-001, M-002, etc.
        $lastPelanggan = \App\Models\Pelanggan::orderBy('id', 'desc')->first();
        $lastNumber = $lastPelanggan ? intval(substr($lastPelanggan->kode_pelanggan, 2)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $kodePelanggan = 'M-' . $newNumber;

        return view('pages.data-pelanggan.create', [
            'title' => 'Tambah Data Pelanggan',
            'kodePelanggan' => $kodePelanggan
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_pelanggan' => 'required|string|unique:pelanggans,kode_pelanggan',
            'nama_pelanggan' => 'required|string|max:255',
            'kontak_pelanggan' => 'required|string|max:20',
            'email_pelanggan' => 'required|email|unique:pelanggans,email_pelanggan',
            'kabupaten_kota' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status_pelanggan' => 'required|in:Active,Non Active',
            'alamat_pelanggan' => 'required|string',
            'catatan_pelanggan' => 'nullable|string',
            'foto_pelanggan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $this->pelangganService->create($validated);

        return redirect()->route('data-pelanggan')->with('success', 'Data pelanggan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pelanggan = $this->pelangganService->getById($id);

        if (!$pelanggan) {
            return redirect()->route('data-pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        return view('pages.data-pelanggan.edit', [
            'title' => 'Edit Data Pelanggan',
            'pelanggan' => $pelanggan
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'kontak_pelanggan' => 'required|string|max:20',
            'email_pelanggan' => 'required|email|unique:pelanggans,email_pelanggan,' . $id,
            'kabupaten_kota' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status_pelanggan' => 'required|in:Active,Non Active',
            'alamat_pelanggan' => 'required|string',
            'catatan_pelanggan' => 'nullable|string',
            'foto_pelanggan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $pelanggan = $this->pelangganService->update($id, $validated);

        if (!$pelanggan) {
            return redirect()->route('data-pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        return redirect()->route('data-pelanggan')->with('success', 'Data pelanggan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $deleted = $this->pelangganService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Data pelanggan tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Data pelanggan berhasil dihapus']);
    }

    public function show($id)
    {
        $pelanggan = $this->pelangganService->getById($id);

        if (!$pelanggan) {
            return redirect()->route('data-pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        return view('pages.data-pelanggan.show', [
            'title' => 'Detail Data Pelanggan',
            'pelanggan' => $pelanggan
        ]);
    }

    public function printData()
    {
        $pelanggans = $this->pelangganService->getAll();
        return view('pages.data-pelanggan.print', [
            'pelanggans' => $pelanggans,
            'title' => 'Laporan Data Pelanggan'
        ]);
    }
}
