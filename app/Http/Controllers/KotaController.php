<?php

namespace App\Http\Controllers;

use App\Services\KotaService;
use Illuminate\Http\Request;

class KotaController extends Controller
{
    protected $kotaService;

    public function __construct(KotaService $kotaService)
    {
        $this->kotaService = $kotaService;
    }

    private function checkPermission($action)
    {
        $user = auth()->user();
        
        if ($user->role->nama_role === 'super-admin') {
            return true;
        }

        $permission = $user->role->permissions()
            ->where('permission_path', '/data-kota.' . $action)
            ->exists();

        if (!$permission) {
            abort(403, 'Anda tidak memiliki akses untuk ' . $action . ' data kota');
        }

        return true;
    }

    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role->nama_role === 'super-admin';
        
        $canCreate = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/data-kota.create')->exists();
        $canEdit = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/data-kota.edit')->exists();
        $canDelete = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/data-kota.delete')->exists();

        $kotas = $this->kotaService->getAll();
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

        return redirect()->route('data-kota.index')->with('success', 'Data kota berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');
        
        $this->kotaService->delete($id);
        return response()->json(['success' => true]);
    }
}
