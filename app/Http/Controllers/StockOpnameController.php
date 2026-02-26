<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StockOpnameController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        
        // Super-admin has full access
        if ($user->role && $user->role->name === 'super-admin') {
            return true;
        }

        $permission = "/stock-opname.{$action}";
        $hasPermission = $user->role && $user->role->permissions()
            ->where('menu_path', $permission)
            ->exists();

        if (!$hasPermission) {
            abort(403, 'Anda tidak memiliki akses untuk ' . $action . ' data stock opname.');
        }

        return true;
    }

    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role && $user->role->name === 'super-admin';
        
        $canCreate = $isSuperAdmin || ($user->role && $user->role->permissions()->where('menu_path', '/stock-opname.create')->exists());
        $canEdit = $isSuperAdmin || ($user->role && $user->role->permissions()->where('menu_path', '/stock-opname.edit')->exists());
        $canDelete = $isSuperAdmin || ($user->role && $user->role->permissions()->where('menu_path', '/stock-opname.delete')->exists());

        $stockOpnames = StockOpname::with('produk')->orderBy('tanggal_adjustment', 'desc')->get();
        return view('pages.stock-opname.index', [
            'title' => 'Stock Opname',
            'stockOpnames' => $stockOpnames,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        
        $produks = Produk::all();
        
        // Generate AD Check Code
        // AD-{YYYYMMDD}-{RAND}
        $date = date('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        $kodeAdjustment = "AD-{$date}-{$random}";

        return view('pages.stock-opname.create', [
            'title' => 'Tambah Stock Opname',
            'produks' => $produks,
            'kodeAdjustment' => $kodeAdjustment
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');
        
        $validated = $request->validate([
            'kode_adjustment' => 'required|string|unique:stock_opnames,kode_adjustment',
            'tanggal_adjustment' => 'required|date|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:9999-12-31',
            'produk_id' => 'required|exists:produks,id',
            'tipe_adjustment' => 'required|in:penambahan,pengurangan',
            'koreksi_stock' => 'required|numeric|min:1',
            'catatan' => 'nullable|string'
        ]);

        $produk = Produk::findOrFail($validated['produk_id']);

        // Set Stok Awal
        $validated['stok_awal'] = $produk->aktual_stok;
        $validated['user_id'] = 'Admin'; // Mock user
        $validated['status_approval'] = 'Approved'; // Default auto-approve

        // Calculate Stok Akhir and Update Product
        if ($validated['tipe_adjustment'] == 'penambahan') {
            $validated['stok_akhir'] = $produk->aktual_stok + $validated['koreksi_stock'];
            $produk->increment('aktual_stok', $validated['koreksi_stock']);
        } else {
            $validated['stok_akhir'] = $produk->aktual_stok - $validated['koreksi_stock'];
            $produk->decrement('aktual_stok', $validated['koreksi_stock']);
        }
        
        $stockOpname = StockOpname::create($validated);

        return redirect()->route('stock-opname.index')->with('success', 'Stock Adjustment berhasil disimpan');
    }

    public function show($id)
    {
        $stockOpname = StockOpname::with('produk')->findOrFail($id);
        
        return view('pages.stock-opname.show', [
            'title' => 'Detail Stock Opname',
            'stockOpname' => $stockOpname
        ]);
    }

    public function edit($id)
    {
        $this->checkPermission('edit');
        
        $stockOpname = StockOpname::findOrFail($id);
        $produks = Produk::all();

        return view('pages.stock-opname.edit', [
            'title' => 'Edit Stock Opname',
            'stockOpname' => $stockOpname,
            'produks' => $produks
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');
        
        $stockOpname = StockOpname::findOrFail($id);
        $produk = Produk::findOrFail($stockOpname->produk_id);

        $validated = $request->validate([
            'tanggal_adjustment' => 'required|date|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:9999-12-31',
            'tipe_adjustment' => 'required|in:penambahan,pengurangan',
            'koreksi_stock' => 'required|numeric|min:1',
            'catatan' => 'nullable|string'
        ]);

        // 1. Revert Old Stock Adjustment
        if ($stockOpname->tipe_adjustment == 'penambahan') {
            $produk->decrement('aktual_stok', $stockOpname->koreksi_stock);
        } else {
            $produk->increment('aktual_stok', $stockOpname->koreksi_stock);
        }

        // 2. Fetch fresh product data (Stock Awal matches current state BEFORE new adjustment)
        $produk->refresh();
        $validated['stok_awal'] = $produk->aktual_stok;

        // 3. Apply New Stock Adjustment
        if ($validated['tipe_adjustment'] == 'penambahan') {
            $validated['stok_akhir'] = $produk->aktual_stok + $validated['koreksi_stock'];
            $produk->increment('aktual_stok', $validated['koreksi_stock']);
        } else {
            $validated['stok_akhir'] = $produk->aktual_stok - $validated['koreksi_stock'];
            $produk->decrement('aktual_stok', $validated['koreksi_stock']);
        }

        $stockOpname->update($validated);

        return redirect()->route('stock-opname.index')->with('success', 'Stock Adjustment berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');
        
        $stockOpname = StockOpname::findOrFail($id);
        $produk = Produk::findOrFail($stockOpname->produk_id);

        // Revert Stock
        if ($stockOpname->tipe_adjustment == 'penambahan') {
            $produk->decrement('aktual_stok', $stockOpname->koreksi_stock);
        } else {
            $produk->increment('aktual_stok', $stockOpname->koreksi_stock);
        }

        $stockOpname->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
