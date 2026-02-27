<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PelangganService;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

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
            'foto_pelanggan' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $this->pelangganService->create($validated);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Pelanggan',
            'action' => 'Create',
            'keterangan' => 'Menambahkan data pelanggan baru: ' . $validated['nama_pelanggan'] . ' (' . $validated['kode_pelanggan'] . ')'
        ]);

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
            'foto_pelanggan' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $pelanggan = $this->pelangganService->update($id, $validated);

        if (!$pelanggan) {
            return redirect()->route('data-pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Pelanggan',
            'action' => 'Update',
            'keterangan' => 'Memperbarui data pelanggan: ' . $validated['nama_pelanggan'] . ' (' . $pelanggan->kode_pelanggan . ')'
        ]);

        return redirect()->route('data-pelanggan')->with('success', 'Data pelanggan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pelanggan = $this->pelangganService->getById($id);
        $namaPelanggan = $pelanggan ? $pelanggan->nama_pelanggan : 'N/A';
        $kodePelanggan = $pelanggan ? $pelanggan->kode_pelanggan : 'N/A';

        $deleted = $this->pelangganService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Data pelanggan tidak ditemukan'], 404);
        }

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Pelanggan',
            'action' => 'Delete',
            'keterangan' => 'Menghapus data pelanggan: ' . $namaPelanggan . ' (' . $kodePelanggan . ')'
        ]);

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
