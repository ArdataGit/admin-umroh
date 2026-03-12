<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiTiketDetail;
use App\Models\TransaksiLayananDetail;
use App\Models\PengeluaranProdukDetail;
use App\Models\PengeluaranUmum;
use App\Models\PemasukanUmum;
use App\Models\CustomerUmroh;
use App\Models\CustomerHaji;
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
                'sub_type' => $detail->layanan->jenis_layanan ?? 'Lainnya',
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

        // 4. Pendaftaran Umroh
        $umrohRegistrations = CustomerUmroh::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->with([
                'keberangkatanUmroh.paketUmroh.maskapai',
                'keberangkatanUmroh.paketUmroh.layanans',
                'keberangkatanUmroh.paketUmroh.hotelMekkah1',
                'keberangkatanUmroh.paketUmroh.hotelMadinah1',
                'keberangkatanUmroh.paketUmroh.hotelTransit1',
                'jamaah'
            ])
            ->get();

        foreach ($umrohRegistrations as $reg) {
            $qty = $reg->jumlah_jamaah;
            $revenue = $reg->total_tagihan;
            
            $hppPerPax = 0;
            $tipe = strtolower($reg->tipe_kamar);
            $paket = $reg->keberangkatanUmroh->paketUmroh ?? null;
            
            if ($paket) {
                $hppField = 'hpp_' . $tipe . '1';
                $hppPerPax = $paket->$hppField ?? 0;

                // Fallback: Calculate if 0 (for old data)
                if ($hppPerPax <= 0) {
                    $maskapaiPrice = $paket->maskapai->harga_tiket ?? 0;
                    $serviceTotal = $paket->layanans->sum('harga_jual') ?? 0;
                    $base = $maskapaiPrice + $serviceTotal;

                    $h1_mekkah = ($paket->hotelMekkah1->harga_hotel ?? 0) * ($paket->hari_mekkah_1 ?? 0);
                    $h1_madinah = ($paket->hotelMadinah1->harga_hotel ?? 0) * ($paket->hari_madinah_1 ?? 0);
                    $h1_transit = ($paket->hotelTransit1->harga_hotel ?? 0) * ($paket->hari_transit_1 ?? 0);
                    $hotelTotal = $h1_mekkah + $h1_madinah + $h1_transit;

                    $divisor = ($tipe === 'double' ? 2 : ($tipe === 'triple' ? 3 : 4));
                    $hppPerPax = $base + ($hotelTotal / $divisor);
                }
            }
            
            $totalCost = $hppPerPax * $qty;
            $profit = $revenue - $totalCost;

            $reportData->push([
                'date' => $reg->created_at->format('Y-m-d'),
                'no_transaksi' => $reg->keberangkatanUmroh->kode_keberangkatan ?? 'Pendaftaran',
                'type' => 'Umroh',
                'item_name' => ($paket->nama_paket ?? 'Paket Umroh') . ' - ' . ($reg->jamaah->nama_jamaah ?? 'Jamaah'),
                'quantity' => $qty,
                'revenue' => $revenue,
                'cogs' => $totalCost,
                'profit' => $profit,
                'raw_date' => $reg->created_at
            ]);
        }

        // 5. Pendaftaran Haji
        $hajiRegistrations = CustomerHaji::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->with([
                'keberangkatanHaji.paketHaji.maskapai',
                'keberangkatanHaji.paketHaji.layanans',
                'keberangkatanHaji.paketHaji.hotelMekkah1',
                'keberangkatanHaji.paketHaji.hotelMadinah1',
                'keberangkatanHaji.paketHaji.hotelTransit1',
                'jamaah'
            ])
            ->get();

        foreach ($hajiRegistrations as $reg) {
            $qty = $reg->jumlah_jamaah;
            $revenue = $reg->total_tagihan;
            
            $hppPerPax = 0;
            $tipe = strtolower($reg->tipe_kamar);
            $paket = $reg->keberangkatanHaji->paketHaji ?? null;
            
            if ($paket) {
                $hppField = 'hpp_' . $tipe . '1';
                $hppPerPax = $paket->$hppField ?? 0;

                // Fallback: Calculate if 0 (for old data)
                if ($hppPerPax <= 0) {
                    $maskapaiPrice = $paket->maskapai->harga_tiket ?? 0;
                    $serviceTotal = $paket->layanans->sum('harga_jual') ?? 0;
                    $base = $maskapaiPrice + $serviceTotal;

                    $h1_mekkah = ($paket->hotelMekkah1->harga_hotel ?? 0) * ($paket->hari_mekkah_1 ?? 0);
                    $h1_madinah = ($paket->hotelMadinah1->harga_hotel ?? 0) * ($paket->hari_madinah_1 ?? 0);
                    $h1_transit = ($paket->hotelTransit1->harga_hotel ?? 0) * ($paket->hari_transit_1 ?? 0);
                    $hotelTotal = $h1_mekkah + $h1_madinah + $h1_transit;

                    $divisor = ($tipe === 'double' ? 2 : ($tipe === 'triple' ? 3 : 4));
                    $hppPerPax = $base + ($hotelTotal / $divisor);
                }
            }
            
            $totalCost = $hppPerPax * $qty;
            $profit = $revenue - $totalCost;

            $reportData->push([
                'date' => $reg->created_at->format('Y-m-d'),
                'no_transaksi' => $reg->keberangkatanHaji->kode_keberangkatan ?? 'Pendaftaran',
                'type' => 'Haji',
                'item_name' => ($paket->nama_paket ?? 'Paket Haji') . ' - ' . ($reg->jamaah->nama_jamaah ?? 'Jamaah'),
                'quantity' => $qty,
                'revenue' => $revenue,
                'cogs' => $totalCost,
                'profit' => $profit,
                'raw_date' => $reg->created_at
            ]);
        }

        // 6. Pengeluaran Umum
        $pengeluaranUmums = PengeluaranUmum::whereBetween('tanggal_pengeluaran', [$startDate, $endDate])->get();
        foreach ($pengeluaranUmums as $item) {
            $reportData->push([
                'date' => $item->tanggal_pengeluaran->format('Y-m-d'),
                'no_transaksi' => $item->kode_pengeluaran,
                'type' => 'Pengeluaran Umum',
                'item_name' => $item->nama_pengeluaran,
                'quantity' => 1,
                'revenue' => 0,
                'cogs' => $item->jumlah_pengeluaran,
                'profit' => -$item->jumlah_pengeluaran,
                'raw_date' => $item->tanggal_pengeluaran
            ]);
        }

        // 7. Pemasukan Umum
        $pemasukanUmums = PemasukanUmum::whereBetween('tanggal_pemasukan', [$startDate, $endDate])->get();
        foreach ($pemasukanUmums as $item) {
            $reportData->push([
                'date' => $item->tanggal_pemasukan->format('Y-m-d'),
                'no_transaksi' => $item->kode_pemasukan,
                'type' => 'Pemasukan Umum',
                'item_name' => $item->nama_pemasukan,
                'quantity' => 1,
                'revenue' => $item->jumlah_pemasukan,
                'cogs' => 0,
                'profit' => $item->jumlah_pemasukan,
                'raw_date' => $item->tanggal_pemasukan
            ]);
        }

        // Sort by Date Descending
        $sortedData = $reportData->sortByDesc('raw_date')->values();

        // Calculate Totals
        $totalRevenue = $sortedData->sum('revenue');
        $totalCOGS = $sortedData->sum('cogs');
        $grossProfit = $totalRevenue - $totalCOGS;

        // Sub Totals for Revenue
        $revenueSubTotals = collect();
        
        // Add other types normally
        foreach (['Tiket', 'Produk', 'Umroh', 'Haji'] as $type) {
            $amount = $sortedData->where('type', $type)->sum('revenue');
            if ($amount > 0) {
                $revenueSubTotals->put($type, $amount);
            }
        }

        // Add broken down Layanan
        $layananItems = $sortedData->where('type', 'Layanan');
        $layananGroups = $layananItems->groupBy('sub_type');
        foreach($layananGroups as $subType => $items) {
            $revenueSubTotals->put('Layanan (' . $subType . ')', $items->sum('revenue'));
        }

        // Sub Totals for COGS
        $cogsSubTotals = collect();
        foreach (['Tiket', 'Produk', 'Umroh', 'Haji'] as $type) {
            $amount = $sortedData->where('type', $type)->sum('cogs');
            if ($amount > 0) {
                $cogsSubTotals->put($type, $amount);
            }
        }
        foreach($layananGroups as $subType => $items) {
            $cogsSubTotals->put('Layanan (' . $subType . ')', $items->sum('cogs'));
        }

        // 4. General Expenses
        $pengeluaranUmumItems = PengeluaranUmum::whereBetween('tanggal_pengeluaran', [$startDate, $endDate])->get();
        $totalPengeluaranUmum = $pengeluaranUmumItems->sum('jumlah_pengeluaran');
        $generalExpenseSubTotals = $pengeluaranUmumItems->groupBy('jenis_pengeluaran')->map(fn($group) => $group->sum('jumlah_pengeluaran'));

        // 5. General Income
        $pemasukanUmumItems = PemasukanUmum::whereBetween('tanggal_pemasukan', [$startDate, $endDate])->get();
        $totalPemasukanUmum = $pemasukanUmumItems->sum('jumlah_pemasukan');
        $generalIncomeSubTotals = $pemasukanUmumItems->groupBy('jenis_pemasukan')->map(fn($group) => $group->sum('jumlah_pemasukan'));

        $netProfitBeforeTax = ($grossProfit + $totalPemasukanUmum) - $totalPengeluaranUmum;

        return view('pages.rugi-laba-penjualan.index', [
            'title' => 'Laporan Rugi Laba Penjualan',
            'reportData' => $sortedData,
            'totalRevenue' => $totalRevenue,
            'totalCOGS' => $totalCOGS,
            'grossProfit' => $grossProfit,
            'revenueSubTotals' => $revenueSubTotals,
            'cogsSubTotals' => $cogsSubTotals,
            'totalPengeluaranUmum' => $totalPengeluaranUmum,
            'generalExpenseSubTotals' => $generalExpenseSubTotals,
            'totalPemasukanUmum' => $totalPemasukanUmum,
            'generalIncomeSubTotals' => $generalIncomeSubTotals,
            'netProfitBeforeTax' => $netProfitBeforeTax,
            'currentPeriod' => $period,
            'periodLabel' => $periodLabel,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}
