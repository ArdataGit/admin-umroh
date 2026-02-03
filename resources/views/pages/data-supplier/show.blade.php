@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Data Supplier" :breadcrumbs="[
    ['label' => 'Data Supplier', 'url' => route('data-supplier')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-800">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Informasi Supplier</h3>
                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                    {{ $supplier->kode_supplier }}
                </span>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Nama Supplier -->
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Supplier</p>
                    <p class="mt-1 text-base font-medium text-gray-800 dark:text-white">{{ $supplier->nama_supplier }}</p>
                </div>

                <!-- Kontak Supplier -->
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kontak Supplier</p>
                    <p class="mt-1 text-base font-medium text-gray-800 dark:text-white">{{ $supplier->kontak_supplier ?? '-' }}</p>
                </div>

                <!-- Email Supplier -->
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Supplier</p>
                    <p class="mt-1 text-base font-medium text-gray-800 dark:text-white">{{ $supplier->email_supplier ?? '-' }}</p>
                </div>

                <!-- Kota/Provinsi -->
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kota/Provinsi</p>
                    <p class="mt-1 text-base font-medium text-gray-800 dark:text-white">{{ $supplier->kota_provinsi ?? '-' }}</p>
                </div>

                <!-- Alamat Supplier -->
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Supplier</p>
                    <p class="mt-1 text-base font-medium text-gray-800 dark:text-white">{{ $supplier->alamat_supplier ?? '-' }}</p>
                </div>

                <!-- Catatan Supplier -->
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan Supplier</p>
                    <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $supplier->catatan_supplier ?? '-' }}</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6 dark:border-gray-800">
                <form action="{{ route('data-supplier.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-100 dark:border-red-900/30 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30">
                        Hapus
                    </button>
                </form>
                <a href="{{ route('data-supplier.edit', $supplier->id) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">
                    Edit Data
                </a>
                 <a href="{{ route('data-supplier') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
