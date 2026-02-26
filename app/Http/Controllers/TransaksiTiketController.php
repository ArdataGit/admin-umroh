<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Ticket;
use App\Models\TransaksiTiket;
use App\Models\TransaksiTiketDetail;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            'bukti_transaksi' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            // Payment Checks
            'jumlah_bayar' => 'nullable|numeric|min:0',
            'metode_pembayaran' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $buktiPath = null;
            if ($request->hasFile('bukti_transaksi')) {
                $buktiPath = $request->file('bukti_transaksi')->store('bukti_transaksi', 'public');
            }

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
                'catatan' => $validated['catatan'],
                'bukti_transaksi' => $buktiPath
            ]);

            foreach ($validated['details'] as $detail) {
                TransaksiTiketDetail::create([
                    'transaksi_tiket_id' => $transaksi->id,
                    'ticket_id' => $detail['ticket_id'],
                    'quantity' => $detail['quantity'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'total_harga' => $detail['total_harga']
                ]);

                $ticket = Ticket::findOrFail($detail['ticket_id']);

                // Stock Management if Process or Completed
                if (in_array($validated['status_transaksi'], ['process', 'completed'])) {
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

            // Generate Code for Payment: PT-00001
            $lastPayment = \App\Models\PembayaranTiket::orderBy('id', 'desc')->first();
            $nextId = $lastPayment ? ($lastPayment->id + 1) : 1;
            $kodePembayaran = 'PT-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

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
        $transaksi = TransaksiTiket::with(['details.ticket', 'pembayaranTikets'])->findOrFail($id);
        
        $initialPayment = $transaksi->pembayaranTikets->first();
        
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
            'pelanggans' => Pelanggan::all(),
            'initialPayment' => $initialPayment
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
            'catatan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Payment Fields
            'jumlah_bayar' => 'nullable|numeric|min:0',
            'metode_pembayaran' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $transaksi = TransaksiTiket::with('details')->findOrFail($id);

            // 1. Revert Stock if was PROCESS or COMPLETED
            if (in_array($transaksi->status_transaksi, ['process', 'completed'])) {
                foreach ($transaksi->details as $oldDetail) {
                    $ticket = Ticket::findOrFail($oldDetail->ticket_id);
                    $ticket->increment('jumlah_tiket', $oldDetail->quantity);
                }
            }

            // 2. Update Main Record
            $updateData = [
                'pelanggan_id' => $validated['pelanggan_id'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'tax_percentage' => $validated['tax_percentage'],
                'discount_percentage' => $validated['discount_percentage'],
                'shipping_cost' => $validated['shipping_cost'],
                'total_transaksi' => $validated['total_transaksi'],
                'status_transaksi' => $validated['status_transaksi'],
                'alamat_transaksi' => $validated['alamat_transaksi'],
                'catatan' => $validated['catatan']
            ];

            if ($request->hasFile('bukti_transaksi')) {
                // Delete old file
                if ($transaksi->bukti_transaksi && Storage::disk('public')->exists($transaksi->bukti_transaksi)) {
                    Storage::disk('public')->delete($transaksi->bukti_transaksi);
                }
                $updateData['bukti_transaksi'] = $request->file('bukti_transaksi')->store('bukti_transaksi', 'public');
            }

            $transaksi->update($updateData);

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

                $ticket = Ticket::findOrFail($detail['ticket_id']);

                if (in_array($validated['status_transaksi'], ['process', 'completed'])) {
                    if ($ticket->jumlah_tiket < $detail['quantity']) {
                        throw new \Exception("Stok tidak cukup untuk tiket: " . $ticket->nama_tiket);
                    }
                    $ticket->decrement('jumlah_tiket', $detail['quantity']);
                }
            }

            // 5. Update Initial Payment
            $initialPayment = $transaksi->pembayaranTikets()->orderBy('id', 'asc')->first();
            if ($initialPayment) {
                $jumlahBayar = $validated['jumlah_bayar'] ?? 0;
                $metodeBayar = $validated['metode_pembayaran'];
                
                if ($jumlahBayar > 0 && empty($metodeBayar)) {
                    throw new \Exception("Metode pembayaran harus dipilih jika ada pembayaran.");
                }

                $statusBayar = ($jumlahBayar > 0) ? 'paid' : 'pending';
                $metodeBayar = $metodeBayar ?? '-';

                $initialPayment->update([
                    'jumlah_pembayaran' => $jumlahBayar,
                    'metode_pembayaran' => $metodeBayar,
                    'status_pembayaran' => $statusBayar,
                    'tanggal_pembayaran' => $validated['tanggal_transaksi']
                ]);
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

            // Revert Stock if was process or completed
            if (in_array($transaksi->status_transaksi, ['process', 'completed'])) {
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

    public function exportPdf($id)
    {
        $transaksi = TransaksiTiket::with([
            'pelanggan',
            'details.ticket',
            'pembayaranTikets'
        ])->findOrFail($id);

        $totalBayar = $transaksi->pembayaranTikets->where('status_pembayaran', 'paid')->sum('jumlah_pembayaran');
        $sisaPembayaran = $transaksi->total_transaksi - $totalBayar;
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.pembayaran-tiket.pdf', [
            'title' => 'Invoice Transaksi Tiket - ' . $transaksi->kode_transaksi,
            'transaksi' => $transaksi,
            'total_bayar' => $totalBayar,
            'sisa_pembayaran' => $sisaPembayaran
        ]);

        $firstDetail = $transaksi->details->first();
        $dateSuffix = '';
        if ($firstDetail && $firstDetail->ticket) {
            $dateSuffix = '_' . $firstDetail->ticket->tanggal_keberangkatan . '_' . $firstDetail->ticket->tanggal_kepulangan;
        }
        
        return $pdf->download('Invoice_' . \Illuminate\Support\Str::slug($transaksi->kode_transaksi) . $dateSuffix . '.pdf');
    }

    public function printPdf($id)
    {
        $transaksi = TransaksiTiket::with([
            'pelanggan',
            'details.ticket',
            'pembayaranTikets'
        ])->findOrFail($id);

        $totalBayar = $transaksi->pembayaranTikets->where('status_pembayaran', 'paid')->sum('jumlah_pembayaran');
        $sisaPembayaran = $transaksi->total_transaksi - $totalBayar;
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.pembayaran-tiket.pdf', [
            'title' => 'Invoice Transaksi Tiket - ' . $transaksi->kode_transaksi,
            'transaksi' => $transaksi,
            'total_bayar' => $totalBayar,
            'sisa_pembayaran' => $sisaPembayaran
        ]);

        $firstDetail = $transaksi->details->first();
        $dateSuffix = '';
        if ($firstDetail && $firstDetail->ticket) {
            $dateSuffix = '_' . $firstDetail->ticket->tanggal_keberangkatan . '_' . $firstDetail->ticket->tanggal_kepulangan;
        }
        
        return $pdf->stream('Invoice_' . \Illuminate\Support\Str::slug($transaksi->kode_transaksi) . $dateSuffix . '.pdf');
    }
}
