@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Stock Opname" :breadcrumbs="[
    ['label' => 'Stock Opname', 'url' => route('stock-opname.index')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="mb-6 flex items-center justify-between">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Detail Transaksi Stock Opname</h3>
        <span class="{{ $stockOpname->tipe_adjustment == 'penambahan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full px-3 py-1 text-sm font-medium uppercase">
            {{ $stockOpname->tipe_adjustment }}
        </span>
    </div>

    <div class="grid grid-cols-1 gap-y-6 gap-x-4 md:grid-cols-2">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Adjustment</p>
            <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $stockOpname->kode_adjustment }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Adjustment</p>
            <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($stockOpname->tanggal_adjustment)->translatedFormat('d F Y') }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk</p>
            <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $stockOpname->produk->nama_produk }} <span class="text-sm text-gray-500">({{ $stockOpname->produk->kode_produk }})</span></p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Koreksi Stock</p>
            <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $stockOpname->koreksi_stock }}</p>
        </div>
        
        <div class="border-t border-gray-100 dark:border-gray-700 col-span-1 md:col-span-2 pt-4 mt-2">
            <h4 class="mb-4 text-sm font-semibold uppercase text-gray-500">Perubahan Stock</h4>
            <div class="flex items-center gap-8">
                <div>
                    <p class="text-xs text-gray-500">Stock Awal</p>
                    <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $stockOpname->stok_awal }}</p>
                </div>
                <div class="text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Stock Akhir</p>
                    <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $stockOpname->stok_akhir }}</p>
                </div>
            </div>
        </div>

        <div class="col-span-1 md:col-span-2">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</p>
            <div class="mt-1 rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                <p class="text-gray-700 dark:text-gray-300">{{ $stockOpname->catatan ?: '-' }}</p>
            </div>
        </div>
        
         <div class="col-span-1 md:col-span-2 flex gap-4 mt-2">
             <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</p>
                <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ $stockOpname->user_id }}</p>
             </div>
             <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Approval</p>
                <p class="text-sm font-semibold text-blue-600">{{ $stockOpname->status_approval }}</p>
             </div>
         </div>
    </div>

    <div class="mt-8 flex justify-end gap-3">
        <a href="{{ route('stock-opname.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Kembali</a>
        <a href="{{ route('stock-opname.edit', $stockOpname->id) }}" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Edit Data</a>
    </div>
</div>
@endsection
