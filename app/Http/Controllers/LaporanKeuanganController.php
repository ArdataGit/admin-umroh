<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembayaranTiket;
use App\Models\PembayaranLayanan;
use App\Models\PemasukanUmum;
use App\Models\PembayaranUmroh;
use App\Models\PembayaranHaji;
use App\Models\PengeluaranUmum;
use App\Models\PengeluaranUmroh;
use App\Models\PengeluaranHaji;
use App\Models\PengeluaranProduk;
use Illuminate\Support\Collection;

class LaporanKeuanganController extends Controller
{
    private function getTransactions()
    {
        // MERGE ALL DATA
        $transactions = new Collection();

        // 1. Pembayaran Tiket (Income)
        $pembayaranTiket = PembayaranTiket::with('transaksiTiket.pelanggan')->get();
        foreach ($pembayaranTiket as $item) {
            $transactions->push([
                'date' => $item->tanggal_pembayaran,
                'source' => 'Pembayaran Tiket',
                'invoice' => $item->kode_transaksi,
                'description' => $item->catatan ?? 'Pembayaran Tiket ' . ($item->transaksiTiket->kode_transaksi ?? ''),
                'income' => $item->jumlah_pembayaran,
                'expense' => 0,
                'raw_date' => $item->tanggal_pembayaran,
                'created_at' => $item->created_at
            ]);
        }

        // 2. Pembayaran Layanan (Income)
        $pembayaranLayanan = PembayaranLayanan::with('transaksiLayanan.pelanggan')->get();
        foreach ($pembayaranLayanan as $item) {
            $transactions->push([
                'date' => $item->tanggal_pembayaran,
                'source' => 'Pembayaran Layanan',
                'invoice' => $item->kode_transaksi,
                'description' => $item->catatan ?? 'Pembayaran Layanan ' . ($item->transaksiLayanan->kode_transaksi ?? ''),
                'income' => $item->jumlah_pembayaran,
                'expense' => 0,
                'raw_date' => $item->tanggal_pembayaran,
                'created_at' => $item->created_at
            ]);
        }

        // 3. Pemasukan Umum (Income)
        $pemasukanUmum = PemasukanUmum::all();
        foreach ($pemasukanUmum as $item) {
            $transactions->push([
                'date' => $item->tanggal_pemasukan,
                'source' => 'Pemasukan Umum',
                'invoice' => $item->kode_pemasukan,
                'description' => $item->nama_pemasukan . ($item->catatan_pemasukan ? ' - ' . $item->catatan_pemasukan : ''),
                'income' => $item->jumlah_pemasukan,
                'expense' => 0,
                'raw_date' => $item->tanggal_pemasukan,
                'created_at' => $item->created_at
            ]);
        }
        
        // 4. Pembayaran Umroh (Income)
        $pembayaranUmroh = PembayaranUmroh::with('customerUmroh')->get();
        foreach ($pembayaranUmroh as $item) {
            $transactions->push([
                'date' => $item->tanggal_pembayaran,
                'source' => 'Pembayaran Umroh',
                'invoice' => $item->kode_transaksi,
                'description' => $item->catatan ?? 'Pembayaran Umroh - ' . ($item->customerUmroh->nama_lengkap ?? ''),
                'income' => $item->jumlah_pembayaran,
                'expense' => 0,
                'raw_date' => $item->tanggal_pembayaran,
                'created_at' => $item->created_at
            ]);
        }

        // 5. Pembayaran Haji (Income)
        $pembayaranHaji = PembayaranHaji::with('customerHaji')->get();
        foreach ($pembayaranHaji as $item) {
            $transactions->push([
                'date' => $item->tanggal_pembayaran,
                'source' => 'Pembayaran Haji',
                'invoice' => $item->kode_transaksi,
                'description' => $item->catatan ?? 'Pembayaran Haji - ' . ($item->customerHaji->nama_lengkap ?? ''),
                'income' => $item->jumlah_pembayaran,
                'expense' => 0,
                'raw_date' => $item->tanggal_pembayaran,
                'created_at' => $item->created_at
            ]);
        }

        // 6. Pengeluaran Umum (Expense)
        $pengeluaranUmum = PengeluaranUmum::all();
        foreach ($pengeluaranUmum as $item) {
            $transactions->push([
                'date' => $item->tanggal_pengeluaran,
                'source' => 'Pengeluaran Umum',
                'invoice' => $item->kode_pengeluaran,
                'description' => $item->nama_pengeluaran . ($item->catatan_pengeluaran ? ' - ' . $item->catatan_pengeluaran : ''),
                'income' => 0,
                'expense' => $item->jumlah_pengeluaran,
                'raw_date' => $item->tanggal_pengeluaran,
                'created_at' => $item->created_at
            ]);
        }

        // 7. Pengeluaran Umroh (Expense)
        $pengeluaranUmroh = PengeluaranUmroh::get();
        foreach ($pengeluaranUmroh as $item) {
            $transactions->push([
                'date' => $item->tanggal_pengeluaran,
                'source' => 'Pengeluaran Umroh',
                'invoice' => $item->kode_pengeluaran,
                'description' => $item->nama_pengeluaran . ' - ' . $item->jenis_pengeluaran,
                'income' => 0,
                'expense' => $item->jumlah_pengeluaran,
                'raw_date' => $item->tanggal_pengeluaran,
                'created_at' => $item->created_at
            ]);
        }

        // 8. Pengeluaran Haji (Expense)
        $pengeluaranHaji = PengeluaranHaji::get();
        foreach ($pengeluaranHaji as $item) {
            $transactions->push([
                'date' => $item->tanggal_pengeluaran,
                'source' => 'Pengeluaran Haji',
                'invoice' => $item->kode_pengeluaran,
                'description' => $item->nama_pengeluaran . ' - ' . $item->jenis_pengeluaran,
                'income' => 0,
                'expense' => $item->jumlah_pengeluaran,
                'raw_date' => $item->tanggal_pengeluaran,
                'created_at' => $item->created_at
            ]);
        }

        // 9. Pengeluaran Produk (Income) - User Request: Masuk di Pemasukan
        $pengeluaranProduk = PengeluaranProduk::with('jamaah')->get();
        foreach ($pengeluaranProduk as $item) {
            $transactions->push([
                'date' => $item->tanggal_pengeluaran,
                'source' => 'Pengeluaran Produk',
                'invoice' => $item->kode_pengeluaran,
                'description' => $item->catatan ?? 'Pengeluaran Produk',
                'income' => $item->total_nominal, 
                'expense' => 0,
                'raw_date' => $item->tanggal_pengeluaran,
                'created_at' => $item->created_at
            ]);
        }

        // 10. Pembelian Produk (Expense)
        $pembelianProduk = \App\Models\PembelianProduk::with('supplier')->get();
        foreach ($pembelianProduk as $item) {
            $transactions->push([
                'date' => $item->tanggal_pembelian,
                'source' => 'Pembelian Produk',
                'invoice' => $item->kode_pembelian,
                'description' => 'Pembelian Produk' . ($item->supplier ? ' - ' . $item->supplier->nama_supplier : ''),
                'income' => 0,
                'expense' => $item->jumlah_bayar ?? $item->total_pembayaran, // Prefer amount paid for cash flow
                'raw_date' => $item->tanggal_pembelian,
                'created_at' => $item->created_at
            ]);
        }

        return $transactions;
    }

    public function index(Request $request)
    {
        $period = $request->input('period', 'today'); // Default to today
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Determine Date Range
        $now = \Carbon\Carbon::now();
        if ($period == 'today') {
            $startDate = $now->format('Y-m-d');
            $endDate = $now->format('Y-m-d');
            $periodLabel = $now->format('d-m-Y');
        } elseif ($period == 'month') {
            $startDate = $now->startOfMonth()->format('Y-m-d');
            $endDate = $now->endOfMonth()->format('Y-m-d');
            $periodLabel = $now->translatedFormat('F Y');
        } elseif ($period == 'year') {
            $startDate = $now->startOfYear()->format('Y-m-d');
            $endDate = $now->endOfYear()->format('Y-m-d');
            $periodLabel = $now->format('Y');
        } elseif ($period == 'custom') {
            // Use provided start_date and end_date
            if (!$startDate) $startDate = $now->format('Y-m-d');
            if (!$endDate) $endDate = $now->format('Y-m-d');
            $periodLabel = \Carbon\Carbon::parse($startDate)->format('d-m-Y') . ' / ' . \Carbon\Carbon::parse($endDate)->format('d-m-Y');
        } else {
             // Fallback
             $startDate = $now->format('Y-m-d');
             $endDate = $now->format('Y-m-d');
             $periodLabel = $now->format('d-m-Y');
        }

        $transactions = $this->getTransactions();

        // SORT BY DATE ASC THEN CREATED_AT ASC
        $sortedTransactions = $transactions->sort(function ($a, $b) {
            $dateA = \Carbon\Carbon::parse($a['raw_date'])->format('Y-m-d');
            $dateB = \Carbon\Carbon::parse($b['raw_date'])->format('Y-m-d');

            if ($dateA == $dateB) {
                return $a['created_at'] <=> $b['created_at'];
            }
            return $dateA <=> $dateB;
        })->values();

        // CALCULATE RUNNING BALANCE
        $runningBalance = 0;
        $finalData = $sortedTransactions->map(function ($item) use (&$runningBalance) {
            $runningBalance += $item['income'];
            $runningBalance -= $item['expense'];
            $item['saldo'] = $runningBalance;
            return (object) $item; // Convert to object for easier blade access
        });

        // Filter by Period
        $filteredData = $finalData->filter(function($item) use ($startDate, $endDate) {
            $itemDate = \Carbon\Carbon::parse($item->raw_date)->format('Y-m-d');
            return $itemDate >= $startDate && $itemDate <= $endDate;
        })->values();
        
        // Final balance to show is the last transaction's saldo in the filtered period, 
        // or the balance just before the period if no transactions.
        $lastFiltered = $filteredData->last();
        if ($lastFiltered) {
            $displayBalance = $lastFiltered->saldo;
        } else {
            // Find the last transaction before start date
            $beforeData = $finalData->filter(function($item) use ($startDate) {
                return \Carbon\Carbon::parse($item->raw_date)->format('Y-m-d') < $startDate;
            })->last();
            $displayBalance = $beforeData ? $beforeData->saldo : 0;
        }

        return view('pages.laporan-keuangan.index', [
            'title' => 'Laporan Keuangan',
            'transactions' => $filteredData,
            'total_income' => $filteredData->sum('income'),
            'total_expense' => $filteredData->sum('expense'),
            'final_balance' => $displayBalance,
            'currentPeriod' => $period,
            'periodLabel' => $periodLabel,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function export(Request $request)
    {
        $period = $request->input('period', 'today'); // Default to today
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $now = \Carbon\Carbon::now();
        if ($period == 'today') {
            $startDate = $now->format('Y-m-d');
            $endDate = $now->format('Y-m-d');
        } elseif ($period == 'month') {
            $startDate = $now->startOfMonth()->format('Y-m-d');
            $endDate = $now->endOfMonth()->format('Y-m-d');
        } elseif ($period == 'year') {
            $startDate = $now->startOfYear()->format('Y-m-d');
            $endDate = $now->endOfYear()->format('Y-m-d');
        } elseif ($period == 'custom') {
            if (!$startDate) $startDate = $now->format('Y-m-d');
            if (!$endDate) $endDate = $now->format('Y-m-d');
        } else {
             $startDate = $now->format('Y-m-d');
             $endDate = $now->format('Y-m-d');
        }

        $transactions = $this->getTransactions();

        // SORT BY DATE ASC THEN CREATED_AT ASC
        $sortedTransactions = $transactions->sort(function ($a, $b) {
            $dateA = \Carbon\Carbon::parse($a['raw_date'])->format('Y-m-d');
            $dateB = \Carbon\Carbon::parse($b['raw_date'])->format('Y-m-d');

            if ($dateA == $dateB) {
                return $a['created_at'] <=> $b['created_at'];
            }
            return $dateA <=> $dateB;
        })->values();

        // Prepare data with running balance for export
        $runningBalance = 0;
        $exportData = $sortedTransactions->map(function ($item) use (&$runningBalance) {
            $runningBalance += $item['income'];
            $runningBalance -= $item['expense'];
            $item['saldo'] = $runningBalance;
            return $item;
        });

        // Filter by Period for export
        $filteredExportData = $exportData->filter(function($item) use ($startDate, $endDate) {
            $itemDate = \Carbon\Carbon::parse($item['raw_date'])->format('Y-m-d');
            return $itemDate >= $startDate && $itemDate <= $endDate;
        })->values();

        $filename = "laporan_keuangan_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Tanggal', 'Sumber', 'No Transaksi', 'Keterangan', 'Pemasukan', 'Pengeluaran', 'Saldo'];

        $callback = function() use ($filteredExportData, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($filteredExportData as $row) {
                fputcsv($file, [
                    $row['date'],
                    $row['source'],
                    $row['invoice'],
                    $row['description'],
                    $row['income'],
                    $row['expense'],
                    $row['saldo']
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
