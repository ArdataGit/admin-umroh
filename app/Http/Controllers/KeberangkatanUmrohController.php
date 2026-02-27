<?php

namespace App\Http\Controllers;

use App\Models\KeberangkatanUmroh;
use App\Models\PaketUmroh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class KeberangkatanUmrohController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->name === 'super-admin') {
            return;
        }

        $permissions = $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        if (!in_array('/keberangkatan-umroh.' . $action, $permissions)) {
            if (request()->wantsJson()) {
                abort(403, 'Unauthorized action.');
            }
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }
    public function index()
    {
        $keberangkatan = KeberangkatanUmroh::with(['paketUmroh.maskapai'])->latest()->get();

        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/keberangkatan-umroh.create', $permissions);
        $canEdit = $isAdmin || in_array('/keberangkatan-umroh.edit', $permissions);
        $canDelete = $isAdmin || in_array('/keberangkatan-umroh.delete', $permissions);

        return view('pages.keberangkatan-umroh.index', [
            'title' => 'Keberangkatan Umroh',
            'keberangkatan' => $keberangkatan,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');

        $lastKeberangkatan = KeberangkatanUmroh::latest()->first();
        $nextId = $lastKeberangkatan ? ($lastKeberangkatan->id + 1) : 1;
        // Format KU-XXX
        $kodeKeberangkatan = 'KU-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('pages.keberangkatan-umroh.create', [
            'title' => 'Tambah Keberangkatan',
            'paketUmrohs' => PaketUmroh::all(),
            'kodeKeberangkatan' => $kodeKeberangkatan
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');

        $validated = $request->validate([
            'kode_keberangkatan' => 'required|unique:keberangkatan_umrohs,kode_keberangkatan',
            'paket_umroh_id' => 'required|exists:paket_umrohs,id',
            'status_keberangkatan' => 'required|in:active,completed',
            'catatan' => 'nullable|string'
        ]);

        // Fetch source data to ensure integrity/snapshotting
        $paket = PaketUmroh::findOrFail($validated['paket_umroh_id']);

        try {
            KeberangkatanUmroh::create([
                'kode_keberangkatan' => $validated['kode_keberangkatan'],
                'paket_umroh_id' => $validated['paket_umroh_id'],
                'nama_keberangkatan' => $paket->nama_paket,
                'tanggal_keberangkatan' => $paket->tanggal_keberangkatan,
                'jumlah_hari' => $paket->jumlah_hari,
                'kuota_jamaah' => $paket->kuota_jamaah,
                'status_keberangkatan' => $validated['status_keberangkatan'],
                'catatan' => $validated['catatan']
            ]);

            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Keberangkatan Umroh',
                'action' => 'Create',
                'keterangan' => 'Menambah keberangkatan umroh baru: ' . $paket->nama_paket . ' (' . $validated['kode_keberangkatan'] . ')'
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Keberangkatan berhasil disimpan',
                'redirect' => route('keberangkatan-umroh.index')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $keberangkatan = KeberangkatanUmroh::with('paketUmroh')->findOrFail($id);
        return view('pages.keberangkatan-umroh.show', [
            'title' => 'Detail Keberangkatan',
            'keberangkatan' => $keberangkatan
        ]);
    }

    public function edit($id)
    {
        $this->checkPermission('edit');

        $keberangkatan = KeberangkatanUmroh::findOrFail($id);
        return view('pages.keberangkatan-umroh.edit', [
            'title' => 'Edit Keberangkatan',
            'keberangkatan' => $keberangkatan,
            'paketUmrohs' => PaketUmroh::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');

        $validated = $request->validate([
            'paket_umroh_id' => 'required|exists:paket_umrohs,id',
            'status_keberangkatan' => 'required|in:active,completed',
            'catatan' => 'nullable|string'
        ]);

        $paket = PaketUmroh::findOrFail($validated['paket_umroh_id']);

        try {
            $keberangkatan = KeberangkatanUmroh::findOrFail($id);
            $keberangkatan->update([
                'paket_umroh_id' => $validated['paket_umroh_id'],
                // Update snapshot if package changed
                'nama_keberangkatan' => $paket->nama_paket,
                'tanggal_keberangkatan' => $paket->tanggal_keberangkatan,
                'jumlah_hari' => $paket->jumlah_hari,
                'kuota_jamaah' => $paket->kuota_jamaah,
                
                'status_keberangkatan' => $validated['status_keberangkatan'],
                'catatan' => $validated['catatan']
            ]);

            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Keberangkatan Umroh',
                'action' => 'Update',
                'keterangan' => 'Memperbarui keberangkatan umroh: ' . $paket->nama_paket . ' (' . $keberangkatan->kode_keberangkatan . ')'
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Keberangkatan berhasil diperbarui',
                'redirect' => route('keberangkatan-umroh.index')
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');

        try {
            $keberangkatan = KeberangkatanUmroh::findOrFail($id);
            $namaKeberangkatan = $keberangkatan->nama_keberangkatan;
            $kodeKeberangkatan = $keberangkatan->kode_keberangkatan;

            $keberangkatan->delete();

            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Keberangkatan Umroh',
                'action' => 'Delete',
                'keterangan' => 'Menghapus keberangkatan umroh: ' . $namaKeberangkatan . ' (' . $kodeKeberangkatan . ')'
            ]);

            return response()->json(['success' => true, 'message' => 'Keberangkatan berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }
}
