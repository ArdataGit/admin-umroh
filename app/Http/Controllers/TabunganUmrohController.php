<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TabunganUmrohService;
use App\Models\TabunganUmroh;
use App\Models\Jamaah;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class TabunganUmrohController extends Controller
{
    protected $tabunganUmrohService;

    public function __construct(TabunganUmrohService $tabunganUmrohService)
    {
        $this->tabunganUmrohService = $tabunganUmrohService;
    }

    public function index()
    {
        $tabunganUmrohs = $this->tabunganUmrohService->getAll();

        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/tabungan-umroh.create', $permissions);
        $canEdit = $isAdmin || in_array('/tabungan-umroh.edit', $permissions);
        $canDelete = $isAdmin || in_array('/tabungan-umroh.delete', $permissions);

        return view('pages.tabungan-umroh.index', [
            'title' => 'Data Tabungan Umroh',
            'tabunganUmrohs' => $tabunganUmrohs,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        // Auto-generate kode_tabungan: TU-001, TU-002, etc.
        $lastTabungan = TabunganUmroh::orderBy('id', 'desc')->first();
        $lastNumber = $lastTabungan ? intval(substr($lastTabungan->kode_tabungan, 3)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $kodeTabungan = 'TU-' . $newNumber;

        $jamaahs = Jamaah::all();

        return view('pages.tabungan-umroh.create', [
            'title' => 'Tambah Tabungan Umroh',
            'kodeTabungan' => $kodeTabungan,
            'jamaahs' => $jamaahs
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');
        $validated = $request->validate([
            'kode_tabungan' => 'required|string|unique:tabungan_umrohs,kode_tabungan',
            'jamaah_id' => 'required|exists:jamaahs,id',
            'tanggal_pendaftaran' => 'required|date|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:9999-12-31',
            'bank_tabungan' => 'required|in:Bank Travel,Bank BSI,Bank Muamalat,Bank BRI,Bank BNI,Bank BCA,Bank Mandiri',
            'rekening_tabungan' => 'required|string|max:50',
            'status_tabungan' => 'required|in:active,non-active',
            'setoran_tabungan' => 'required|numeric',
            'metode_pembayaran' => 'required|in:Cash,Transfer,Debit,QRIS,Other',
            'catatan_pembayaran' => 'nullable|string'
        ]);

        $this->tabunganUmrohService->create($validated);

        $jamaah = Jamaah::find($validated['jamaah_id']);
        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Tabungan Umroh',
            'action' => 'Create',
            'keterangan' => 'Menambahkan tabungan umroh baru: ' . $validated['kode_tabungan'] . ' untuk jamaah: ' . ($jamaah ? $jamaah->nama_jamaah : 'ID ' . $validated['jamaah_id'])
        ]);

        return redirect()->route('tabungan-umroh')->with('success', 'Tabungan umroh berhasil ditambahkan');
    }

    public function edit($id)
    {
        $this->checkPermission('edit');
        $tabungan = $this->tabunganUmrohService->getById($id);
        if (!$tabungan) {
            return redirect()->route('tabungan-umroh')->with('error', 'Tabungan umroh tidak ditemukan');
        }

        $jamaahs = Jamaah::all();

        return view('pages.tabungan-umroh.edit', [
            'title' => 'Edit Tabungan Umroh',
            'tabungan' => $tabungan,
            'jamaahs' => $jamaahs
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');
        $validated = $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'tanggal_pendaftaran' => 'required|date|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:9999-12-31',
            'bank_tabungan' => 'required|in:Bank Travel,Bank BSI,Bank Muamalat,Bank BRI,Bank BNI,Bank BCA,Bank Mandiri',
            'rekening_tabungan' => 'required|string|max:50',
            'status_tabungan' => 'required|in:active,non-active',
            'setoran_tabungan' => 'required|numeric',
            'metode_pembayaran' => 'required|in:Cash,Transfer,Debit,QRIS,Other',
            'catatan_pembayaran' => 'nullable|string'
        ]);

        $this->tabunganUmrohService->update($id, $validated);

        $tabungan = $this->tabunganUmrohService->getById($id);
        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Tabungan Umroh',
            'action' => 'Update',
            'keterangan' => 'Memperbarui tabungan umroh: ' . ($tabungan ? $tabungan->kode_tabungan : 'ID ' . $id)
        ]);

        return redirect()->route('tabungan-umroh')->with('success', 'Tabungan umroh berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');
        $tabungan = $this->tabunganUmrohService->getById($id);
        $kodeTabungan = $tabungan ? $tabungan->kode_tabungan : 'N/A';

        $deleted = $this->tabunganUmrohService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Tabungan umroh tidak ditemukan'], 404);
        }

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Tabungan Umroh',
            'action' => 'Delete',
            'keterangan' => 'Menghapus tabungan umroh: ' . $kodeTabungan
        ]);

        return response()->json(['success' => true, 'message' => 'Tabungan umroh berhasil dihapus']);
    }

    public function show($id)
    {
        $tabungan = $this->tabunganUmrohService->getById($id);
        if (!$tabungan) {
            return redirect()->route('tabungan-umroh')->with('error', 'Tabungan umroh tidak ditemukan');
        }

        return view('pages.tabungan-umroh.show', [
            'title' => 'Detail Tabungan Umroh',
            'tabungan' => $tabungan
        ]);
    }

    private function checkPermission($action)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->name === 'super-admin') {
            return;
        }

        $permissions = $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        if (!in_array('/tabungan-umroh.' . $action, $permissions)) {
            if (request()->wantsJson()) {
                abort(403, 'Unauthorized action.');
            }
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }
}
