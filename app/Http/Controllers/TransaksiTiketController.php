<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Ticket;
use App\Models\TransaksiTiket;
use App\Models\TransaksiTiketDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiTiketController extends Controller
{
    public function index()
    {
        $transaksi = TransaksiTiket::with(['pelanggan', 'details.ticket', 'pembayaranTikets'])->latest()->get();
        return view('pages.transaksi-tiket.index', [
            'title' => 'Transaksi Tiket',
            'transaksi' => $transaksi
        ]);
    }

    public function create()
    {
        $lastTransaction = TransaksiTiket::latest()->first();
        $nextId = $lastTransaction ? ($lastTransaction->id + 1) : 1;
        // Format TI-XXX (Ticket Invoice)
        $kodeTransaksi = 'TI-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('pages.transaksi-tiket.create', [
            'title' => 'Tambah Transaksi Tiket',
            'tickets' => Ticket::all(),
            'pelanggans' => Pelanggan::all(),
            'kodeTransaksi' => $kodeTransaksi
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_transaksi' => 'required|unique:transaksi_tikets,kode_transaksi',
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal_transaksi' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.ticket_id' => 'required|exists:tickets,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.harga_satuan' => 'required|numeric|min:0',
            'details.*.total_harga' => 'required|numeric|min:0',
            'tax_percentage' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
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

            $transaksi = TransaksiTiket::create([
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
                TransaksiTiketDetail::create([
                    'transaksi_tiket_id' => $transaksi->id,
                    'ticket_id' => $detail['ticket_id'],
                    'quantity' => $detail['quantity'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'total_harga' => $detail['total_harga']
                ]);

                // Stock Management ONLY if Completed
                if ($validated['status_transaksi'] === 'completed') {
                    $ticket = Ticket::findOrFail($detail['ticket_id']);
                    if ($ticket->jumlah_tiket < $detail['quantity']) {
                        throw new \Exception("Stok tidak cukup untuk tiket: " . $ticket->nama_tiket);
                    }
                    $ticket->decrement('jumlah_tiket', $detail['quantity']);
                }
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

            // Generate Code for Payment: PT-ID-XXX
            $countPayment = \App\Models\PembayaranTiket::count() + 1;
            $kodePembayaran = 'PT-' . str_pad($countPayment, 5, '0', STR_PAD_LEFT);

            \App\Models\PembayaranTiket::create([
                'transaksi_tiket_id' => $transaksi->id,
                'kode_transaksi' => $kodePembayaran,
                'tanggal_pembayaran' => $validated['tanggal_transaksi'], // Match transaction date
                'jumlah_pembayaran' => $jumlahBayar,
                'metode_pembayaran' => $metodeBayar,
                'status_pembayaran' => $statusBayar,
                'catatan' => 'Pembayaran awal saat transaksi',
                'kode_referensi' => null
            ]);

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Transaksi berhasil disimpan',
                'redirect' => route('transaksi-tiket.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $transaksi = TransaksiTiket::with(['pelanggan', 'details.ticket'])->findOrFail($id);
        return view('pages.transaksi-tiket.show', [
            'title' => 'Detail Transaksi Tiket',
            'transaksi' => $transaksi
        ]);
    }

    public function edit($id)
    {
        $transaksi = TransaksiTiket::with('details.ticket')->findOrFail($id);
        
        $details = $transaksi->details->map(function($detail) {
            return [
                'ticket_id' => $detail->ticket_id,
                'nama_tiket' => $detail->ticket->nama_tiket,
                'kode_tiket' => $detail->ticket->kode_tiket,
                'stok' => $detail->ticket->jumlah_tiket,
                'kurs' => $detail->ticket->kurs,
                'harga_jual_asing' => $detail->ticket->harga_jual_asing,
                'harga_satuan' => $detail->harga_satuan,
                'quantity' => $detail->quantity,
                'total_harga' => $detail->total_harga
            ];
        });

        return view('pages.transaksi-tiket.edit', [
            'title' => 'Edit Transaksi Tiket',
            'transaksi' => $transaksi,
            'details' => $details,
            'tickets' => Ticket::all(),
            'pelanggans' => Pelanggan::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal_transaksi' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.ticket_id' => 'required|exists:tickets,id',
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

            $transaksi = TransaksiTiket::with('details')->findOrFail($id);

            // 1. Revert Stock if was COMPLETED
            if ($transaksi->status_transaksi === 'completed') {
                foreach ($transaksi->details as $oldDetail) {
                    $ticket = Ticket::findOrFail($oldDetail->ticket_id);
                    $ticket->increment('jumlah_tiket', $oldDetail->quantity);
                }
            }

            // 2. Update Main Record
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

            // 3. Delete Old Details
            $transaksi->details()->delete();

            // 4. Insert New Details & Apply Stock Logic
            foreach ($validated['details'] as $detail) {
                TransaksiTiketDetail::create([
                    'transaksi_tiket_id' => $transaksi->id,
                    'ticket_id' => $detail['ticket_id'],
                    'quantity' => $detail['quantity'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'total_harga' => $detail['total_harga']
                ]);

                if ($validated['status_transaksi'] === 'completed') {
                    $ticket = Ticket::findOrFail($detail['ticket_id']);
                    if ($ticket->jumlah_tiket < $detail['quantity']) {
                        throw new \Exception("Stok tidak cukup untuk tiket: " . $ticket->nama_tiket);
                    }
                    $ticket->decrement('jumlah_tiket', $detail['quantity']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Transaksi berhasil diperbarui',
                'redirect' => route('transaksi-tiket.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $transaksi = TransaksiTiket::with('details')->findOrFail($id);

            // Revert Stock if was completed
            if ($transaksi->status_transaksi === 'completed') {
                foreach ($transaksi->details as $detail) {
                    $ticket = Ticket::findOrFail($detail->ticket_id);
                    $ticket->increment('jumlah_tiket', $detail->quantity);
                }
            }

            $transaksi->delete();
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }
}
