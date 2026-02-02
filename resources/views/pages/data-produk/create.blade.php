@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Data Produk" :breadcrumbs="[
    ['label' => 'Data Produk', 'url' => route('data-produk')],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <form action="{{ route('data-produk.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    
                    <!-- Kode Produk (Readonly) -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Produk</label>
                        <input type="text" name="kode_produk" value="{{ $kodeProduk }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" />
                    </div>

                    <!-- Nama Produk -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Produk</label>
                        <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Nama Produk" required />
                        @error('nama_produk') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Standar Stok -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Standar Stok</label>
                        <input type="number" name="standar_stok" value="{{ old('standar_stok') }}" min="0" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" required />
                        @error('standar_stok') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Aktual Stok -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Aktual Stok</label>
                        <input type="number" name="aktual_stok" value="{{ old('aktual_stok') }}" min="0" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" required />
                        @error('aktual_stok') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Satuan Unit -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Satuan Unit</label>
                        <select name="satuan_unit" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
                            <option value="">Pilih Satuan Unit</option>
                            <option value="Pcs" {{ old('satuan_unit') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                            <option value="Set" {{ old('satuan_unit') == 'Set' ? 'selected' : '' }}>Set</option>
                            <option value="Pack" {{ old('satuan_unit') == 'Pack' ? 'selected' : '' }}>Pack</option>
                            <option value="Dus" {{ old('satuan_unit') == 'Dus' ? 'selected' : '' }}>Dus</option>
                            <option value="Lot" {{ old('satuan_unit') == 'Lot' ? 'selected' : '' }}>Lot</option>
                            <option value="Pax" {{ old('satuan_unit') == 'Pax' ? 'selected' : '' }}>Pax</option>
                            <option value="Room" {{ old('satuan_unit') == 'Room' ? 'selected' : '' }}>Room</option>
                            <option value="Seat" {{ old('satuan_unit') == 'Seat' ? 'selected' : '' }}>Seat</option>
                        </select>
                         @error('satuan_unit') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Harga Beli -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Beli</label>
                        <input type="number" name="harga_beli" value="{{ old('harga_beli') }}" min="0" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" required />
                        @error('harga_beli') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Harga Jual -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Jual</label>
                        <input type="number" name="harga_jual" value="{{ old('harga_jual') }}" min="0" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" required />
                        @error('harga_jual') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan Produk -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Produk</label>
                        <textarea name="catatan_produk" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Catatan (Opsional)">{{ old('catatan_produk') }}</textarea>
                        @error('catatan_produk') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('data-produk') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
