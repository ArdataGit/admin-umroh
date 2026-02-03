<?php

namespace App\Http\Controllers;

use App\Models\PembayaranUmroh;
use Illuminate\Http\Request;

class PembayaranUmrohController extends Controller
{
    public function index()
    {
        $pembayarans = PembayaranUmroh::with(['customerUmroh.jamaah', 'customerUmroh.keberangkatanUmroh', 'customerUmroh.agent'])
            ->latest()
            ->get();

        return view('pages.pembayaran-umroh.index', [
            'title' => 'Data Pembayaran Umroh',
            'pembayarans' => $pembayarans
        ]);
    }
    public function show($id)
    {
        $pembayaran = PembayaranUmroh::with(['customerUmroh.jamaah', 'customerUmroh.keberangkatanUmroh.paketUmroh', 'customerUmroh.agent'])->findOrFail($id);
        
        return view('pages.pembayaran-umroh.show', [
            'title' => 'Detail Pembayaran',
            'pembayaran' => $pembayaran
        ]);
    }

    public function edit($id)
    {
        $pembayaran = PembayaranUmroh::with(['customerUmroh.jamaah'])->findOrFail($id);
        
        return view('pages.pembayaran-umroh.edit', [
            'title' => 'Edit Pembayaran',
            'pembayaran' => $pembayaran
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jumlah_pembayaran' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'status_pembayaran' => 'required|in:pending,paid,failed,checked',
            'tanggal_pembayaran' => 'required|date',
            'catatan' => 'nullable|string',
            'kode_referensi' => 'nullable|string',
        ]);

        $pembayaran = PembayaranUmroh::findOrFail($id);
        
        $pembayaran->update($validated);

        // Update Sisa Tagihan in CustomerUmroh
        $customerUmroh = $pembayaran->customerUmroh;
        // Recalculate total paid
        $totalPaid = $customerUmroh->pembayaranUmroh->where('status_pembayaran', '!=', 'failed')->sum('jumlah_pembayaran');
        
        $customerUmroh->update([
            'total_bayar' => $totalPaid,
            'sisa_tagihan' => $customerUmroh->total_tagihan - $totalPaid
        ]);

        return redirect()->route('pembayaran-umroh.index')->with('success', 'Data pembayaran berhasil diperbarui');
    }
    // History List per Jamaah (CustomerUmroh)
    public function history($id)
    {
        $customerUmroh = \App\Models\CustomerUmroh::with(['jamaah', 'keberangkatanUmroh.paketUmroh', 'pembayaranUmroh'])->findOrFail($id);
        
        return view('pages.pembayaran-umroh.history', [
            'title' => 'Riwayat Pembayaran - ' . $customerUmroh->jamaah->nama_jamaah,
            'customerUmroh' => $customerUmroh,
            'pembayarans' => $customerUmroh->pembayaranUmroh()->latest()->get()
        ]);
    }

    // Form Add Payment
    public function createPayment($id)
    {
        $customerUmroh = \App\Models\CustomerUmroh::with(['jamaah', 'keberangkatanUmroh'])->findOrFail($id);
        
        return view('pages.pembayaran-umroh.create_payment', [
            'title' => 'Tambah Pembayaran',
            'customerUmroh' => $customerUmroh
        ]);
    }

    // Store New Payment
    public function storePayment(Request $request, $id)
    {
        $customerUmroh = \App\Models\CustomerUmroh::findOrFail($id);

        $validated = $request->validate([
            'jumlah_pembayaran' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'tanggal_pembayaran' => 'required|date',
            'catatan' => 'nullable|string',
            'kode_referensi' => 'nullable|string',
        ]);

        // Create Payment
        $pembayaran = PembayaranUmroh::create([
            'customer_umroh_id' => $customerUmroh->id,
            'kode_transaksi' => 'TEMP-' . uniqid(),
            'jumlah_pembayaran' => $validated['jumlah_pembayaran'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_pembayaran' => 'checked', // Direct 'paid/checked' for manual entry
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
            'catatan' => $validated['catatan'],
            'kode_referensi' => $validated['kode_referensi']
        ]);

        // Generate Transaction Code
        $keberangkatan = $customerUmroh->keberangkatanUmroh;
        $kodeJamaah = $customerUmroh->jamaah->kode_jamaah;
        $kodeTransaksi = "INV/CR/{$kodeJamaah}/{$keberangkatan->kode_keberangkatan}/{$pembayaran->id}";
        $pembayaran->update(['kode_transaksi' => $kodeTransaksi]);

        // Update Customer Totals
        $totalPaid = $customerUmroh->pembayaranUmroh->where('status_pembayaran', '!=', 'failed')->sum('jumlah_pembayaran');
        
        $customerUmroh->update([
            'total_bayar' => $totalPaid,
            'sisa_tagihan' => $customerUmroh->total_tagihan - $totalPaid
        ]);

        return redirect()->route('pembayaran-umroh.history', $customerUmroh->id)->with('success', 'Pembayaran berhasil ditambahkan');
    }
}
