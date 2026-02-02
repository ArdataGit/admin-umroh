<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiTiketDetail;
use App\Models\TransaksiLayananDetail;
use App\Models\PengeluaranProdukDetail;
use Illuminate\Support\Collection;

class RugiLabaPenjualanController extends Controller
{
    public function index()
    {
        $reportData = new Collection();

        // 1. Transactions Tiket
        $tiketDetails = TransaksiTiketDetail::with(['transaksiTiket', 'ticket'])->get();
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
        $layananDetails = TransaksiLayananDetail::with(['transaksiLayanan', 'layanan'])->get();
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
        // Assuming PengeluaranProduk is Sales
        $produkDetails = PengeluaranProdukDetail::with(['pengeluaran', 'produk'])->get();
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
            'totalProfit' => $totalProfit
        ]);
    }
}
