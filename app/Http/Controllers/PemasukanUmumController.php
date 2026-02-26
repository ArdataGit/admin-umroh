<?php

namespace App\Http\Controllers;

use App\Models\PemasukanUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PemasukanUmumController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        
        if ($user->role->nama_role === 'super-admin') {
            return true;
        }

        $permission = $user->role->permissions()
            ->where('permission_path', '/pemasukan-umum.' . $action)
            ->exists();

        if (!$permission) {
            abort(403, 'Anda tidak memiliki akses untuk ' . $action . ' data pemasukan umum');
        }

        return true;
    }

    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role->nama_role === 'super-admin';
        
        $canCreate = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pemasukan-umum.create')->exists();
        $canEdit = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pemasukan-umum.edit')->exists();
        $canDelete = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pemasukan-umum.delete')->exists();

        $pemasukan = PemasukanUmum::latest()->get();
        return view('pages.pemasukan-umum.index', [
            'title' => 'Data Pemasukan Umum',
            'pemasukan' => $pemasukan,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        
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
        $this->checkPermission('create');
        
        $validated = $request->validate([
            'kode_pemasukan' => 'required|unique:pemasukan_umums,kode_pemasukan',
            'tanggal_pemasukan' => 'required|date',
            'jenis_pemasukan' => 'required|string',
            'nama_pemasukan' => 'required|string',
            'jumlah_pemasukan' => 'required|numeric',
            'catatan_pemasukan' => 'nullable|string',
            'bukti_pemasukan' => 'nullable|image'
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
