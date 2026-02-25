<?php

namespace App\Http\Controllers;

use App\Models\PembayaranTiket;
use App\Models\SystemSetting;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranTiketController extends Controller
{
    public function index()
    {
        $pembayarans = PembayaranTiket::with(['transaksiTiket.pelanggan', 'transaksiTiket.details.ticket'])
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
        
        ExchangeRateService::updateRates();

        $kursUsd = SystemSetting::where('key', 'kurs_usd')->first()->value ?? 0;
        $kursSar = SystemSetting::where('key', 'kurs_sar')->first()->value ?? 0;
        $kursMyr = SystemSetting::where('key', 'kurs_myr')->first()->value ?? (SystemSetting::where('key', 'kurs_rm')->first()->value ?? 0);

        return view('pages.pembayaran-tiket.create_payment', [
            'title' => 'Tambah Pembayaran',
            'transaksi' => $transaksi,
            'kursUsd' => $kursUsd / 100,
            'kursSar' => $kursSar / 100,
            'kursMyr' => $kursMyr / 100
        ]);
    }

    public function storePayment(Request $request, $id)
    {
        $transaksi = \App\Models\TransaksiTiket::findOrFail($id);

        $validated = $request->validate([
            'jumlah_pembayaran' => 'required|numeric|min:1',
            'kurs' => 'required|in:USD,SAR,MYR,IDR',
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'tanggal_pembayaran' => 'required|date',
            'catatan' => 'nullable|string',
            'kode_referensi' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,pdf',
        ]);

        // Handle Currency Conversion
        $kurs = $validated['kurs'] ?? 'IDR';
        if ($kurs !== 'IDR') {
            $rateKey = match($kurs) {
                'USD' => 'kurs_usd',
                'SAR' => 'kurs_sar',
                'MYR' => 'kurs_myr',
                default => null,
            };

            $rateValue = $rateKey ? (SystemSetting::where('key', $rateKey)->first()->value ?? 0) : 0;
            $rate = $rateValue / 100;

            $validated['kurs_asing'] = $validated['jumlah_pembayaran'];
            $validated['jumlah_pembayaran'] = $validated['jumlah_pembayaran'] * $rate;
        } else {
            $validated['kurs_asing'] = 0;
        }

        // Generate Code for Payment: PT-00001
        $lastPayment = \App\Models\PembayaranTiket::orderBy('id', 'desc')->first();
        $nextId = $lastPayment ? ($lastPayment->id + 1) : 1;
        $kodePembayaran = 'PT-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . Str::slug($transaksi->kode_transaksi) . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('bukti_pembayaran_tiket', $filename, 'public');
        }

        PembayaranTiket::create([
            'transaksi_tiket_id' => $transaksi->id,
            'kode_transaksi' => $kodePembayaran,
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
            'jumlah_pembayaran' => $validated['jumlah_pembayaran'],
            'kurs' => $validated['kurs'],
            'kurs_asing' => $validated['kurs_asing'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_pembayaran' => 'paid', // Direct 'paid' for manual entry
            'catatan' => $validated['catatan'],
            'kode_referensi' => $validated['kode_referensi'],
            'bukti_pembayaran' => $buktiPath
        ]);

        return redirect()->route('pembayaran-tiket.show', $transaksi->id)->with('success', 'Pembayaran berhasil ditambahkan');
    }

    public function detail($id)
    {
        $pembayaran = PembayaranTiket::with(['transaksiTiket.pelanggan', 'transaksiTiket.details.ticket'])->findOrFail($id);

        return view('pages.pembayaran-tiket.detail', [
            'title' => 'Detail Pembayaran Tiket',
            'pembayaran' => $pembayaran
        ]);
    }

    public function edit($id)
    {
        $pembayaran = PembayaranTiket::with(['transaksiTiket.pelanggan'])->findOrFail($id);

        ExchangeRateService::updateRates();

        $kursUsd = SystemSetting::where('key', 'kurs_usd')->first()->value ?? 0;
        $kursSar = SystemSetting::where('key', 'kurs_sar')->first()->value ?? 0;
        $kursMyr = SystemSetting::where('key', 'kurs_myr')->first()->value ?? (SystemSetting::where('key', 'kurs_rm')->first()->value ?? 0);

        return view('pages.pembayaran-tiket.edit', [
            'title' => 'Edit Pembayaran Tiket',
            'pembayaran' => $pembayaran,
            'transaksi' => $pembayaran->transaksiTiket,
            'kursUsd' => $kursUsd / 100,
            'kursSar' => $kursSar / 100,
            'kursMyr' => $kursMyr / 100
        ]);
    }

    public function update(Request $request, $id)
    {
        $pembayaran = PembayaranTiket::findOrFail($id);

        $validated = $request->validate([
            'jumlah_pembayaran' => 'required|numeric|min:1',
            'kurs' => 'required|in:USD,SAR,MYR,IDR',
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'tanggal_pembayaran' => 'required|date',
            'catatan' => 'nullable|string',
            'kode_referensi' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // Handle Currency Conversion
        $kurs = $validated['kurs'] ?? 'IDR';
        if ($kurs !== 'IDR') {
            $rateKey = match($kurs) {
                'USD' => 'kurs_usd',
                'SAR' => 'kurs_sar',
                'MYR' => 'kurs_myr',
                default => null,
            };

            $rateValue = $rateKey ? (SystemSetting::where('key', $rateKey)->first()->value ?? 0) : 0;
            $rate = $rateValue / 100;

            $validated['kurs_asing'] = $validated['jumlah_pembayaran'];
            $validated['jumlah_pembayaran'] = $validated['jumlah_pembayaran'] * $rate;
        } else {
            $validated['kurs_asing'] = 0;
        }

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . Str::slug($pembayaran->transaksiTiket->kode_transaksi) . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('bukti_pembayaran_tiket', $filename, 'public');
            
            // Delete old file if exists
            if ($pembayaran->bukti_pembayaran && \Illuminate\Support\Facades\Storage::disk('public')->exists($pembayaran->bukti_pembayaran)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
            }
            
            $validated['bukti_pembayaran'] = $buktiPath;
        }

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

        $columns = ['No', 'Tanggal', 'Kode Transaksi', 'Trx. Tiket', 'Nama Mitra', 'Jumlah', 'Metode', 'Referensi'];

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
