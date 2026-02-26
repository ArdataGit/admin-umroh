<?php
namespace App\Http\Controllers;

use App\Models\TransaksiTiketDetail;
use App\Models\Maskapai;
use Illuminate\Http\Request;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistikController extends Controller
{
    public function penjualanMaskapai(Request $request)
    {
        $day = $request->get('day');
        $month = $request->get('month');
        $year = $request->get('year');
        $range = $request->get('range', 'all');

        // Get sales nominal grouped by maskapai only
        $query = TransaksiTiketDetail::select(
                'maskapais.nama_maskapai',
                DB::raw('SUM(transaksi_tiket_details.total_harga) as total_nominal')
            )
            ->join('transaksi_tikets', 'transaksi_tiket_details.transaksi_tiket_id', '=', 'transaksi_tikets.id')
            ->join('tickets', 'transaksi_tiket_details.ticket_id', '=', 'tickets.id')
            ->join('maskapais', 'tickets.maskapai_id', '=', 'maskapais.id')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('pembayaran_tikets')
                      ->whereColumn('pembayaran_tikets.transaksi_tiket_id', 'transaksi_tikets.id');
            });

        // Apply Range Filter
        if ($range === 'today') {
            $query->whereDate('transaksi_tikets.tanggal_transaksi', Carbon::today());
        } elseif ($range === 'month') {
            $query->whereMonth('transaksi_tikets.tanggal_transaksi', Carbon::now()->month)
                  ->whereYear('transaksi_tikets.tanggal_transaksi', Carbon::now()->year);
        } elseif ($range === 'year') {
            $query->whereYear('transaksi_tikets.tanggal_transaksi', Carbon::now()->year);
        }

        // Apply Granular Filters
        if ($day) {
            $query->whereDay('transaksi_tikets.tanggal_transaksi', $day);
        }
        if ($month) {
            $query->whereMonth('transaksi_tikets.tanggal_transaksi', $month);
        }
        if ($year) {
            $query->whereYear('transaksi_tikets.tanggal_transaksi', $year);
        }

        $salesData = $query->groupBy('maskapais.nama_maskapai')
            ->orderBy('total_nominal', 'desc')
            ->get();

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Statistik Penjualan Maskapai',
            'action' => 'View',
            'keterangan' => 'Melihat statistik penjualan maskapai'
        ]);

        return view('pages.statistik.penjualan-maskapai', [
            'title' => 'Statistik Penjualan Maskapai',
            'salesData' => $salesData,
            'filters' => [
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'range' => $range
            ]
        ]);
    }

    public function penjualanPelanggan(Request $request)
    {
        $day = $request->get('day');
        $month = $request->get('month');
        $year = $request->get('year');
        $range = $request->get('range', 'all');

        // Get sales nominal grouped by pelanggan only
        $query = TransaksiTiketDetail::select(
                'pelanggans.nama_pelanggan',
                DB::raw('SUM(transaksi_tiket_details.total_harga) as total_nominal')
            )
            ->join('transaksi_tikets', 'transaksi_tiket_details.transaksi_tiket_id', '=', 'transaksi_tikets.id')
            ->join('pelanggans', 'transaksi_tikets.pelanggan_id', '=', 'pelanggans.id')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('pembayaran_tikets')
                      ->whereColumn('pembayaran_tikets.transaksi_tiket_id', 'transaksi_tikets.id');
            });

        // Apply Range Filter
        if ($range === 'today') {
            $query->whereDate('transaksi_tikets.tanggal_transaksi', Carbon::today());
        } elseif ($range === 'month') {
            $query->whereMonth('transaksi_tikets.tanggal_transaksi', Carbon::now()->month)
                  ->whereYear('transaksi_tikets.tanggal_transaksi', Carbon::now()->year);
        } elseif ($range === 'year') {
            $query->whereYear('transaksi_tikets.tanggal_transaksi', Carbon::now()->year);
        }

        // Apply Granular Filters
        if ($day) {
            $query->whereDay('transaksi_tikets.tanggal_transaksi', $day);
        }
        if ($month) {
            $query->whereMonth('transaksi_tikets.tanggal_transaksi', $month);
        }
        if ($year) {
            $query->whereYear('transaksi_tikets.tanggal_transaksi', $year);
        }

        $salesData = $query->groupBy('pelanggans.nama_pelanggan')
            ->orderBy('total_nominal', 'desc')
            ->get();

        $grandTotal = $salesData->sum('total_nominal');

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Statistik Penjualan Travel',
            'action' => 'View',
            'keterangan' => 'Melihat statistik penjualan travel'
        ]);

        return view('pages.statistik.penjualan-pelanggan', [
            'title' => 'Statistik Penjualan Pelanggan',
            'salesData' => $salesData,
            'grandTotal' => $grandTotal,
            'filters' => [
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'range' => $range
            ]
        ]);
    }

    public function laporanKeberangkatanUmroh(Request $request)
    {
        $day = $request->get('day');
        $month = $request->get('month');
        $year = $request->get('year');
        $range = $request->get('range', 'all');

        $query = \App\Models\KeberangkatanUmroh::select(
                'tanggal_keberangkatan',
                DB::raw('COUNT(*) as jumlah_keberangkatan')
            );

        // Apply Range Filter
        if ($range === 'today') {
            $query->whereDate('tanggal_keberangkatan', Carbon::today());
        } elseif ($range === 'month') {
            $query->whereMonth('tanggal_keberangkatan', Carbon::now()->month)
                  ->whereYear('tanggal_keberangkatan', Carbon::now()->year);
        } elseif ($range === 'year') {
            $query->whereYear('tanggal_keberangkatan', Carbon::now()->year);
        }

        // Apply Granular Filters
        if ($day) {
            $query->whereDay('tanggal_keberangkatan', $day);
        }
        if ($month) {
            $query->whereMonth('tanggal_keberangkatan', $month);
        }
        if ($year) {
            $query->whereYear('tanggal_keberangkatan', $year);
        }

        $departures = $query->groupBy('tanggal_keberangkatan')
            ->orderBy('tanggal_keberangkatan', 'desc')
            ->get();

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Laporan Keberangkatan Umroh',
            'action' => 'View',
            'keterangan' => 'Melihat laporan keberangkatan umroh'
        ]);

        return view('pages.statistik.laporan-keberangkatan-umroh', [
            'title' => 'Laporan Keberangkatan Umroh',
            'departures' => $departures,
            'filters' => [
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'range' => $range
            ]
        ]);
    }

    public function laporanKeberangkatanHaji(Request $request)
    {
        $day = $request->get('day');
        $month = $request->get('month');
        $year = $request->get('year');
        $range = $request->get('range', 'all');

        $query = \App\Models\KeberangkatanHaji::select(
                DB::raw('YEAR(tanggal_keberangkatan) as tahun'),
                DB::raw('COUNT(*) as jumlah_keberangkatan')
            );

        // Apply Range Filter
        if ($range === 'today') {
            $query->whereDate('tanggal_keberangkatan', Carbon::today());
        } elseif ($range === 'month') {
            $query->whereMonth('tanggal_keberangkatan', Carbon::now()->month)
                  ->whereYear('tanggal_keberangkatan', Carbon::now()->year);
        } elseif ($range === 'year') {
            $query->whereYear('tanggal_keberangkatan', Carbon::now()->year);
        }

        // Apply Granular Filters
        if ($day) {
            $query->whereDay('tanggal_keberangkatan', $day);
        }
        if ($month) {
            $query->whereMonth('tanggal_keberangkatan', $month);
        }
        if ($year) {
            $query->whereYear('tanggal_keberangkatan', $year);
        }

        $departures = $query->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->get();

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Laporan Keberangkatan Haji',
            'action' => 'View',
            'keterangan' => 'Melihat laporan keberangkatan haji'
        ]);

        return view('pages.statistik.laporan-keberangkatan-haji', [
            'title' => 'Laporan Keberangkatan Haji',
            'departures' => $departures,
            'filters' => [
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'range' => $range
            ]
        ]);
    }

    public function laporanPenjualanUmroh(Request $request)
    {
        $day = $request->get('day');
        $month = $request->get('month');
        $year = $request->get('year');
        $range = $request->get('range', 'all');

        $query = \App\Models\CustomerUmroh::select(
                'agents.nama_agent',
                DB::raw('COUNT(*) as total_penjualan')
            )
            ->join('agents', 'customer_umrohs.agent_id', '=', 'agents.id');

        // Apply Range Filter
        if ($range === 'today') {
            $query->whereDate('customer_umrohs.created_at', Carbon::today());
        } elseif ($range === 'month') {
            $query->whereMonth('customer_umrohs.created_at', Carbon::now()->month)
                  ->whereYear('customer_umrohs.created_at', Carbon::now()->year);
        } elseif ($range === 'year') {
            $query->whereYear('customer_umrohs.created_at', Carbon::now()->year);
        }

        // Apply Granular Filters
        if ($day) {
            $query->whereDay('customer_umrohs.created_at', $day);
        }
        if ($month) {
            $query->whereMonth('customer_umrohs.created_at', $month);
        }
        if ($year) {
            $query->whereYear('customer_umrohs.created_at', $year);
        }

        $salesData = $query->groupBy('agents.nama_agent')
            ->orderBy('total_penjualan', 'desc')
            ->get();

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Laporan Penjualan Umroh (Agen)',
            'action' => 'View',
            'keterangan' => 'Melihat laporan penjualan umroh berdasarkan agen'
        ]);

        return view('pages.statistik.laporan-penjualan-umroh', [
            'title' => 'Laporan Penjualan Umroh Berdasarkan Agen',
            'salesData' => $salesData,
            'filters' => [
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'range' => $range
            ]
        ]);
    }

    public function laporanPenjualanHaji(Request $request)
    {
        $day = $request->get('day');
        $month = $request->get('month');
        $year = $request->get('year');
        $range = $request->get('range', 'all');

        $query = \App\Models\CustomerHaji::select(
                'agents.nama_agent',
                DB::raw('COUNT(*) as total_penjualan')
            )
            ->join('agents', 'customer_hajis.agent_id', '=', 'agents.id');

        // Apply Range Filter
        if ($range === 'today') {
            $query->whereDate('customer_hajis.created_at', Carbon::today());
        } elseif ($range === 'month') {
            $query->whereMonth('customer_hajis.created_at', Carbon::now()->month)
                  ->whereYear('customer_hajis.created_at', Carbon::now()->year);
        } elseif ($range === 'year') {
            $query->whereYear('customer_hajis.created_at', Carbon::now()->year);
        }

        // Apply Granular Filters
        if ($day) {
            $query->whereDay('customer_hajis.created_at', $day);
        }
        if ($month) {
            $query->whereMonth('customer_hajis.created_at', $month);
        }
        if ($year) {
            $query->whereYear('customer_hajis.created_at', $year);
        }

        $salesData = $query->groupBy('agents.nama_agent')
            ->orderBy('total_penjualan', 'desc')
            ->get();

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Laporan Penjualan Haji (Agen)',
            'action' => 'View',
            'keterangan' => 'Melihat laporan penjualan haji berdasarkan agen'
        ]);

        return view('pages.statistik.laporan-penjualan-haji', [
            'title' => 'Laporan Penjualan Haji Berdasarkan Agen',
            'salesData' => $salesData,
            'filters' => [
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'range' => $range
            ]
        ]);
    }

    public function laporanProduk(Request $request)
    {
        $day = $request->get('day');
        $month = $request->get('month');
        $year = $request->get('year');
        $range = $request->get('range', 'all');

        // We need to link PengeluaranProduk -> Jamaah -> CustomerUmroh/Haji -> Agent
        // Since a Jamaah can have multiple registrations, we'll try to find the agent 
        // from their registrations. If they have both Umroh and Haji, we'll take the latest one.
        
        $query = \App\Models\PengeluaranProdukDetail::select(
                'produks.nama_produk',
                'agents.nama_agent',
                DB::raw('SUM(pengeluaran_produk_details.quantity) as total_quantity')
            )
            ->join('produks', 'pengeluaran_produk_details.produk_id', '=', 'produks.id')
            ->join('pengeluaran_produks', 'pengeluaran_produk_details.pengeluaran_produk_id', '=', 'pengeluaran_produks.id')
            ->join('jamaahs', 'pengeluaran_produks.jamaah_id', '=', 'jamaahs.id')
            // Link to agent via CustomerUmroh or CustomerHaji
            // Using a subquery to get an agent associated with the jamaah
            ->join(DB::raw('(
                SELECT jamaah_id, MAX(agent_id) as agent_id FROM (
                    SELECT jamaah_id, agent_id FROM customer_umrohs
                    UNION ALL
                    SELECT jamaah_id, agent_id FROM customer_hajis
                ) as all_registrations
                GROUP BY jamaah_id
            ) as jamaah_agents'), 'jamaah_agents.jamaah_id', '=', 'jamaahs.id')
            ->join('agents', 'jamaah_agents.agent_id', '=', 'agents.id');

        // Apply Range Filter
        if ($range === 'today') {
            $query->whereDate('pengeluaran_produks.tanggal_pengeluaran', Carbon::today());
        } elseif ($range === 'month') {
            $query->whereMonth('pengeluaran_produks.tanggal_pengeluaran', Carbon::now()->month)
                  ->whereYear('pengeluaran_produks.tanggal_pengeluaran', Carbon::now()->year);
        } elseif ($range === 'year') {
            $query->whereYear('pengeluaran_produks.tanggal_pengeluaran', Carbon::now()->year);
        }

        // Apply Granular Filters
        if ($day) {
            $query->whereDay('pengeluaran_produks.tanggal_pengeluaran', $day);
        }
        if ($month) {
            $query->whereMonth('pengeluaran_produks.tanggal_pengeluaran', $month);
        }
        if ($year) {
            $query->whereYear('pengeluaran_produks.tanggal_pengeluaran', $year);
        }

        $productData = $query->groupBy('produks.nama_produk', 'agents.nama_agent')
            ->orderBy('produks.nama_produk')
            ->orderBy('total_quantity', 'desc')
            ->get();

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Laporan Properti (Agen)',
            'action' => 'View',
            'keterangan' => 'Melihat laporan distribusi properti ke agen'
        ]);

        return view('pages.statistik.laporan-produk', [
            'title' => 'Laporan Distribusi Produk ke Agen',
            'productData' => $productData,
            'filters' => [
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'range' => $range
            ]
        ]);
    }
}
