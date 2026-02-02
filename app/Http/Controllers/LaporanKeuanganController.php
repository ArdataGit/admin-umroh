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
    public function index(Request $request)
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
                'raw_date' => $item->tanggal_pembayaran
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
                'raw_date' => $item->tanggal_pembayaran
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
                'raw_date' => $item->tanggal_pemasukan
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
                'raw_date' => $item->tanggal_pembayaran
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
                'raw_date' => $item->tanggal_pembayaran
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
                'raw_date' => $item->tanggal_pengeluaran
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
                'raw_date' => $item->tanggal_pengeluaran
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
                'raw_date' => $item->tanggal_pengeluaran
            ]);
        }

        // 9. Pengeluaran Produk (Expense)
        $pengeluaranProduk = PengeluaranProduk::with('jamaah')->get();
        foreach ($pengeluaranProduk as $item) {
            $transactions->push([
                'date' => $item->tanggal_pengeluaran,
                'source' => 'Pengeluaran Produk',
                'invoice' => $item->kode_pengeluaran,
                'description' => $item->catatan ?? 'Pengeluaran Produk',
                'income' => 0,
                'expense' => $item->total_nominal, // Asumsi total_nominal adalah pengeluaran
                'raw_date' => $item->tanggal_pengeluaran
            ]);
        }

        // SORT BY DATE ASC
        $sortedTransactions = $transactions->sortBy('raw_date')->values();

        // CALCULATE RUNNING BALANCE
        $runningBalance = 0;
        $finalData = $sortedTransactions->map(function ($item) use (&$runningBalance) {
            $runningBalance += $item['income'];
            $runningBalance -= $item['expense'];
            $item['saldo'] = $runningBalance;
            return (object) $item; // Convert to object for easier blade access
        });

        // Filter by Date Range if requested (Optional - basic implementation)
        // if ($request->has('start_date') && $request->has('end_date')) { ... }

        return view('pages.laporan-keuangan.index', [
            'title' => 'Laporan Keuangan',
            'transactions' => $finalData,
            'total_income' => $sortedTransactions->sum('income'),
            'total_expense' => $sortedTransactions->sum('expense'),
            'final_balance' => $runningBalance
        ]);
    }
}
