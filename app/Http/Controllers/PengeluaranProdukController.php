<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use App\Models\PengeluaranProduk;
use App\Models\PengeluaranProdukDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class PengeluaranProdukController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/pengeluaran-produk.create', $permissions);
        $canEdit = $isAdmin || in_array('/pengeluaran-produk.edit', $permissions);
        $canDelete = $isAdmin || in_array('/pengeluaran-produk.delete', $permissions);

        $pengeluarans = PengeluaranProduk::with(['jamaah', 'details'])->latest()->get();
        return view('pages.pengeluaran-produk.index', [
            'title' => 'Pengeluaran Produk',
            'pengeluarans' => $pengeluarans,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        $lastTransaction = PengeluaranProduk::latest()->first();
        $nextId = $lastTransaction ? ($lastTransaction->id + 1) : 1;
        // Format PK-XXX, e.g., PK-001
        $kodePengeluaran = 'PK-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('pages.pengeluaran-produk.create', [
            'title' => 'Tambah Pengeluaran Produk',
            'produks' => Produk::all(),
            'jamaahs' => Jamaah::all(),
            'kodePengeluaran' => $kodePengeluaran
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');
        $validated = $request->validate([
            'kode_pengeluaran' => 'required|unique:pengeluaran_produks,kode_pengeluaran',
            'jamaah_id' => 'required|exists:jamaahs,id',
            'tanggal_pengeluaran' => 'required|date|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:9999-12-31',
            'details' => 'required|array|min:1',
            'details.*.produk_id' => 'required|exists:produks,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.harga_satuan' => 'required|numeric|min:0',
            'details.*.total_harga' => 'required|numeric|min:0',
            'tax_percentage' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'total_nominal' => 'required|numeric|min:0',
            'status_pengeluaran' => 'required|in:process,delivery,completed',
            'metode_pengiriman' => 'required|in:kurir,kantor,delivery,order',
            'alamat_pengiriman' => 'nullable|string',
            'catatan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $pengeluaran = PengeluaranProduk::create([
                'kode_pengeluaran' => $validated['kode_pengeluaran'],
                'jamaah_id' => $validated['jamaah_id'],
                'tanggal_pengeluaran' => $validated['tanggal_pengeluaran'],
                'tax_percentage' => $validated['tax_percentage'],
                'discount_percentage' => $validated['discount_percentage'],
                'shipping_cost' => $validated['shipping_cost'],
                'total_nominal' => $validated['total_nominal'],
                'status_pengeluaran' => $validated['status_pengeluaran'],
                'metode_pengiriman' => $validated['metode_pengiriman'],
                'alamat_pengiriman' => $validated['alamat_pengiriman'],
                'catatan' => $validated['catatan']
            ]);

            foreach ($validated['details'] as $detail) {
                PengeluaranProdukDetail::create([
                    'pengeluaran_produk_id' => $pengeluaran->id,
                    'produk_id' => $detail['produk_id'],
                    'quantity' => $detail['quantity'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'total_harga' => $detail['total_harga']
                ]);

                // Update Stock if status is COMPLETED
                if ($validated['status_pengeluaran'] === 'completed') {
                    $produk = Produk::findOrFail($detail['produk_id']);
                    // For Pengeluaran (Outgoing), we DECREMENT stock
                    if ($produk->aktual_stok < $detail['quantity']) {
                        throw new \Exception("Stok tidak cukup untuk produk: " . $produk->nama_produk);
                    }
                    $produk->decrement('aktual_stok', $detail['quantity']);
                }
            }

            DB::commit();

            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Pengeluaran Produk',
                'action' => 'Create',
                'keterangan' => 'Membuat pengeluaran produk: ' . $validated['kode_pengeluaran'] . ' untuk jamaah: ' . (Jamaah::find($validated['jamaah_id'])->nama_jamaah ?? 'N/A') . ' senilai ' . number_format($validated['total_nominal'], 0, ',', '.')
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Pengeluaran berhasil disimpan',
                'redirect' => route('pengeluaran-produk.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $pengeluaran = PengeluaranProduk::with(['jamaah', 'details.produk'])->findOrFail($id);
        return view('pages.pengeluaran-produk.show', [
            'title' => 'Detail Pengeluaran Produk',
            'pengeluaran' => $pengeluaran
        ]);
    }

    public function edit($id)
    {
        $this->checkPermission('edit');
        $pengeluaran = PengeluaranProduk::with('details.produk')->findOrFail($id);
        
        // Prepare details data for x-data
        $details = $pengeluaran->details->map(function($detail) {
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

        return view('pages.pengeluaran-produk.edit', [
            'title' => 'Edit Pengeluaran Produk',
            'pengeluaran' => $pengeluaran,
            'details' => $details,
            'produks' => Produk::all(),
            'jamaahs' => Jamaah::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');
        // Code pengeluaran cannot be edited, so exclude from validation
        $validated = $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'tanggal_pengeluaran' => 'required|date|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:9999-12-31',
            'details' => 'required|array|min:1',
            'details.*.produk_id' => 'required|exists:produks,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.harga_satuan' => 'required|numeric|min:0',
            'details.*.total_harga' => 'required|numeric|min:0',
            'tax_percentage' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'total_nominal' => 'required|numeric|min:0',
            'status_pengeluaran' => 'required|in:process,delivery,completed',
            'metode_pengiriman' => 'required|in:kurir,kantor,delivery,order',
            'alamat_pengiriman' => 'nullable|string',
            'catatan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $pengeluaran = PengeluaranProduk::with('details')->findOrFail($id);

            // 1. REVERT STOCK if previous status was COMPLETED
            // Revert means adding back what was taken out
            if ($pengeluaran->status_pengeluaran === 'completed') {
                foreach ($pengeluaran->details as $oldDetail) {
                    $produk = Produk::findOrFail($oldDetail->produk_id);
                    $produk->increment('aktual_stok', $oldDetail->quantity);
                }
            }

            // 2. Update Main Record
            $pengeluaran->update([
                'jamaah_id' => $validated['jamaah_id'],
                'tanggal_pengeluaran' => $validated['tanggal_pengeluaran'],
                'tax_percentage' => $validated['tax_percentage'],
                'discount_percentage' => $validated['discount_percentage'],
                'shipping_cost' => $validated['shipping_cost'],
                'total_nominal' => $validated['total_nominal'],
                'status_pengeluaran' => $validated['status_pengeluaran'],
                'metode_pengiriman' => $validated['metode_pengiriman'],
                'alamat_pengiriman' => $validated['alamat_pengiriman'],
                'catatan' => $validated['catatan']
            ]);

            // 3. Delete Old Details
            $pengeluaran->details()->delete();

            // 4. Insert New Details & Apply New Stock Logic
            foreach ($validated['details'] as $detail) {
                PengeluaranProdukDetail::create([
                    'pengeluaran_produk_id' => $pengeluaran->id,
                    'produk_id' => $detail['produk_id'],
                    'quantity' => $detail['quantity'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'total_harga' => $detail['total_harga']
                ]);

                if ($validated['status_pengeluaran'] === 'completed') {
                    $produk = Produk::findOrFail($detail['produk_id']);
                    // Decrement again for output
                    if ($produk->aktual_stok < $detail['quantity']) {
                        throw new \Exception("Stok tidak cukup untuk produk: " . $produk->nama_produk);
                    }
                    $produk->decrement('aktual_stok', $detail['quantity']);
                }
            }

            DB::commit();

            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Pengeluaran Produk',
                'action' => 'Update',
                'keterangan' => 'Memperbarui pengeluaran produk: ' . $pengeluaran->kode_pengeluaran . ' untuk jamaah: ' . ($pengeluaran->jamaah->nama_jamaah ?? 'N/A')
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Pengeluaran berhasil diperbarui',
                'redirect' => route('pengeluaran-produk.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');
        try {
            DB::beginTransaction();
            $pengeluaran = PengeluaranProduk::with('details')->findOrFail($id);

            // Revert Stock if was completed
            if ($pengeluaran->status_pengeluaran === 'completed') {
                foreach ($pengeluaran->details as $detail) {
                    $produk = Produk::findOrFail($detail->produk_id);
                    $produk->increment('aktual_stok', $detail->quantity);
                }
            }

            $kodePengeluaran = $pengeluaran->kode_pengeluaran;
            $namaJamaah = $pengeluaran->jamaah->nama_jamaah ?? 'N/A';

            $pengeluaran->delete();
            DB::commit();

            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Pengeluaran Produk',
                'action' => 'Delete',
                'keterangan' => 'Menghapus pengeluaran produk: ' . $kodePengeluaran . ' untuk jamaah: ' . $namaJamaah
            ]);

            return response()->json(['success' => true, 'message' => 'Pengeluaran berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }

    private function checkPermission($action)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->name === 'super-admin') {
            return;
        }

        $permissions = $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        if (!in_array('/pengeluaran-produk.' . $action, $permissions)) {
            if (request()->wantsJson()) {
                abort(403, 'Unauthorized action.');
            }
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }
}
