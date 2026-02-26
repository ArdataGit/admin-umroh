<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TabunganHajiService;
use App\Models\TabunganHaji;
use App\Models\Jamaah;

class TabunganHajiController extends Controller
{
    protected $tabunganHajiService;

    public function __construct(TabunganHajiService $tabunganHajiService)
    {
        $this->tabunganHajiService = $tabunganHajiService;
    }

    private function checkPermission($action)
    {
        $user = auth()->user();
        
        // Super-admin has full access
        if ($user->role && $user->role->name === 'super-admin') {
            return true;
        }

        $permission = "/tabungan-haji.{$action}";
        $hasPermission = $user->role && $user->role->permissions()
            ->where('menu_path', $permission)
            ->exists();

        if (!$hasPermission) {
            abort(403, 'Anda tidak memiliki akses untuk ' . $action . ' data tabungan haji.');
        }

        return true;
    }

    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role && $user->role->name === 'super-admin';
        
        $canCreate = $isSuperAdmin || ($user->role && $user->role->permissions()->where('menu_path', '/tabungan-haji.create')->exists());
        $canEdit = $isSuperAdmin || ($user->role && $user->role->permissions()->where('menu_path', '/tabungan-haji.edit')->exists());
        $canDelete = $isSuperAdmin || ($user->role && $user->role->permissions()->where('menu_path', '/tabungan-haji.delete')->exists());

        $tabunganHajis = $this->tabunganHajiService->getAll();
        return view('pages.tabungan-haji.index', [
            'title' => 'Data Tabungan Haji',
            'tabunganHajis' => $tabunganHajis,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        
        // Auto-generate kode_tabungan: TH-001, TH-002, etc.
        $lastTabungan = TabunganHaji::orderBy('id', 'desc')->first();
        $lastNumber = $lastTabungan ? intval(substr($lastTabungan->kode_tabungan, 3)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $kodeTabungan = 'TH-' . $newNumber;

        $jamaahs = Jamaah::all();

        return view('pages.tabungan-haji.create', [
            'title' => 'Tambah Tabungan Haji',
            'kodeTabungan' => $kodeTabungan,
            'jamaahs' => $jamaahs
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');
        
        $validated = $request->validate([
            'kode_tabungan' => 'required|string|unique:tabungan_hajis,kode_tabungan',
            'jamaah_id' => 'required|exists:jamaahs,id',
            'tanggal_pendaftaran' => 'required|date|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:9999-12-31',
            'bank_tabungan' => 'required|in:Bank Travel,Bank BSI,Bank Muamalat,Bank BRI,Bank BNI,Bank BCA,Bank Mandiri',
            'rekening_tabungan' => 'required|string|max:50',
            'status_tabungan' => 'required|in:active,non-active',
            'setoran_tabungan' => 'required|numeric',
            'metode_pembayaran' => 'required|in:Cash,Transfer,Debit,QRIS,Other',
            'catatan_pembayaran' => 'nullable|string'
        ]);

        $this->tabunganHajiService->create($validated);

        return redirect()->route('tabungan-haji')->with('success', 'Tabungan haji berhasil ditambahkan');
    }

    public function edit($id)
    {
        $this->checkPermission('edit');
        
        $tabungan = $this->tabunganHajiService->getById($id);
        if (!$tabungan) {
            return redirect()->route('tabungan-haji')->with('error', 'Tabungan haji tidak ditemukan');
        }

        $jamaahs = Jamaah::all();

        return view('pages.tabungan-haji.edit', [
            'title' => 'Edit Tabungan Haji',
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

        $this->tabunganHajiService->update($id, $validated);

        return redirect()->route('tabungan-haji')->with('success', 'Tabungan haji berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');
        
        $deleted = $this->tabunganHajiService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Tabungan haji tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Tabungan haji berhasil dihapus']);
    }

    public function show($id)
    {
        $tabungan = $this->tabunganHajiService->getById($id);
        if (!$tabungan) {
            return redirect()->route('tabungan-haji')->with('error', 'Tabungan haji tidak ditemukan');
        }

        return view('pages.tabungan-haji.show', [
            'title' => 'Detail Tabungan Haji',
            'tabungan' => $tabungan
        ]);
    }
}
