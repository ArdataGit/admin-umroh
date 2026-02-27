<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupplierService;
use App\Models\Supplier;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/data-supplier.create', $permissions);
        $canEdit = $isAdmin || in_array('/data-supplier.edit', $permissions);
        $canDelete = $isAdmin || in_array('/data-supplier.delete', $permissions);

        $dataSupplier = $this->supplierService->getAll();
        return view('pages.data-supplier.index', [
            'title' => 'Data Supplier', 
            'dataSupplier' => $dataSupplier,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        // Auto-generate kode_supplier: S-001, S-002, etc.
        $lastSupplier = Supplier::orderBy('id', 'desc')->first();
        $lastNumber = $lastSupplier ? intval(substr($lastSupplier->kode_supplier, 2)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $kodeSupplier = 'S-' . $newNumber;

        return view('pages.data-supplier.create', [
            'title' => 'Tambah Data Supplier',
            'kodeSupplier' => $kodeSupplier
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');
        $validated = $request->validate([
            'kode_supplier' => 'required|string|unique:suppliers,kode_supplier',
            'nama_supplier' => 'required|string|max:255',
            'kontak_supplier' => 'nullable|string|max:255',
            'email_supplier' => 'nullable|email|max:255',
            'kota_provinsi' => 'nullable|string|max:255',
            'alamat_supplier' => 'nullable|string',
            'catatan_supplier' => 'nullable|string',
        ]);

        $this->supplierService->create($validated);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Supplier',
            'action' => 'Create',
            'keterangan' => 'Menambahkan supplier baru: ' . $validated['nama_supplier'] . ' (' . $validated['kode_supplier'] . ')'
        ]);

        return redirect()->route('data-supplier')->with('success', 'Data supplier berhasil ditambahkan');
    }

    public function edit($id)
    {
        $this->checkPermission('edit');
        $supplier = $this->supplierService->getById($id);

        if (!$supplier) {
            return redirect()->route('data-supplier')->with('error', 'Data supplier tidak ditemukan');
        }

        return view('pages.data-supplier.edit', [
            'title' => 'Edit Data Supplier',
            'supplier' => $supplier
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'kontak_supplier' => 'nullable|string|max:255',
            'email_supplier' => 'nullable|email|max:255',
            'kota_provinsi' => 'nullable|string|max:255',
            'alamat_supplier' => 'nullable|string',
            'catatan_supplier' => 'nullable|string',
        ]);

        $supplier = $this->supplierService->update($id, $validated);

        if (!$supplier) {
            return redirect()->route('data-supplier')->with('error', 'Data supplier tidak ditemukan');
        }

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Supplier',
            'action' => 'Update',
            'keterangan' => 'Memperbarui data supplier: ' . $supplier->nama_supplier . ' (' . $supplier->kode_supplier . ')'
        ]);

        return redirect()->route('data-supplier')->with('success', 'Data supplier berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');
        $deleted = $this->supplierService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Data supplier tidak ditemukan'], 404);
        }

        $supplier = Supplier::withTrashed()->find($id); // Get name for log even if soft deleted or if service just deleted it

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Supplier',
            'action' => 'Delete',
            'keterangan' => 'Menghapus data supplier: ' . ($supplier->nama_supplier ?? 'ID: ' . $id) . ' (' . ($supplier->kode_supplier ?? 'N/A') . ')'
        ]);

        return response()->json(['success' => true, 'message' => 'Data supplier berhasil dihapus']);
    }

    public function show($id)
    {
        $supplier = $this->supplierService->getById($id);

        if (!$supplier) {
            return redirect()->route('data-supplier')->with('error', 'Data supplier tidak ditemukan');
        }

        return view('pages.data-supplier.show', [
            'title' => 'Detail Data Supplier',
            'supplier' => $supplier
        ]);
    }

    public function printData()
    {
        $this->checkPermission('index');
        $suppliers = $this->supplierService->getAll();
        return view('pages.data-supplier.print', [
            'suppliers' => $suppliers,
            'title' => 'Laporan Data Supplier'
        ]);
    }

    private function checkPermission($action)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->name === 'super-admin') {
            return;
        }

        $permissions = $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        if (!in_array('/data-supplier.' . $action, $permissions)) {
            if (request()->wantsJson()) {
                abort(403, 'Unauthorized action.');
            }
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }
}
