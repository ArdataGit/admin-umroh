<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\TransaksiLayanan;
use App\Models\TransaksiLayananDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class TransaksiLayananController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->name === 'super-admin') {
            return;
        }

        $permissions = $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        if (!in_array('/transaksi-layanan.' . $action, $permissions)) {
            if (request()->wantsJson()) {
                abort(403, 'Unauthorized action.');
            }
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }

    public function index()
    {
        $transaksi = TransaksiLayanan::with(['pelanggan', 'details.layanan', 'pembayaranLayanans'])->latest()->get();

        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/transaksi-layanan.create', $permissions);
        $canEdit = $isAdmin || in_array('/transaksi-layanan.edit', $permissions);
        $canDelete = $isAdmin || in_array('/transaksi-layanan.delete', $permissions);

        return view('pages.transaksi-layanan.index', [
            'title' => 'Transaksi Layanan',
            'transaksi' => $transaksi,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        $lastTransaction = TransaksiLayanan::latest()->first();
        $nextId = $lastTransaction ? ($lastTransaction->id + 1) : 1;
        // Format SO-XXX
        $kodeTransaksi = 'SO-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('pages.transaksi-layanan.create', [
            'title' => 'Tambah Transaksi Layanan',
            'layanans' => Layanan::all(),
            'pelanggans' => Pelanggan::all(),
            'kodeTransaksi' => $kodeTransaksi
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');
        $validated = $request->validate([
            'kode_transaksi' => 'required|unique:transaksi_layanans,kode_transaksi',
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal_transaksi' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.layanan_id' => 'required|exists:layanans,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.harga_satuan' => 'required|numeric|min:0',
            'details.*.total_harga' => 'required|numeric|min:0',
            'tax_percentage' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0', // Used as additional cost potentially
            'total_transaksi' => 'required|numeric|min:0',
            'status_transaksi' => 'required|in:process,completed,cancelled',
            'alamat_transaksi' => 'nullable|string',
            'catatan' => 'nullable|string',
            // Payment Checks
            'jumlah_bayar' => 'nullable|numeric|min:0',
            'metode_pembayaran' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $transaksi = TransaksiLayanan::create([
                'kode_transaksi' => $validated['kode_transaksi'],
                'pelanggan_id' => $validated['pelanggan_id'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'tax_percentage' => $validated['tax_percentage'],
                'discount_percentage' => $validated['discount_percentage'],
                'shipping_cost' => $validated['shipping_cost'],
                'total_transaksi' => $validated['total_transaksi'],
                'status_transaksi' => $validated['status_transaksi'],
                'alamat_transaksi' => $validated['alamat_transaksi'],
                'catatan' => $validated['catatan']
            ]);

            foreach ($validated['details'] as $detail) {
                TransaksiLayananDetail::create([
                    'transaksi_layanan_id' => $transaksi->id,
                    'layanan_id' => $detail['layanan_id'],
                    'quantity' => $detail['quantity'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'total_harga' => $detail['total_harga']
                ]);
            }

            // Handle Initial Payment (Always create a record for tracking)
            $jumlahBayar = $validated['jumlah_bayar'] ?? 0;
            $metodeBayar = $validated['metode_pembayaran'];
            
            // If amount > 0, method is required
            if ($jumlahBayar > 0 && empty($metodeBayar)) {
                throw new \Exception("Metode pembayaran harus dipilih jika ada pembayaran.");
            }

            $statusBayar = ($jumlahBayar > 0) ? 'paid' : 'pending';
            $metodeBayar = $metodeBayar ?? '-'; // Default if pending

            // Generate Code for Payment: PS-ID-XXX (Payment Service)
            $countPayment = \App\Models\PembayaranLayanan::count() + 1;
            $kodePembayaran = 'PS-' . str_pad($countPayment, 5, '0', STR_PAD_LEFT);

            \App\Models\PembayaranLayanan::create([
                'transaksi_layanan_id' => $transaksi->id,
                'kode_transaksi' => $kodePembayaran,
                'tanggal_pembayaran' => $validated['tanggal_transaksi'], // Match transaction date
                'jumlah_pembayaran' => $jumlahBayar,
                'metode_pembayaran' => $metodeBayar,
                'status_pembayaran' => $statusBayar,
                'catatan' => 'Pembayaran awal saat transaksi',
                'kode_referensi' => null
            ]);

            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Transaksi Layanan',
                'action' => 'Create',
                'keterangan' => 'Membuat transaksi layanan baru: ' . $transaksi->kode_transaksi . ' untuk pelanggan: ' . ($transaksi->pelanggan->nama_pelanggan ?? 'Umum')
            ]);

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Transaksi berhasil disimpan',
                'redirect' => route('transaksi-layanan.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $transaksi = TransaksiLayanan::with(['pelanggan', 'details.layanan'])->findOrFail($id);
        return view('pages.transaksi-layanan.show', [
            'title' => 'Detail Transaksi Layanan',
            'transaksi' => $transaksi
        ]);
    }

    public function edit($id)
    {
        $this->checkPermission('edit');
        $transaksi = TransaksiLayanan::with('details.layanan')->findOrFail($id);
        
        $details = $transaksi->details->map(function($detail) {
            return [
                'layanan_id' => $detail->layanan_id,
                'nama_layanan' => $detail->layanan->nama_layanan,
                'kurs' => $detail->layanan->kurs,
                'harga_jual_asing' => $detail->layanan->harga_jual_asing,
                'harga_satuan' => $detail->harga_satuan,
                'quantity' => $detail->quantity,
                'total_harga' => $detail->total_harga
            ];
        });

        return view('pages.transaksi-layanan.edit', [
            'title' => 'Edit Transaksi Layanan',
            'transaksi' => $transaksi,
            'details' => $details,
            'layanans' => Layanan::all(),
            'pelanggans' => Pelanggan::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal_transaksi' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.layanan_id' => 'required|exists:layanans,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.harga_satuan' => 'required|numeric|min:0',
            'details.*.total_harga' => 'required|numeric|min:0',
            'tax_percentage' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'total_transaksi' => 'required|numeric|min:0',
            'status_transaksi' => 'required|in:process,completed,cancelled',
            'alamat_transaksi' => 'nullable|string',
            'catatan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $transaksi = TransaksiLayanan::findOrFail($id);

            $transaksi->update([
                'pelanggan_id' => $validated['pelanggan_id'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'tax_percentage' => $validated['tax_percentage'],
                'discount_percentage' => $validated['discount_percentage'],
                'shipping_cost' => $validated['shipping_cost'],
                'total_transaksi' => $validated['total_transaksi'],
                'status_transaksi' => $validated['status_transaksi'],
                'alamat_transaksi' => $validated['alamat_transaksi'],
                'catatan' => $validated['catatan']
            ]);

            $transaksi->details()->delete();

            foreach ($validated['details'] as $detail) {
                TransaksiLayananDetail::create([
                    'transaksi_layanan_id' => $transaksi->id,
                    'layanan_id' => $detail['layanan_id'],
                    'quantity' => $detail['quantity'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'total_harga' => $detail['total_harga']
                ]);
            }

            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Transaksi Layanan',
                'action' => 'Update',
                'keterangan' => 'Memperbarui transaksi layanan: ' . $transaksi->kode_transaksi
            ]);

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Transaksi berhasil diperbarui',
                'redirect' => route('transaksi-layanan.index')
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
            $transaksi = TransaksiLayanan::findOrFail($id);
            $kodeTransaksi = $transaksi->kode_transaksi;
            $transaksi->delete();

            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Transaksi Layanan',
                'action' => 'Delete',
                'keterangan' => 'Menghapus transaksi layanan: ' . $kodeTransaksi
            ]);

            return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }
}
