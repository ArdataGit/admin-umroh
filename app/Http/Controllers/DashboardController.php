<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Transaction Counts
        $totalTransaksiHaji = \App\Models\PembayaranHaji::count();
        $totalTransaksiUmroh = \App\Models\PembayaranUmroh::count();

        // 2. Jamaah Counts
        $totalJamaahHaji = \App\Models\CustomerHaji::count();
        $totalJamaahUmroh = \App\Models\CustomerUmroh::count();

        // 3. Payment Status (Sudah Bayar / Sisa Tagihan)
        // Sudah Pembayaran Haji/Umroh: Count of Customer where sisa_tagihan == 0
        $sudahBayarHaji = \App\Models\CustomerHaji::where('sisa_tagihan', '<=', 0)->count();
        $sudahBayarUmroh = \App\Models\CustomerUmroh::where('sisa_tagihan', '<=', 0)->count();
        
        // Sisa Tagihan Haji/Umroh: Count of Customer where sisa_tagihan > 0
        $sisaTagihanHaji = \App\Models\CustomerHaji::where('sisa_tagihan', '>', 0)->count();
        $sisaTagihanUmroh = \App\Models\CustomerUmroh::where('sisa_tagihan', '>', 0)->count();

        // 4. Tabungan Data (Money)
        $totalSaldoHaji = \App\Models\TabunganHaji::sum('setoran_tabungan');
        // If setoran_tabungan is not reliable, we should sum transactions:
        // $totalSaldoHaji = \App\Models\TransaksiTabunganHaji::where('jenis_transaksi', 'setoran')->sum('nominal') - ...;
        // But Tabungan model usually holds the cached balance. Let's use that.
        
        $totalSaldoUmroh = \App\Models\TabunganUmroh::sum('setoran_tabungan');

        // 5. Active Accounts
        $rekeningAktifHaji = \App\Models\TabunganHaji::where('status_tabungan', 'active')->count();
        $rekeningAktifUmroh = \App\Models\TabunganUmroh::where('status_tabungan', 'active')->count();

        // 6. Tables
        $keberangkatanUmroh = \App\Models\KeberangkatanUmroh::with(['paketUmroh.maskapai', 'customerUmrohs'])
                                ->where('tanggal_keberangkatan', '>=', now())
                                ->orderBy('tanggal_keberangkatan', 'asc')
                                ->limit(5)
                                ->get()
                                ->map(function($item) {
                                    $filled = $item->customerUmrohs->sum('jumlah_jamaah');
                                    $remaining = $item->kuota_jamaah - $filled;
                                    $airline = ($item->paketUmroh && $item->paketUmroh->maskapai) ? $item->paketUmroh->maskapai->nama_maskapai . ' / ' . $item->paketUmroh->maskapai->kode_maskapai : '-';
                                    
                                    return [
                                        'id' => $item->id,
                                        'code' => $item->kode_keberangkatan,
                                        'name' => $item->nama_keberangkatan,
                                        'date' => \Carbon\Carbon::parse($item->tanggal_keberangkatan)->translatedFormat('d F Y'),
                                        'location' => ($item->paketUmroh && $item->paketUmroh->lokasi_keberangkatan) ? $item->paketUmroh->lokasi_keberangkatan : '-',
                                        'airline' => $airline,
                                        'duration' => $item->jumlah_hari,
                                        'quota' => $item->kuota_jamaah,
                                        'filled' => $filled,
                                        'remaining' => $remaining,
                                        'status' => $item->status_keberangkatan
                                    ];
                                });

        // Keberangkatan Haji
        $keberangkatanHaji = [];
        if (class_exists(\App\Models\KeberangkatanHaji::class)) {
             $keberangkatanHaji = \App\Models\KeberangkatanHaji::with(['paketHaji', 'customerHajis'])
                                ->where('tanggal_keberangkatan', '>=', now())
                                ->orderBy('tanggal_keberangkatan', 'asc')
                                ->limit(5)
                                ->get()
                                ->map(function($item) {
                                    // Assuming similar structure for Haji
                                    $filled = $item->customerHajis ? $item->customerHajis->sum('jumlah_jamaah') : 0;
                                    $remaining = $item->kuota_jamaah - $filled;
                                    
                                    return [
                                        'id' => $item->id,
                                        'code' => $item->kode_keberangkatan,
                                        'name' => $item->nama_keberangkatan,
                                        'date' => \Carbon\Carbon::parse($item->tanggal_keberangkatan)->translatedFormat('d F Y'),
                                        'location' => $item->paketHaji->lokasi_keberangkatan ?? '-',
                                        'airline' => '-', // Add logic if needed
                                        'duration' => $item->jumlah_hari,
                                        'quota' => $item->kuota_jamaah,
                                        'filled' => $filled,
                                        'remaining' => $remaining,
                                        'status' => $item->status_keberangkatan ?? 'Aktif'
                                    ];
                                });           
        }

        // 7. Inventory (Low Stock)
        $lowStockProducts = \App\Models\Produk::whereRaw('aktual_stok < standar_stok')
                                ->limit(5)
                                ->get()
                                ->map(function($item) {
                                    $status = 'Aman';
                                    if ($item->aktual_stok == 0) $status = 'Habis';
                                    elseif ($item->aktual_stok < $item->standar_stok) $status = 'Menipis';

                                    return [
                                        'id' => $item->id,
                                        'code' => $item->kode_produk,
                                        'name' => $item->nama_produk,
                                        'purchasePrice' => $item->harga_beli,
                                        'standardStock' => $item->standar_stok,
                                        'actualStock' => $item->aktual_stok,
                                        'status' => $status
                                    ];
                                });

        return view('pages.dashboard.dashboard', [
            'totalTransaksiHaji' => $totalTransaksiHaji,
            'totalTransaksiUmroh' => $totalTransaksiUmroh,
            'totalJamaahHaji' => $totalJamaahHaji,
            'totalJamaahUmroh' => $totalJamaahUmroh,
            'sudahBayarHaji' => $sudahBayarHaji,
            'sudahBayarUmroh' => $sudahBayarUmroh,
            'sisaTagihanHaji' => $sisaTagihanHaji,
            'sisaTagihanUmroh' => $sisaTagihanUmroh,
            'totalSaldoHaji' => $totalSaldoHaji,
            'totalSaldoUmroh' => $totalSaldoUmroh,
            'rekeningAktifHaji' => $rekeningAktifHaji,
            'rekeningAktifUmroh' => $rekeningAktifUmroh,
            'keberangkatanUmroh' => $keberangkatanUmroh,
            'keberangkatanHaji' => $keberangkatanHaji,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}
