@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Pembayaran Umroh" :breadcrumbs="[
    ['label' => 'Pembayaran Umroh', 'url' => route('pembayaran-umroh.index')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="col-span-12">
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-800">
            <div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                    #{{ $pembayaran->kode_transaksi }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tanggal: {{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d M Y') }}
                </p>
            </div>
            <div>
                 <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium
                    @if($pembayaran->status_pembayaran === 'paid' || $pembayaran->status_pembayaran === 'checked') bg-green-100 text-green-700
                    @elseif($pembayaran->status_pembayaran === 'pending') bg-yellow-100 text-yellow-700
                    @else bg-red-100 text-red-700 @endif">
                    {{ ucfirst($pembayaran->status_pembayaran) }}
                </span>
            </div>
        </div>

        <!-- Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Info Jamaah -->
            <div class="space-y-4">
                <h4 class="font-medium text-gray-900 dark:text-white border-l-4 border-blue-500 pl-3">Informasi Jamaah</h4>
                <div class="grid grid-cols-1 gap-2 text-sm">
                    <div class="grid grid-cols-3">
                        <span class="text-gray-500">Nama Jamaah</span>
                        <span class="col-span-2 font-medium text-gray-800 dark:text-gray-200">: {{ $pembayaran->customerUmroh->jamaah->nama_jamaah }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="text-gray-500">Paket</span>
                        <span class="col-span-2 font-medium text-gray-800 dark:text-gray-200">: {{ $pembayaran->customerUmroh->keberangkatanUmroh->paketUmroh->nama_paket ?? '-' }}</span>
                    </div>
                     <div class="grid grid-cols-3">
                        <span class="text-gray-500">Keberangkatan</span>
                        <span class="col-span-2 font-medium text-gray-800 dark:text-gray-200">: {{ $pembayaran->customerUmroh->keberangkatanUmroh->nama_keberangkatan }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="text-gray-500">Total Tagihan</span>
                        <span class="col-span-2 font-medium text-gray-800 dark:text-gray-200">: Rp {{ number_format($pembayaran->customerUmroh->total_tagihan, 0, ',', '.') }}</span>
                    </div>
                     <div class="grid grid-cols-3">
                        <span class="text-gray-500">Sisa Tagihan</span>
                        <span class="col-span-2 font-medium text-red-600">: Rp {{ number_format($pembayaran->customerUmroh->sisa_tagihan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Info Pembayaran -->
             <div class="space-y-4">
                <h4 class="font-medium text-gray-900 dark:text-white border-l-4 border-green-500 pl-3">Rincian Transaksi</h4>
                <div class="grid grid-cols-1 gap-2 text-sm">
                    <div class="grid grid-cols-3">
                        <span class="text-gray-500">Jumlah Bayar</span>
                        <span class="col-span-2 text-lg font-bold text-green-600">: Rp {{ number_format($pembayaran->jumlah_pembayaran, 0, ',', '.') }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="text-gray-500">Metode</span>
                        <span class="col-span-2 font-medium text-gray-800 dark:text-gray-200">: {{ strtoupper($pembayaran->metode_pembayaran) }}</span>
                    </div>
                     <div class="grid grid-cols-3">
                        <span class="text-gray-500">Status</span>
                        <span class="col-span-2 font-medium text-gray-800 dark:text-gray-200">: {{ ucfirst($pembayaran->status_pembayaran) }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="text-gray-500">Kode Referensi</span>
                        <span class="col-span-2 font-medium text-gray-800 dark:text-gray-200">: {{ $pembayaran->kode_referensi ?? '-' }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="text-gray-500">Catatan</span>
                        <span class="col-span-2 font-medium text-gray-800 dark:text-gray-200">: {{ $pembayaran->catatan ?? '-' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer Actions -->
        <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6 dark:border-gray-800">
            <a href="{{ route('pembayaran-umroh.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                Kembali
            </a>
            <a href="{{ route('pembayaran-umroh.edit', $pembayaran->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Edit Pembayaran
            </a>
        </div>

    </div>
</div>
@endsection
