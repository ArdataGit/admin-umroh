@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Riwayat Pembayaran Tiket" :breadcrumbs="[
    ['label' => 'Pembayaran Tiket', 'url' => route('pembayaran-tiket.index')],
    ['label' => 'History', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <!-- Transaction Details -->
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Informasi Transaksi</h3>
            </div>
            <div class="p-6">
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Kode Transaksi</p>
                        <h4 class="text-base font-medium text-gray-800 dark:text-white">{{ $transaksi->kode_transaksi }}</h4>
                    </div>
                     <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Tanggal Transaksi</p>
                        <h4 class="text-base font-medium text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d F Y') }}</h4>
                    </div>
                     <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Nama Mitra</p>
                        <h4 class="text-base font-medium text-gray-800 dark:text-white">{{ $transaksi->pelanggan->nama_pelanggan ?? '-' }}</h4>
                    </div>
                     <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Tagihan</p>
                        <h4 class="text-base font-medium text-gray-800 dark:text-white">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</h4>
                    </div>
                     <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Terbayar</p>
                        <h4 class="text-base font-medium text-green-600">Rp {{ number_format($pembayarans->sum('jumlah_pembayaran'), 0, ',', '.') }}</h4>
                    </div>
                     <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Sisa Pembayaran</p>
                        @php
                            $sisa = $transaksi->total_transaksi - $pembayarans->sum('jumlah_pembayaran');
                        @endphp
                        <h4 class="text-base font-medium {{ $sisa > 0 ? 'text-red-500' : 'text-gray-800 dark:text-white' }}">
                            Rp {{ number_format($sisa, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment List -->
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-800">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Riwayat Pembayaran</h4>
                <a href="{{ route('pembayaran-tiket.create-payment', $transaksi->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                    + Tambah Pembayaran
                </a>
            </div>
            
            <div class="overflow-x-auto">
                 <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800 text-left bg-gray-50 dark:bg-gray-800/50">
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Kode Pembayaran</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Metode</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Status</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Bukti</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($pembayarans as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white font-medium">{{ $payment->kode_transaksi }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->translatedFormat('d F Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 capitalize">{{ $payment->metode_pembayaran == 'cash' ? 'Cash' : $payment->metode_pembayaran }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white font-medium">Rp {{ number_format($payment->jumlah_pembayaran, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $payment->status_pembayaran == 'paid' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                      ($payment->status_pembayaran == 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                                    {{ ucfirst($payment->status_pembayaran) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($payment->bukti_pembayaran)
                                    <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $payment->catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada riwayat pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
