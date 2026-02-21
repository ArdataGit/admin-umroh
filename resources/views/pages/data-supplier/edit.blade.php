@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Data Supplier" :breadcrumbs="[
    ['label' => 'Data Supplier', 'url' => route('data-supplier')],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <form action="{{ route('data-supplier.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    
                    <!-- Kode Supplier (Readonly) -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Supplier</label>
                        <input type="text" name="kode_supplier" value="{{ $supplier->kode_supplier }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" />
                    </div>

                    <!-- Nama Supplier -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Supplier</label>
                        <input type="text" name="nama_supplier" value="{{ old('nama_supplier', $supplier->nama_supplier) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Nama Supplier" required />
                        @error('nama_supplier') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kontak Supplier -->
                    <div x-data="{ kontak: '{{ old('kontak_supplier', $supplier->kontak_supplier) }}' }">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kontak Supplier</label>
                        <input type="text" name="kontak_supplier" x-model="kontak" @input="kontak = $el.value.replace(/\D/g, '')" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Kontak Supplier" />
                        @error('kontak_supplier') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email Supplier -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Email Supplier</label>
                        <input type="email" name="email_supplier" value="{{ old('email_supplier', $supplier->email_supplier) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Email Supplier" />
                        @error('email_supplier') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kota/Provinsi -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kota/Provinsi</label>
                        <input type="text" name="kota_provinsi" value="{{ old('kota_provinsi', $supplier->kota_provinsi) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Kota/Provinsi" />
                        @error('kota_provinsi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alamat Supplier -->
                     <div class="col-span-1 md:col-span-1">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat Supplier</label>
                        <textarea name="alamat_supplier" rows="1" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Alamat">{{ old('alamat_supplier', $supplier->alamat_supplier) }}</textarea>
                        @error('alamat_supplier') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan Supplier -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Supplier</label>
                        <textarea name="catatan_supplier" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Catatan (Opsional)">{{ old('catatan_supplier', $supplier->catatan_supplier) }}</textarea>
                        @error('catatan_supplier') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('data-supplier') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
