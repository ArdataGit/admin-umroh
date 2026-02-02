@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Data Produk" :breadcrumbs="[
    ['label' => 'Data Produk', 'url' => route('data-produk')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Informasi Produk</h2>
        <div class="flex gap-2">
            <a href="{{ route('data-produk') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                Kembali
            </a>
            <a href="{{ route('data-produk.edit', $produk->id) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Kode Produk -->
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Produk</p>
            <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">{{ $produk->kode_produk }}</p>
        </div>

        <!-- Nama Produk -->
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Produk</p>
            <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">{{ $produk->nama_produk }}</p>
        </div>

        <!-- Standar Stok -->
        <div>
             <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Standar Stok</p>
             <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">{{ $produk->standar_stok }}</p>
        </div>

        <!-- Aktual Stok -->
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Aktual Stok</p>
            <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">{{ $produk->aktual_stok }}</p>
        </div>

        <!-- Satuan Unit -->
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Satuan Unit</p>
            <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">{{ $produk->satuan_unit }}</p>
        </div>
        
        <!-- Harga Beli -->
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga Beli</p>
            <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</p>
        </div>

        <!-- Harga Jual -->
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga Jual</p>
            <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</p>
        </div>

        <!-- Catatan Produk -->
        <div class="col-span-1 md:col-span-2">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan Produk</p>
            <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $produk->catatan_produk ?? '-' }}</p>
        </div>
    </div>
</div>
@endsection
