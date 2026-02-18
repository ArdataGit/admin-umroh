<?php

namespace App\Http\Controllers;

use App\Models\PembayaranTiket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranTiketController extends Controller
{
    public function index()
    {
        $pembayarans = PembayaranTiket::with(['transaksiTiket.pelanggan'])
            ->latest()
            ->get();

        return view('pages.pembayaran-tiket.index', [
            'title' => 'Data Pembayaran Tiket',
            'pembayarans' => $pembayarans
        ]);
    }
    public function show($id)
    {
        // Assuming ID is TransaksiTiket ID
        $transaksi = \App\Models\TransaksiTiket::with(['pelanggan', 'details', 'pembayaranTikets'])->findOrFail($id);
        
        return view('pages.pembayaran-tiket.show', [
            'title' => 'Riwayat Pembayaran - ' . ($transaksi->pelanggan->nama_pelanggan ?? 'Umum'),
            'transaksi' => $transaksi,
            'pembayarans' => $transaksi->pembayaranTikets()->latest()->get()
        ]);
    }

    public function createPayment($id)
    {
        $transaksi = \App\Models\TransaksiTiket::with(['pelanggan'])->findOrFail($id);
        
        return view('pages.pembayaran-tiket.create_payment', [
            'title' => 'Tambah Pembayaran',
            'transaksi' => $transaksi
        ]);
    }

    public function storePayment(Request $request, $id)
    {
        $transaksi = \App\Models\TransaksiTiket::findOrFail($id);

        $validated = $request->validate([
            'jumlah_pembayaran' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'tanggal_pembayaran' => 'required|date',
            'catatan' => 'nullable|string',
            'kode_referensi' => 'nullable|string',
        ]);

        // Generate Code for Payment: PT-ID-XXX (Payment Ticket)
        $countPayment = PembayaranTiket::count() + 1;
        $kodePembayaran = 'PT-' . str_pad($countPayment, 5, '0', STR_PAD_LEFT);

        PembayaranTiket::create([
            'transaksi_tiket_id' => $transaksi->id,
            'kode_transaksi' => $kodePembayaran,
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
            'jumlah_pembayaran' => $validated['jumlah_pembayaran'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_pembayaran' => 'paid', // Direct 'paid' for manual entry
            'catatan' => $validated['catatan'],
            'kode_referensi' => $validated['kode_referensi']
        ]);

        return redirect()->route('pembayaran-tiket.show', $transaksi->id)->with('success', 'Pembayaran berhasil ditambahkan');
    }

    public function detail($id)
    {
        $pembayaran = PembayaranTiket::with(['transaksiTiket.pelanggan'])->findOrFail($id);

        return view('pages.pembayaran-tiket.detail', [
            'title' => 'Detail Pembayaran Tiket',
            'pembayaran' => $pembayaran
        ]);
    }

    public function edit($id)
    {
        $pembayaran = PembayaranTiket::with(['transaksiTiket.pelanggan'])->findOrFail($id);

        return view('pages.pembayaran-tiket.edit', [
            'title' => 'Edit Pembayaran Tiket',
            'pembayaran' => $pembayaran,
            'transaksi' => $pembayaran->transaksiTiket
        ]);
    }

    public function update(Request $request, $id)
    {
        $pembayaran = PembayaranTiket::findOrFail($id);

        $validated = $request->validate([
            'jumlah_pembayaran' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'tanggal_pembayaran' => 'required|date',
            'catatan' => 'nullable|string',
            'kode_referensi' => 'nullable|string',
        ]);

        $pembayaran->update($validated);

        return redirect()->route('pembayaran-tiket.show', $pembayaran->transaksi_tiket_id)->with('success', 'Pembayaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pembayaran = PembayaranTiket::findOrFail($id);
        $transaksiId = $pembayaran->transaksi_tiket_id;
        $pembayaran->delete();

        return redirect()->route('pembayaran-tiket.show', $transaksiId)->with('success', 'Pembayaran berhasil dihapus');
    }

    public function exportPdf($id)
    {
        $pembayaran = PembayaranTiket::with([
            'transaksiTiket.pelanggan',
            'transaksiTiket.details.ticket',
            'transaksiTiket.pembayaranTikets'
        ])->findOrFail($id);

        $transaksi = $pembayaran->transaksiTiket;
        $totalBayar = $transaksi->pembayaranTikets->where('status_pembayaran', 'paid')->sum('jumlah_pembayaran');
        $sisaPembayaran = $transaksi->total_transaksi - $totalBayar;
        
        $pdf = Pdf::loadView('pages.pembayaran-tiket.pdf', [
            'title' => 'Invoice Transaksi Tiket - ' . $transaksi->kode_transaksi,
            'pembayaran' => $pembayaran,
            'transaksi' => $transaksi,
            'total_bayar' => $totalBayar,
            'sisa_pembayaran' => $sisaPembayaran
        ]);

        $firstDetail = $transaksi->details->first();
        $dateSuffix = '';
        if ($firstDetail && $firstDetail->ticket) {
            $dateSuffix = '_' . $firstDetail->ticket->tanggal_keberangkatan . '_' . $firstDetail->ticket->tanggal_kepulangan;
        }
        
        return $pdf->download('Invoice_' . Str::slug($transaksi->kode_transaksi) . $dateSuffix . '.pdf');
    }

    public function printPdf($id)
    {
        $pembayaran = PembayaranTiket::with([
            'transaksiTiket.pelanggan',
            'transaksiTiket.details.ticket',
            'transaksiTiket.pembayaranTikets'
        ])->findOrFail($id);

        $transaksi = $pembayaran->transaksiTiket;
        $totalBayar = $transaksi->pembayaranTikets->where('status_pembayaran', 'paid')->sum('jumlah_pembayaran');
        $sisaPembayaran = $transaksi->total_transaksi - $totalBayar;
        
        $pdf = Pdf::loadView('pages.pembayaran-tiket.pdf', [
            'title' => 'Invoice Transaksi Tiket - ' . $transaksi->kode_transaksi,
            'pembayaran' => $pembayaran,
            'transaksi' => $transaksi,
            'total_bayar' => $totalBayar,
            'sisa_pembayaran' => $sisaPembayaran
        ]);

        $firstDetail = $transaksi->details->first();
        $dateSuffix = '';
        if ($firstDetail && $firstDetail->ticket) {
            $dateSuffix = '_' . $firstDetail->ticket->tanggal_keberangkatan . '_' . $firstDetail->ticket->tanggal_kepulangan;
        }
        
        return $pdf->stream('Invoice_' . Str::slug($transaksi->kode_transaksi) . $dateSuffix . '.pdf');
    }

    public function export()
    {
        $pembayarans = PembayaranTiket::with(['transaksiTiket.pelanggan'])->latest()->get();
        $filename = "data_pembayaran_tiket_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No', 'Tanggal', 'Kode Transaksi', 'Trx. Tiket', 'Nama Mitra', 'Jumlah', 'Metode', 'Status', 'Referensi'];

        $callback = function() use ($pembayarans, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($pembayarans as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    $item->tanggal_pembayaran,
                    $item->kode_transaksi,
                    $item->transaksi_tiket->kode_transaksi ?? '-',
                    $item->transaksi_tiket->pelanggan->nama_pelanggan ?? '-',
                    $item->jumlah_pembayaran,
                    $item->metode_pembayaran,
                    $item->status_pembayaran,
                    $item->kode_referensi
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printData()
    {
        $pembayarans = PembayaranTiket::with(['transaksiTiket.pelanggan'])->latest()->get();
        return view('pages.pembayaran-tiket.print', [
            'pembayarans' => $pembayarans,
            'title' => 'Laporan Pembayaran Tiket'
        ]);
    }
}
