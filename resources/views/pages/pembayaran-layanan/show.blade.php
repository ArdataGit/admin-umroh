@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Riwayat Pembayaran Layanan" :breadcrumbs="[
    ['label' => 'Pembayaran Layanan', 'url' => route('pembayaran-layanan.index')],
    ['label' => 'Detail', 'url' => '#']
]" />

@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/20 dark:text-green-400">
    <div class="flex items-center gap-2">
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

<div class="space-y-6">
    <!-- Header Info -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
            <div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $transaksi->pelanggan->nama_pelanggan ?? 'Umum' }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Kode Transaksi: {{ $transaksi->kode_transaksi }} | 
                    Tanggal: {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}
                </p>
                <div class="mt-2">
                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium 
                        @if($transaksi->status_transaksi == 'completed') bg-green-100 text-green-700
                        @elseif($transaksi->status_transaksi == 'process') bg-blue-100 text-blue-700
                        @else bg-red-100 text-red-700 @endif uppercase">
                        {{ $transaksi->status_transaksi }}
                    </span>
                </div>
            </div>
            <div class="flex flex-col items-end">
                 <div class="text-sm text-gray-500">Total Transaksi</div>
                 <div class="text-lg font-bold text-gray-800 dark:text-white">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</div>
                 
                 @php
                     $totalBayar = $transaksi->pembayaranLayanans->where('status_pembayaran', '!=', 'failed')->sum('jumlah_pembayaran');
                     $sisa = $transaksi->total_transaksi - $totalBayar;
                 @endphp

                 <div class="text-sm {{ $sisa > 0 ? 'text-red-600' : 'text-green-600' }} font-medium">
                    Sisa: Rp {{ number_format($sisa, 0, ',', '.') }}
                 </div>
            </div>
        </div>
    </div>

    <!-- Payment List -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-800">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Riwayat Pembayaran</h4>
            <a href="{{ route('pembayaran-layanan.create-payment', $transaksi->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                + Tambah Pembayaran
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                         <th class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">Tanggal</th>
                         <th class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">Kode Pembayaran</th>
                         <th class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">Metode</th>
                         <th class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">Jumlah</th>
                         <th class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">Status</th>
                         <th class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($pembayarans as $pembayaran)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-6 py-4 text-gray-800 dark:text-white">
                            {{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-blue-600 font-medium">
                            {{ $pembayaran->kode_transaksi }}
                        </td>
                        <td class="px-6 py-4 text-gray-800 dark:text-white capitalize">
                            {{ $pembayaran->metode_pembayaran }}
                        </td>
                         <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">
                            Rp {{ number_format($pembayaran->jumlah_pembayaran, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium 
                                @if($pembayaran->status_pembayaran == 'paid') bg-green-100 text-green-700
                                @elseif($pembayaran->status_pembayaran == 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif capitalize">
                                {{ $pembayaran->status_pembayaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                            {{ $pembayaran->catatan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
