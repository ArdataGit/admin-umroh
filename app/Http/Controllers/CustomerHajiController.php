<?php

namespace App\Http\Controllers;

use App\Models\KeberangkatanHaji;
use App\Models\CustomerHaji;
use Illuminate\Http\Request;

class CustomerHajiController extends Controller
{
    public function index($id)
    {
        $keberangkatan = KeberangkatanHaji::with(['paketHaji.maskapai'])->findOrFail($id);
        $customerHajis = CustomerHaji::with(['jamaah', 'agent'])->where('keberangkatan_haji_id', $id)->get();
        
        // Calculate Summaries
        $summary = [
            'jumlah_jamaah' => $customerHajis->sum('jumlah_jamaah'),
            'total_harga_paket' => $customerHajis->sum(function($row) {
                return $row->total_tagihan + $row->diskon;
            }),
            'total_diskon' => $customerHajis->sum('diskon'),
            'total_transaksi' => $customerHajis->sum('total_tagihan'),
            'total_bayar' => $customerHajis->sum('total_bayar'),
            'total_sisa' => $customerHajis->sum('sisa_tagihan'),
        ];

        return view('pages.customer-haji.index', [
            'title' => 'Manifest Jamaah Haji',
            'keberangkatan' => $keberangkatan,
            'customerHajis' => $customerHajis,
            'summary' => $summary
        ]);
    }

    public function create($id)
    {
        $keberangkatan = KeberangkatanHaji::with('paketHaji')->findOrFail($id);
        $jamaahs = \App\Models\Jamaah::all();
        $agents = \App\Models\Agent::all();

        return view('pages.customer-haji.create', [
            'title' => 'Tambah Jamaah Haji',
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

            $customerHaji = CustomerHaji::create([
                'keberangkatan_haji_id' => $id,
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
                'status_visa' => $request->has('status_visa'),
                'status_tiket' => $request->has('status_tiket'),
                'status_siskopatuh' => $request->has('status_siskopatuh'),
                'status_perlengkapan' => $request->has('status_perlengkapan'),
                'catatan' => $validated['catatan']
            ]);

            // Auto-create PembayaranHaji Record if total_bayar > 0
            if ($validated['total_bayar'] > 0) {
                // Create temporary record to get ID
                $pembayaran = \App\Models\PembayaranHaji::create([
                    'customer_haji_id' => $customerHaji->id,
                    'kode_transaksi' => 'TEMP-' . uniqid(),
                    'jumlah_pembayaran' => $validated['total_bayar'],
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'status_pembayaran' => 'checked',
                    'tanggal_pembayaran' => now(),
                    'catatan' => 'Pembayaran awal manifest'
                ]);

                // Update with correct Code Format: INV/H/{kode jamaah}/{kode keberangkatan}/{id}
                $keberangkatan = \App\Models\KeberangkatanHaji::find($id);
                $jamaah = \App\Models\Jamaah::find($validated['jamaah_id']);

                $kodeTransaksi = "INV/H/{$jamaah->kode_jamaah}/{$keberangkatan->kode_keberangkatan}/{$pembayaran->id}";

                $pembayaran->update(['kode_transaksi' => $kodeTransaksi]);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Jamaah Haji berhasil ditambahkan',
                'redirect' => route('customer-haji.index', $id)
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }
}
