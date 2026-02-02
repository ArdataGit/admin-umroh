<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupplierService;
use App\Models\Supplier;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function index()
    {
        $dataSupplier = $this->supplierService->getAll();
        return view('pages.data-supplier.index', ['title' => 'Data Supplier', 'dataSupplier' => $dataSupplier]);
    }

    public function create()
    {
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

        return redirect()->route('data-supplier')->with('success', 'Data supplier berhasil ditambahkan');
    }

    public function edit($id)
    {
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

        return redirect()->route('data-supplier')->with('success', 'Data supplier berhasil diperbarui');
    }

    public function destroy($id)
    {
        $deleted = $this->supplierService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Data supplier tidak ditemukan'], 404);
        }

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
        $suppliers = $this->supplierService->getAll();
        return view('pages.data-supplier.print', [
            'suppliers' => $suppliers,
            'title' => 'Laporan Data Supplier'
        ]);
    }
}
