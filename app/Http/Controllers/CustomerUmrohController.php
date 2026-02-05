<?php

namespace App\Http\Controllers;

use App\Models\KeberangkatanUmroh;
use Illuminate\Http\Request;

class CustomerUmrohController extends Controller
{
    public function index($id)
    {
        $keberangkatan = KeberangkatanUmroh::with(['paketUmroh.maskapai'])->findOrFail($id);
        $customerUmrohs = \App\Models\CustomerUmroh::with(['jamaah', 'agent'])->where('keberangkatan_umroh_id', $id)->get();
        
        // Calculate Summaries
        $summary = [
            'jumlah_jamaah' => $customerUmrohs->sum('jumlah_jamaah'),
            'total_harga_paket' => $customerUmrohs->sum(function($row) {
                return $row->total_tagihan + $row->diskon;
            }),
            'total_diskon' => $customerUmrohs->sum('diskon'),
            'total_transaksi' => $customerUmrohs->sum('total_tagihan'),
            'total_bayar' => $customerUmrohs->sum('total_bayar'),
            'total_sisa' => $customerUmrohs->sum('sisa_tagihan'),
        ];

        return view('pages.customer-umroh.index', [
            'title' => 'Manifest Jamaah',
            'keberangkatan' => $keberangkatan,
            'customerUmrohs' => $customerUmrohs,
            'summary' => $summary
        ]);
    }

    public function create($id)
    {
        $keberangkatan = KeberangkatanUmroh::with('paketUmroh')->findOrFail($id);
        $jamaahs = \App\Models\Jamaah::all();
        $agents = \App\Models\Agent::all();

        return view('pages.customer-umroh.create', [
            'title' => 'Tambah Jamaah',
            'keberangkatan' => $keberangkatan,
            'jamaahs' => $jamaahs,
            'agents' => $agents
        ]);
    }

    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'agent_id' => 'required|exists:agents,id',
            'tipe_kamar' => 'required|in:quad,triple,double',
            'jumlah_jamaah' => 'required|integer|min:1',
            'nama_keluarga' => 'nullable|string',
            'harga_paket' => 'required|numeric',
            'diskon' => 'required|numeric', // Nominal validation
            'total_bayar' => 'required|numeric|min:1', // DP
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'catatan' => 'nullable|string'
        ]);

        try {
            // Recalculate server-side for safety
            $totalTagihan = ($validated['harga_paket'] * $validated['jumlah_jamaah']) - $validated['diskon'];
            $sisaTagihan = $totalTagihan - $validated['total_bayar'];

            $customerUmroh = \App\Models\CustomerUmroh::create([
                'keberangkatan_umroh_id' => $id,
                'jamaah_id' => $validated['jamaah_id'],
                'agent_id' => $validated['agent_id'],
                'tipe_kamar' => $validated['tipe_kamar'],
                'jumlah_jamaah' => $validated['jumlah_jamaah'],
                'nama_keluarga' => $validated['nama_keluarga'],
                'harga_paket' => $validated['harga_paket'],
                'diskon' => $validated['diskon'],
                'total_tagihan' => $totalTagihan,
                'total_bayar' => $validated['total_bayar'],
                'sisa_tagihan' => $sisaTagihan,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_visa' => $request->boolean('status_visa'),
                'status_tiket' => $request->boolean('status_tiket'),
                'status_siskopatuh' => $request->boolean('status_siskopatuh'),
                'status_perlengkapan' => $request->boolean('status_perlengkapan'),
                'catatan' => $validated['catatan']
            ]);

            // Auto-create PembayaranUmroh Record if total_bayar > 0
            if ($validated['total_bayar'] > 0) {
                // Create temporary record to get ID
                $pembayaran = \App\Models\PembayaranUmroh::create([
                    'customer_umroh_id' => $customerUmroh->id,
                    'kode_transaksi' => 'TEMP-' . uniqid(),
                    'jumlah_pembayaran' => $validated['total_bayar'],
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'status_pembayaran' => 'checked',
                    'tanggal_pembayaran' => now(),
                    'catatan' => 'Pembayaran awal manifest'
                ]);

                // Update with correct Code Format: INV/CR/{kode jamaah}/{kode keberangkatan}/{id}
                $keberangkatan = \App\Models\KeberangkatanUmroh::find($id);
                $jamaah = \App\Models\Jamaah::find($validated['jamaah_id']);

                $kodeTransaksi = "INV/CR/{$jamaah->kode_jamaah}/{$keberangkatan->kode_keberangkatan}/{$pembayaran->id}";

                $pembayaran->update(['kode_transaksi' => $kodeTransaksi]);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Jamaah berhasil ditambahkan',
                'redirect' => route('customer-umroh.index', $id)
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }
    public function show($id)
    {
        $customerUmroh = \App\Models\CustomerUmroh::with(['jamaah', 'keberangkatanUmroh.paketUmroh', 'agent', 'pembayaranUmroh'])->findOrFail($id);

        return view('pages.customer-umroh.show', [
            'title' => 'Detail Jamaah Manifest',
            'customer' => $customerUmroh
        ]);
    }

    public function edit($id)
    {
        $customerUmroh = \App\Models\CustomerUmroh::with(['keberangkatanUmroh.paketUmroh'])->findOrFail($id);
        $jamaahs = \App\Models\Jamaah::all();
        $agents = \App\Models\Agent::all();
        $keberangkatan = $customerUmroh->keberangkatanUmroh;

        return view('pages.customer-umroh.edit', [
            'title' => 'Edit Jamaah Manifest',
            'customer' => $customerUmroh,
            'keberangkatan' => $keberangkatan,
            'jamaahs' => $jamaahs,
            'agents' => $agents
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'agent_id' => 'required|exists:agents,id',
            'tipe_kamar' => 'required|in:quad,triple,double',
            'jumlah_jamaah' => 'required|integer|min:1',
            'nama_keluarga' => 'nullable|string',
            'harga_paket' => 'required|numeric', // Price per pax
            'diskon' => 'required|numeric', // Total Discount
            // 'total_bayar' => 'required|numeric', // We usually don't edit total_bayar directly here as it comes from payments, but if valid requirement allows:
            'catatan' => 'nullable|string'
        ]);

        try {
            $customerUmroh = \App\Models\CustomerUmroh::findOrFail($id);

            // Recalculate financial fields
            $unitPrice = $validated['harga_paket'];
            $qty = $validated['jumlah_jamaah'];
            $discount = $validated['diskon'];
            
            $totalTagihan = ($unitPrice * $qty) - $discount;
            // $sisaTagihan = $totalTagihan - $customerUmroh->total_bayar;
            $sisaTagihan = $totalTagihan - $customerUmroh->total_bayar;

            $customerUmroh->update([
                'jamaah_id' => $validated['jamaah_id'],
                'agent_id' => $validated['agent_id'],
                'tipe_kamar' => $validated['tipe_kamar'],
                'jumlah_jamaah' => $validated['jumlah_jamaah'],
                'nama_keluarga' => $validated['nama_keluarga'],
                'harga_paket' => $validated['harga_paket'],
                'diskon' => $validated['diskon'],
                'total_tagihan' => $totalTagihan,
                // 'total_bayar' => not updated here, updated via Payment module
                'sisa_tagihan' => $sisaTagihan,
                'status_visa' => $request->boolean('status_visa'),
                'status_tiket' => $request->boolean('status_tiket'),
                'status_siskopatuh' => $request->boolean('status_siskopatuh'),
                'status_perlengkapan' => $request->boolean('status_perlengkapan'),
                'catatan' => $validated['catatan']
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Data Jamaah berhasil diperbarui',
                'redirect' => route('customer-umroh.index', $customerUmroh->keberangkatan_umroh_id)
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $customerUmroh = \App\Models\CustomerUmroh::findOrFail($id);
            $keberangkatanId = $customerUmroh->keberangkatan_umroh_id;
            
            // Optional: Check if payments exist?
            $customerUmroh->delete();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Jamaah berhasil dihapus'
                ]);
            }
            
            return redirect()->route('customer-umroh.index', $keberangkatanId)->with('success', 'Data Jamaah berhasil dihapus');

        } catch (\Exception $e) {
             if (request()->wantsJson()) {
                 return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
             }
             return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
