<?php

namespace App\Http\Controllers;

use App\Services\KotaService;
use Illuminate\Http\Request;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class KotaController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->name === 'super-admin') {
            return;
        }

        $permissions = $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        if (!in_array('/data-kota.' . $action, $permissions)) {
            if (request()->wantsJson()) {
                abort(403, 'Unauthorized action.');
            }
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }
    protected $kotaService;

    public function __construct(KotaService $kotaService)
    {
        $this->kotaService = $kotaService;
    }

    public function index()
    {
        $kotas = $this->kotaService->getAll();

        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/data-kota.create', $permissions);
        $canEdit = $isAdmin || in_array('/data-kota.edit', $permissions);
        $canDelete = $isAdmin || in_array('/data-kota.delete', $permissions);

        return view('pages.data-kota.index', [
            'title' => 'Data Kota',
            'kotas' => $kotas,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        return view('pages.data-kota.create', [
            'title' => 'Tambah Data Kota'
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');
        $validated = $request->validate([
            'kode_kota' => 'required|string|unique:kotas,kode_kota',
            'nama_kota' => 'required|string|max:255',
        ]);

        $this->kotaService->create($validated);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Kota',
            'action' => 'Create',
            'keterangan' => 'Menambahkan data kota baru: ' . $validated['nama_kota'] . ' (' . $validated['kode_kota'] . ')'
        ]);

        return redirect()->route('data-kota.index')->with('success', 'Data kota berhasil ditambahkan');
    }

    public function show($id)
    {
        $kota = $this->kotaService->getById($id);
        return view('pages.data-kota.show', [
            'title' => 'Detail Kota',
            'kota' => $kota
        ]);
    }

    public function edit($id)
    {
        $this->checkPermission('edit');
        $kota = $this->kotaService->getById($id);
        return view('pages.data-kota.edit', [
            'title' => 'Edit Kota',
            'kota' => $kota
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');
        $validated = $request->validate([
            'kode_kota' => 'required|string|unique:kotas,kode_kota,' . $id,
            'nama_kota' => 'required|string|max:255',
        ]);

        $this->kotaService->update($id, $validated);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Kota',
            'action' => 'Update',
            'keterangan' => 'Memperbarui data kota: ' . $validated['nama_kota'] . ' (' . $validated['kode_kota'] . ')'
        ]);

        return redirect()->route('data-kota.index')->with('success', 'Data kota berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');
        $kota = $this->kotaService->getById($id);
        $namaKota = $kota ? $kota->nama_kota : 'N/A';
        $kodeKota = $kota ? $kota->kode_kota : 'N/A';

        $this->kotaService->delete($id);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Kota',
            'action' => 'Delete',
            'keterangan' => 'Menghapus data kota: ' . $namaKota . ' (' . $kodeKota . ')'
        ]);

        return response()->json(['success' => true]);
    }
}
