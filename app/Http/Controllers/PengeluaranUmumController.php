<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengeluaranUmumController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        
        if ($user->role->nama_role === 'super-admin') {
            return true;
        }

        $permission = $user->role->permissions()
            ->where('permission_path', '/pengeluaran-umum.' . $action)
            ->exists();

        if (!$permission) {
            abort(403, 'Anda tidak memiliki akses untuk ' . $action . ' data pengeluaran umum');
        }

        return true;
    }

    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role->nama_role === 'super-admin';
        
        $canCreate = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pengeluaran-umum.create')->exists();
        $canEdit = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pengeluaran-umum.edit')->exists();
        $canDelete = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pengeluaran-umum.delete')->exists();

        $pengeluaran = PengeluaranUmum::latest()->get();
        return view('pages.pengeluaran-umum.index', [
            'title' => 'Data Pengeluaran Umum',
            'pengeluaran' => $pengeluaran,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        
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
        $this->checkPermission('create');
        
        $validated = $request->validate([
            'kode_pengeluaran' => 'required|unique:pengeluaran_umums,kode_pengeluaran',
            'tanggal_pengeluaran' => 'required|date',
            'jenis_pengeluaran' => 'required|string',
            'nama_pengeluaran' => 'required|string',
            'jumlah_pengeluaran' => 'required|numeric',
            'catatan_pengeluaran' => 'nullable|string',
            'bukti_pengeluaran' => 'nullable|image'
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
    public function show($id)
    {
        $pengeluaran = PengeluaranUmum::findOrFail($id);
        return view('pages.pengeluaran-umum.show', [
            'title' => 'Detail Pengeluaran Umum',
            'pengeluaran' => $pengeluaran
        ]);
    }

    public function edit($id)
    {
        $this->checkPermission('edit');
        
        $pengeluaran = PengeluaranUmum::findOrFail($id);
        return view('pages.pengeluaran-umum.edit', [
            'title' => 'Edit Pengeluaran Umum',
            'pengeluaran' => $pengeluaran
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');
        
        $pengeluaran = PengeluaranUmum::findOrFail($id);

        $validated = $request->validate([
            'tanggal_pengeluaran' => 'required|date',
            'jenis_pengeluaran' => 'required|string',
            'nama_pengeluaran' => 'required|string',
            'jumlah_pengeluaran' => 'required|numeric',
            'catatan_pengeluaran' => 'nullable|string',
            'bukti_pengeluaran' => 'nullable|image'
        ]);

        $path = $pengeluaran->bukti_pengeluaran;
        if ($request->hasFile('bukti_pengeluaran')) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('bukti_pengeluaran')->store('bukti_pengeluaran', 'public');
        }

        $pengeluaran->update([
            'tanggal_pengeluaran' => $validated['tanggal_pengeluaran'],
            'jenis_pengeluaran' => $validated['jenis_pengeluaran'],
            'nama_pengeluaran' => $validated['nama_pengeluaran'],
            'jumlah_pengeluaran' => $validated['jumlah_pengeluaran'],
            'catatan_pengeluaran' => $validated['catatan_pengeluaran'],
            'bukti_pengeluaran' => $path
        ]);

        return redirect()->route('pengeluaran-umum.index')->with('success', 'Pengeluaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');
        
        $pengeluaran = PengeluaranUmum::findOrFail($id);
        
        if ($pengeluaran->bukti_pengeluaran && Storage::disk('public')->exists($pengeluaran->bukti_pengeluaran)) {
            Storage::disk('public')->delete($pengeluaran->bukti_pengeluaran);
        }

        $pengeluaran->delete();

        return redirect()->route('pengeluaran-umum.index')->with('success', 'Pengeluaran berhasil dihapus');
    }
}
