<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PelangganService;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->name === 'super-admin') {
            return;
        }

        $permissions = $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        if (!in_array('/data-pelanggan.' . $action, $permissions)) {
            if (request()->wantsJson()) {
                abort(403, 'Unauthorized action.');
            }
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }
    protected $pelangganService;

    public function __construct(PelangganService $pelangganService)
    {
        $this->pelangganService = $pelangganService;
    }

    public function index()
    {
        $dataPelanggan = $this->pelangganService->getAll();

        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/data-pelanggan.create', $permissions);
        $canEdit = $isAdmin || in_array('/data-pelanggan.edit', $permissions);
        $canDelete = $isAdmin || in_array('/data-pelanggan.delete', $permissions);

        return view('pages.data-pelanggan.index', [
            'title' => 'Data Pelanggan',
            'dataPelanggan' => $dataPelanggan,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
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
        $this->checkPermission('create');
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
        $this->checkPermission('edit');
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
        $this->checkPermission('edit');
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
        $this->checkPermission('delete');
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
