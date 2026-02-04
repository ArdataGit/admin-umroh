@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Pembayaran Tiket" :breadcrumbs="[
    ['label' => 'Pembayaran Tiket', 'url' => route('pembayaran-tiket.index')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Detail Pembayaran - {{ $pembayaran->kode_transaksi }}</h3>
                    <p class="text-sm text-gray-500">Transaksi Tiket: {{ $pembayaran->transaksiTiket->kode_transaksi }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('pembayaran-tiket.edit', $pembayaran->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Edit</a>
                    <a href="{{ route('pembayaran-tiket.show', $pembayaran->transaksi_tiket_id) }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Kembali</a>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Pelanggan:</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $pembayaran->transaksiTiket->pelanggan->nama_pelanggan ?? 'Umum' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Tanggal Pembayaran:</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ date('d F Y', strtotime($pembayaran->tanggal_pembayaran)) }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Jumlah Pembayaran:</span>
                            <span class="text-lg font-bold text-blue-600">Rp {{ number_format($pembayaran->jumlah_pembayaran, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Metode Pembayaran:</span>
                            <span class="font-medium text-gray-800 dark:text-white capitalize">{{ $pembayaran->metode_pembayaran }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                {{ $pembayaran->status_pembayaran }}
                            </span>
                        </div>
                         <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Kode Referensi:</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $pembayaran->kode_referensi ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Catatan:</h4>
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg text-gray-700 dark:text-gray-300 italic">
                            {{ $pembayaran->catatan ?? 'Tidak ada catatan.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
