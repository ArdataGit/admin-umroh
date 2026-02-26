<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranHaji;
use App\Models\KeberangkatanHaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengeluaranHajiController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        
        if ($user->role->nama_role === 'super-admin') {
            return true;
        }

        $permission = $user->role->permissions()
            ->where('permission_path', '/pengeluaran-haji.' . $action)
            ->exists();

        if (!$permission) {
            abort(403, 'Anda tidak memiliki akses untuk ' . $action . ' data pengeluaran haji');
        }

        return true;
    }

    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role->nama_role === 'super-admin';
        
        $canCreate = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pengeluaran-haji.create')->exists();
        $canEdit = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pengeluaran-haji.edit')->exists();
        $canDelete = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pengeluaran-haji.delete')->exists();

        $pengeluaran = PengeluaranHaji::with('keberangkatanHaji')->latest()->get();
        return view('pages.pengeluaran-haji.index', [
            'title' => 'Data Pengeluaran Haji',
            'pengeluaran' => $pengeluaran,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        
        $keberangkatans = KeberangkatanHaji::with('paketHaji')->where('status_keberangkatan', 'active')->get();
        // Generate Auto Code CH-XXX
        $count = PengeluaranHaji::count() + 1;
        $kodePengeluaran = 'CH-' . str_pad($count, 6, '0', STR_PAD_LEFT);

        return view('pages.pengeluaran-haji.create', [
            'title' => 'Tambah Pengeluaran Haji',
            'keberangkatans' => $keberangkatans,
            'kodePengeluaran' => $kodePengeluaran
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');
        
        $validated = $request->validate([
            'keberangkatan_haji_id' => 'required|exists:keberangkatan_hajis,id',
            'kode_pengeluaran' => 'required|unique:pengeluaran_hajis,kode_pengeluaran',
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

        PengeluaranHaji::create([
            'keberangkatan_haji_id' => $validated['keberangkatan_haji_id'],
            'kode_pengeluaran' => $validated['kode_pengeluaran'],
            'tanggal_pengeluaran' => $validated['tanggal_pengeluaran'],
            'jenis_pengeluaran' => $validated['jenis_pengeluaran'],
            'nama_pengeluaran' => $validated['nama_pengeluaran'],
            'jumlah_pengeluaran' => $validated['jumlah_pengeluaran'],
            'catatan_pengeluaran' => $validated['catatan_pengeluaran'],
            'bukti_pengeluaran' => $path
        ]);

        return redirect()->route('pengeluaran-haji.index')->with('success', 'Pengeluaran berhasil ditambahkan');
    }
}
