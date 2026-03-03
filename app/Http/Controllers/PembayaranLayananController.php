<?php

namespace App\Http\Controllers;

use App\Models\PembayaranLayanan;
use Illuminate\Http\Request;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CodeGenerator;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranLayananController extends Controller
{
    public function index()
    {
        $pembayarans = PembayaranLayanan::with(['transaksiLayanan.pelanggan'])
            ->latest()
            ->get();

        return view('pages.pembayaran-layanan.index', [
            'title' => 'Data Pembayaran Layanan',
            'pembayarans' => $pembayarans
        ]);
    }

    public function show($id)
    {
        // Assuming ID is TransaksiLayanan ID based on "Partner" context
        $transaksi = \App\Models\TransaksiLayanan::with(['pelanggan', 'details', 'pembayaranLayanans'])->findOrFail($id);
        
        return view('pages.pembayaran-layanan.show', [
            'title' => 'Riwayat Pembayaran - ' . ($transaksi->pelanggan->nama_pelanggan ?? 'Umum'),
            'transaksi' => $transaksi,
            'pembayarans' => $transaksi->pembayaranLayanans()->latest()->get()
        ]);
    }

    public function createPayment($id)
    {
        $transaksi = \App\Models\TransaksiLayanan::with(['pelanggan'])->findOrFail($id);
        
        return view('pages.pembayaran-layanan.create_payment', [
            'title' => 'Tambah Pembayaran',
            'transaksi' => $transaksi
        ]);
    }

    public function storePayment(Request $request, $id)
    {
        $transaksi = \App\Models\TransaksiLayanan::findOrFail($id);

        $validated = $request->validate([
            'jumlah_pembayaran' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'tanggal_pembayaran' => 'required|date',
            'catatan' => 'nullable|string',
            'kode_referensi' => 'nullable|string',
        ]);

        // Generate Code for Payment: PS-XXXXX (Payment Service)
        $kodePembayaran = CodeGenerator::generate(PembayaranLayanan::class, 'kode_transaksi', 'PS-', 5);

        PembayaranLayanan::create([
            'transaksi_layanan_id' => $transaksi->id,
            'kode_transaksi' => $kodePembayaran,
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
            'jumlah_pembayaran' => $validated['jumlah_pembayaran'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_pembayaran' => 'paid', // Direct 'paid' for manual entry
            'catatan' => $validated['catatan'],
            'kode_referensi' => $validated['kode_referensi']
        ]);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Pembayaran Layanan',
            'action' => 'Create',
            'keterangan' => 'Menambahkan pembayaran layanan: ' . $kodePembayaran . ' untuk transaksi: ' . ($transaksi->kode_transaksi)
        ]);

        return redirect()->route('pembayaran-layanan.show', $transaksi->id)->with('success', 'Pembayaran berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pembayaran = PembayaranLayanan::with('transaksiLayanan.pelanggan')->findOrFail($id);
        
        return view('pages.pembayaran-layanan.edit', [
            'title' => 'Edit Pembayaran',
            'pembayaran' => $pembayaran
        ]);
    }

    public function update(Request $request, $id)
    {
        $pembayaran = PembayaranLayanan::findOrFail($id);

        $validated = $request->validate([
            'jumlah_pembayaran' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'tanggal_pembayaran' => 'required|date',
            'catatan' => 'nullable|string',
            'kode_referensi' => 'nullable|string',
        ]);

        $pembayaran->update([
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
            'jumlah_pembayaran' => $validated['jumlah_pembayaran'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'catatan' => $validated['catatan'],
            'kode_referensi' => $validated['kode_referensi']
        ]);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Pembayaran Layanan',
            'action' => 'Update',
            'keterangan' => 'Memperbarui pembayaran layanan: ' . $pembayaran->kode_transaksi
        ]);

        return redirect()->route('pembayaran-layanan.index')->with('success', 'Pembayaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pembayaran = PembayaranLayanan::findOrFail($id);
        $kodePembayaran = $pembayaran->kode_transaksi;
        $transaksiId = $pembayaran->transaksi_layanan_id; // Get the transaction ID before deleting
        $pembayaran->delete();

        // Pencatatan History Action
        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Pembayaran Layanan',
            'action' => 'Delete',
            'keterangan' => 'Menghapus pembayaran: ' . $kodePembayaran
        ]);

        return redirect()->route('pembayaran-layanan.show', $transaksiId)->with('success', 'Pembayaran berhasil dihapus');
    }

    public function exportPdf($id)
    {
        $pembayaran = PembayaranLayanan::with([
            'transaksiLayanan.pelanggan',
            'transaksiLayanan.details.layanan',
            'transaksiLayanan.pembayaranLayanans'
        ])->findOrFail($id);

        $transaksi = $pembayaran->transaksiLayanan;
        $totalBayar = $transaksi->pembayaranLayanans->sum('jumlah_pembayaran');
        $sisaPembayaran = $transaksi->total_transaksi - $totalBayar;
        
        $pdf = Pdf::loadView('pages.pembayaran-layanan.pdf', [
            'title' => 'Invoice Transaksi Layanan - ' . $transaksi->kode_transaksi,
            'pembayaran' => $pembayaran,
            'transaksi' => $transaksi,
            'total_bayar' => $totalBayar,
            'sisa_pembayaran' => $sisaPembayaran
        ]);
        
        return $pdf->download('Invoice_' . Str::slug($transaksi->kode_transaksi) . '.pdf');
    }

    public function printPdf($id)
    {
        $pembayaran = PembayaranLayanan::with([
            'transaksiLayanan.pelanggan',
            'transaksiLayanan.details.layanan',
            'transaksiLayanan.pembayaranLayanans'
        ])->findOrFail($id);

        $transaksi = $pembayaran->transaksiLayanan;
        $totalBayar = $transaksi->pembayaranLayanans->sum('jumlah_pembayaran');
        $sisaPembayaran = $transaksi->total_transaksi - $totalBayar;
        
        $pdf = Pdf::loadView('pages.pembayaran-layanan.pdf', [
            'title' => 'Invoice Transaksi Layanan - ' . $transaksi->kode_transaksi,
            'pembayaran' => $pembayaran,
            'transaksi' => $transaksi,
            'total_bayar' => $totalBayar,
            'sisa_pembayaran' => $sisaPembayaran
        ]);
        
        return $pdf->stream('Invoice_' . Str::slug($transaksi->kode_transaksi) . '.pdf');
    }
}
