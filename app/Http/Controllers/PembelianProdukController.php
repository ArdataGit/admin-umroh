<?php

namespace App\Http\Controllers;

use App\Models\PembelianProduk;
use App\Models\PembelianProdukDetail;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianProdukController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        
        // Super-admin has full access
        if ($user->role && $user->role->name === 'super-admin') {
            return true;
        }

        $permission = "/pembelian-produk.{$action}";
        $hasPermission = $user->role && $user->role->permissions()
            ->where('menu_path', $permission)
            ->exists();

        if (!$hasPermission) {
            abort(403, 'Anda tidak memiliki akses untuk ' . $action . ' data pembelian produk.');
        }

        return true;
    }

    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role && $user->role->name === 'super-admin';
        
        $canCreate = $isSuperAdmin || ($user->role && $user->role->permissions()->where('menu_path', '/pembelian-produk.create')->exists());
        $canEdit = $isSuperAdmin || ($user->role && $user->role->permissions()->where('menu_path', '/pembelian-produk.edit')->exists());
        $canDelete = $isSuperAdmin || ($user->role && $user->role->permissions()->where('menu_path', '/pembelian-produk.delete')->exists());

        $pembelians = PembelianProduk::with(['supplier', 'details'])->latest()->get();
        return view('pages.pembelian-produk.index', [
            'title' => 'Pembelian Produk',
            'pembelians' => $pembelians,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        
        $suppliers = Supplier::all();
        $produks = Produk::all();
        
        // Generate PM Code: PM-{YYYYMMDD}-{RAND}
        $date = date('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        $kodePembelian = "PM-{$date}-{$random}";

        return view('pages.pembelian-produk.create', [
            'title' => 'Tambah Pembelian Produk',
            'suppliers' => $suppliers,
            'produks' => $produks,
            'kodePembelian' => $kodePembelian
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');
        
        $validated = $request->validate([
            'kode_pembelian' => 'required|string|unique:pembelian_produks,kode_pembelian',
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.produk_id' => 'required|exists:produks,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.harga_satuan' => 'required|numeric|min:0',
            'details.*.total_harga' => 'required|numeric|min:0',
            'tax_percentage' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'total_pembayaran' => 'required|numeric|min:0',
            'jumlah_bayar' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:order,delivery,completed',
            'metode_pembayaran' => 'required|in:cash,transfer,qris,other',
            'catatan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $pembelian = PembelianProduk::create([
                'kode_pembelian' => $validated['kode_pembelian'],
                'supplier_id' => $validated['supplier_id'],
                'tanggal_pembelian' => $validated['tanggal_pembelian'],
                'tax_percentage' => $validated['tax_percentage'],
                'discount_percentage' => $validated['discount_percentage'],
                'shipping_cost' => $validated['shipping_cost'],
                'total_pembayaran' => $validated['total_pembayaran'],
                'jumlah_bayar' => $validated['jumlah_bayar'],
                'status_pembayaran' => $validated['status_pembayaran'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'catatan' => $validated['catatan']
            ]);

            foreach ($validated['details'] as $detail) {
                PembelianProdukDetail::create([
                    'pembelian_produk_id' => $pembelian->id,
                    'produk_id' => $detail['produk_id'],
                    'quantity' => $detail['quantity'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'total_harga' => $detail['total_harga']
                ]);

                // Update Stock ONLY IF status is COMPLETED
                if ($validated['status_pembayaran'] === 'completed') {
                    $produk = Produk::findOrFail($detail['produk_id']);
                    $produk->increment('aktual_stok', $detail['quantity']);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pembelian berhasil disimpan', 'redirect' => route('pembelian-produk.index')]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan pembelian: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $pembelian = PembelianProduk::with(['supplier', 'details.produk'])->findOrFail($id);
        return view('pages.pembelian-produk.show', [
            'title' => 'Detail Pembelian Produk',
            'pembelian' => $pembelian
        ]);
    }

    public function edit($id)
    {
        $this->checkPermission('edit');
        
        $pembelian = PembelianProduk::with('details.produk')->findOrFail($id);
        $suppliers = Supplier::all();
        $produks = Produk::all();

        // Format details for frontend
        $formattedDetails = $pembelian->details->map(function ($detail) {
            return [
                'produk_id' => $detail->produk_id,
                'nama_produk' => $detail->produk->nama_produk,
                'standar_stok' => $detail->produk->standar_stok,
                'aktual_stok' => $detail->produk->aktual_stok,
                'harga_satuan' => $detail->harga_satuan,
                'quantity' => $detail->quantity,
                'total_harga' => $detail->total_harga
            ];
        });

        return view('pages.pembelian-produk.edit', [
            'title' => 'Edit Pembelian Produk',
            'pembelian' => $pembelian,
            'details' => $formattedDetails,
            'suppliers' => $suppliers,
            'produks' => $produks
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');
        
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.produk_id' => 'required|exists:produks,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.harga_satuan' => 'required|numeric|min:0',
            'details.*.total_harga' => 'required|numeric|min:0',
            'tax_percentage' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'total_pembayaran' => 'required|numeric|min:0',
            'jumlah_bayar' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:order,delivery,completed',
            'metode_pembayaran' => 'required|in:cash,transfer,qris,other',
            'catatan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $pembelian = PembelianProduk::with('details')->findOrFail($id);

            // 1. REVERT STOCK if previous status was COMPLETED
            if ($pembelian->status_pembayaran === 'completed') {
                foreach ($pembelian->details as $oldDetail) {
                    $produk = Produk::findOrFail($oldDetail->produk_id);
                    $produk->decrement('aktual_stok', $oldDetail->quantity);
                }
            }

            // 2. Update Main Purchase Record
            $pembelian->update([
                'supplier_id' => $validated['supplier_id'],
                'tanggal_pembelian' => $validated['tanggal_pembelian'],
                'tax_percentage' => $validated['tax_percentage'],
                'discount_percentage' => $validated['discount_percentage'],
                'shipping_cost' => $validated['shipping_cost'],
                'total_pembayaran' => $validated['total_pembayaran'],
                'jumlah_bayar' => $validated['jumlah_bayar'],
                'status_pembayaran' => $validated['status_pembayaran'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'catatan' => $validated['catatan']
            ]);

            // 3. Delete Old Details
            $pembelian->details()->delete();

            // 4. Create New Details & APLLY NEW STOCK Logi
            foreach ($validated['details'] as $detail) {
                PembelianProdukDetail::create([
                    'pembelian_produk_id' => $pembelian->id,
                    'produk_id' => $detail['produk_id'],
                    'quantity' => $detail['quantity'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'total_harga' => $detail['total_harga']
                ]);

                // Apply Stock ONLY IF status is COMPLETED
                if ($validated['status_pembayaran'] === 'completed') {
                    $produk = Produk::findOrFail($detail['produk_id']);
                    $produk->increment('aktual_stok', $detail['quantity']);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pembelian berhasil diperbarui', 'redirect' => route('pembelian-produk.index')]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui pembelian: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');
        
        try {
            DB::beginTransaction();
            $pembelian = PembelianProduk::with('details')->findOrFail($id);

            // Revert Stock if was completed
            if ($pembelian->status_pembayaran === 'completed') {
                foreach ($pembelian->details as $detail) {
                    $produk = Produk::findOrFail($detail->produk_id);
                    $produk->decrement('aktual_stok', $detail->quantity);
                }
            }

            $pembelian->delete();
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Pembelian berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }
}
