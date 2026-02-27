<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class StockOpnameController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/stock-opname.create', $permissions);
        $canEdit = $isAdmin || in_array('/stock-opname.edit', $permissions);
        $canDelete = $isAdmin || in_array('/stock-opname.delete', $permissions);

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

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Stock Opname',
            'action' => 'Create',
            'keterangan' => 'Menambahkan penyesuaian stok (' . $validated['tipe_adjustment'] . ') untuk produk: ' . $produk->nama_produk . ' | Kode: ' . $validated['kode_adjustment']
        ]);

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

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Stock Opname',
            'action' => 'Update',
            'keterangan' => 'Memperbarui penyesuaian stok: ' . $stockOpname->kode_adjustment . ' | Produk: ' . $produk->nama_produk
        ]);

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

        $kodeAdjustment = $stockOpname->kode_adjustment;
        $namaProduk = $produk->nama_produk;

        $stockOpname->delete();

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Stock Opname',
            'action' => 'Delete',
            'keterangan' => 'Menghapus penyesuaian stok: ' . $kodeAdjustment . ' | Produk: ' . $namaProduk
        ]);

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }

    private function checkPermission($action)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->name === 'super-admin') {
            return;
        }

        $permissions = $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        if (!in_array('/stock-opname.' . $action, $permissions)) {
            if (request()->wantsJson()) {
                abort(403, 'Unauthorized action.');
            }
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }
}
