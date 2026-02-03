<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiTiketDetail;
use App\Models\TransaksiLayananDetail;
use App\Models\PengeluaranProdukDetail;
use Illuminate\Support\Collection;

class RugiLabaPenjualanController extends Controller
{
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

        $reportData = new Collection();

        // 1. Transactions Tiket
        $tiketDetails = TransaksiTiketDetail::whereHas('transaksiTiket', function($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
        })->with(['transaksiTiket', 'ticket'])->get();

        foreach ($tiketDetails as $detail) {
            if (!$detail->transaksiTiket) continue;

            $qty = $detail->quantity;
            $revenue = $detail->total_harga; // Selling Price Total
            $unitCost = $detail->ticket->harga_modal ?? 0;
            $totalCost = $unitCost * $qty;
            $profit = $revenue - $totalCost;

            $reportData->push([
                'date' => $detail->transaksiTiket->tanggal_transaksi,
                'no_transaksi' => $detail->transaksiTiket->kode_transaksi,
                'type' => 'Tiket',
                'item_name' => $detail->ticket->nama_tiket ?? 'Unknown Ticket',
                'quantity' => $qty,
                'revenue' => $revenue,
                'cogs' => $totalCost,
                'profit' => $profit,
                'raw_date' => $detail->transaksiTiket->tanggal_transaksi
            ]);
        }

        // 2. Transactions Layanan
        $layananDetails = TransaksiLayananDetail::whereHas('transaksiLayanan', function($q) use ($startDate, $endDate) {
             $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
        })->with(['transaksiLayanan', 'layanan'])->get();

        foreach ($layananDetails as $detail) {
            if (!$detail->transaksiLayanan) continue;

            $qty = $detail->quantity;
            $revenue = $detail->total_harga;
            $unitCost = $detail->layanan->harga_modal ?? 0;
            $totalCost = $unitCost * $qty;
            $profit = $revenue - $totalCost;

            $reportData->push([
                'date' => $detail->transaksiLayanan->tanggal_transaksi,
                'no_transaksi' => $detail->transaksiLayanan->kode_transaksi,
                'type' => 'Layanan',
                'item_name' => $detail->layanan->nama_layanan ?? 'Unknown Service',
                'quantity' => $qty,
                'revenue' => $revenue,
                'cogs' => $totalCost,
                'profit' => $profit,
                'raw_date' => $detail->transaksiLayanan->tanggal_transaksi
            ]);
        }

        // 3. Transactions Produk (PengeluaranProduk)
        $produkDetails = PengeluaranProdukDetail::whereHas('pengeluaran', function($q) use ($startDate, $endDate) {
             $q->whereBetween('tanggal_pengeluaran', [$startDate, $endDate]);
        })->with(['pengeluaran', 'produk'])->get();

        foreach ($produkDetails as $detail) {
            if (!$detail->pengeluaran) continue;

            $qty = $detail->quantity;
            $revenue = $detail->total_harga;
            $unitCost = $detail->produk->harga_beli ?? 0; // Cost Price
            $totalCost = $unitCost * $qty;
            $profit = $revenue - $totalCost;

            $reportData->push([
                'date' => $detail->pengeluaran->tanggal_pengeluaran,
                'no_transaksi' => $detail->pengeluaran->kode_pengeluaran,
                'type' => 'Produk',
                'item_name' => $detail->produk->nama_produk ?? 'Unknown Product',
                'quantity' => $qty,
                'revenue' => $revenue,
                'cogs' => $totalCost,
                'profit' => $profit,
                'raw_date' => $detail->pengeluaran->tanggal_pengeluaran
            ]);
        }

        // Sort by Date Descending
        $sortedData = $reportData->sortByDesc('raw_date')->values();

        // Calculate Totals
        $totalRevenue = $sortedData->sum('revenue');
        $totalCOGS = $sortedData->sum('cogs');
        $totalProfit = $sortedData->sum('profit');

        return view('pages.rugi-laba-penjualan.index', [
            'title' => 'Laporan Rugi Laba Penjualan',
            'reportData' => $sortedData,
            'totalRevenue' => $totalRevenue,
            'totalCOGS' => $totalCOGS,
            'totalProfit' => $totalProfit,
            'currentPeriod' => $period,
            'periodLabel' => $periodLabel,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}
