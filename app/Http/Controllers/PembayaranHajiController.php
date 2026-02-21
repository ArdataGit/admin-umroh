<?php

namespace App\Http\Controllers;

use App\Models\PembayaranHaji;
use App\Models\CustomerHaji;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class PembayaranHajiController extends Controller
{
    public function index()
    {
        $pembayarans = PembayaranHaji::with(['customerHaji.jamaah', 'customerHaji.keberangkatanHaji.paketHaji', 'customerHaji.agent'])
            ->latest()
            ->get();

        return view('pages.pembayaran-haji.index', [
            'title' => 'Data Pembayaran Haji',
            'pembayarans' => $pembayarans
        ]);
    }

    // Detail Transaksi
    public function show($id)
    {
        $pembayaran = PembayaranHaji::with(['customerHaji.jamaah', 'customerHaji.keberangkatanHaji.paketHaji', 'customerHaji.agent'])->findOrFail($id);
        
        return view('pages.pembayaran-haji.show', [
            'title' => 'Detail Pembayaran Haji',
            'pembayaran' => $pembayaran
        ]);
    }

    // Edit Transaksi
    public function edit($id)
    {
        $pembayaran = PembayaranHaji::with(['customerHaji.jamaah'])->findOrFail($id);
        
        return view('pages.pembayaran-haji.edit', [
            'title' => 'Edit Pembayaran Haji',
            'pembayaran' => $pembayaran
        ]);
    }

    // Update Transaksi
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

        $pembayaran = PembayaranHaji::findOrFail($id);
        
        $pembayaran->update($validated);

        // Update Sisa Tagihan in CustomerHaji
        $customerHaji = $pembayaran->customerHaji;
        // Recalculate total paid
        $totalPaid = $customerHaji->pembayaranHaji->where('status_pembayaran', '!=', 'failed')->sum('jumlah_pembayaran');
        
        $customerHaji->update([
            'total_bayar' => $totalPaid,
            'sisa_tagihan' => $customerHaji->total_tagihan - $totalPaid
        ]);

        return redirect()->route('pembayaran-haji.index')->with('success', 'Data pembayaran haji berhasil diperbarui');
    }

    // History List per Jamaah (CustomerHaji)
    public function history($id)
    {
        $customerHaji = CustomerHaji::with(['jamaah', 'keberangkatanHaji.paketHaji', 'pembayaranHaji'])->findOrFail($id);
        
        return view('pages.pembayaran-haji.history', [
            'title' => 'Riwayat Pembayaran Haji - ' . $customerHaji->jamaah->nama_jamaah,
            'customerHaji' => $customerHaji,
            'pembayarans' => $customerHaji->pembayaranHaji()->latest()->get()
        ]);
    }

    // Form Add Payment
    public function createPayment($id)
    {
        $customerHaji = CustomerHaji::with(['jamaah', 'keberangkatanHaji'])->findOrFail($id);
        
        return view('pages.pembayaran-haji.create_payment', [
            'title' => 'Tambah Pembayaran Haji',
            'customerHaji' => $customerHaji
        ]);
    }

    // Store New Payment
    public function storePayment(Request $request, $id)
    {
        $customerHaji = CustomerHaji::findOrFail($id);

        $validated = $request->validate([
            'jumlah_pembayaran' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'tanggal_pembayaran' => 'required|date',
            'catatan' => 'nullable|string',
            'kode_referensi' => 'nullable|string',
        ]);

        // Create Payment
        $pembayaran = PembayaranHaji::create([
            'customer_haji_id' => $customerHaji->id,
            'kode_transaksi' => 'TEMP-' . uniqid(),
            'jumlah_pembayaran' => $validated['jumlah_pembayaran'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_pembayaran' => 'checked', // Direct 'paid/checked' for manual entry
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
            'catatan' => $validated['catatan'],
            'kode_referensi' => $validated['kode_referensi']
        ]);

        // Generate Transaction Code
        $keberangkatan = $customerHaji->keberangkatanHaji;
        $kodeJamaah = $customerHaji->jamaah->kode_jamaah;
        $kodeTransaksi = "INV/H/{$kodeJamaah}/{$keberangkatan->kode_keberangkatan}/{$pembayaran->id}";
        $pembayaran->update(['kode_transaksi' => $kodeTransaksi]);

        // Update Customer Totals
        $totalPaid = $customerHaji->pembayaranHaji->where('status_pembayaran', '!=', 'failed')->sum('jumlah_pembayaran');
        
        $customerHaji->update([
            'total_bayar' => $totalPaid,
            'sisa_tagihan' => $customerHaji->total_tagihan - $totalPaid
        ]);

        return redirect()->route('pembayaran-haji.history', $customerHaji->id)->with('success', 'Pembayaran haji berhasil ditambahkan');
    }

    public function exportPdf($id)
    {
        $pembayaran = PembayaranHaji::with(['customerHaji.jamaah', 'customerHaji.keberangkatanHaji.paketHaji'])->findOrFail($id);
        $customerHaji = $pembayaran->customerHaji;

        $total_bayar = $customerHaji->pembayaranHaji()->where('status_pembayaran', '!=', 'failed')->sum('jumlah_pembayaran');
        $sisa_pembayaran = $customerHaji->total_tagihan - $total_bayar;

        $pdf = Pdf::loadView('pages.pembayaran-haji.pdf', [
            'title' => 'Invoice Pembayaran Haji - ' . $pembayaran->kode_transaksi,
            'pembayaran' => $pembayaran,
            'customerHaji' => $customerHaji,
            'total_bayar' => $total_bayar,
            'sisa_pembayaran' => $sisa_pembayaran
        ]);

        return $pdf->download('Invoice_' . Str::slug($pembayaran->kode_transaksi) . '.pdf');
    }

    public function printPdf($id)
    {
        $pembayaran = PembayaranHaji::with(['customerHaji.jamaah', 'customerHaji.keberangkatanHaji.paketHaji'])->findOrFail($id);
        $customerHaji = $pembayaran->customerHaji;

        $total_bayar = $customerHaji->pembayaranHaji()->where('status_pembayaran', '!=', 'failed')->sum('jumlah_pembayaran');
        $sisa_pembayaran = $customerHaji->total_tagihan - $total_bayar;

        $pdf = Pdf::loadView('pages.pembayaran-haji.pdf', [
            'title' => 'Invoice Pembayaran Haji - ' . $pembayaran->kode_transaksi,
            'pembayaran' => $pembayaran,
            'customerHaji' => $customerHaji,
            'total_bayar' => $total_bayar,
            'sisa_pembayaran' => $sisa_pembayaran
        ]);

        return $pdf->stream('Invoice_' . Str::slug($pembayaran->kode_transaksi) . '.pdf');
    }
}
