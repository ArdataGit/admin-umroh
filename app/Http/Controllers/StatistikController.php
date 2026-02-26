<?php
namespace App\Http\Controllers;

use App\Models\TransaksiTiketDetail;
use App\Models\Maskapai;
use Illuminate\Http\Request;
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
}
