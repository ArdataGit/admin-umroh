@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Stock Opname" :breadcrumbs="[
    ['label' => 'Stock Opname', 'url' => route('stock-opname.index')],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <form action="{{ route('stock-opname.store') }}" method="POST">
        @csrf
        
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Form Stock Opname</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Adjustment</label>
                    <input type="text" name="kode_adjustment" value="{{ $kodeAdjustment }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Adjustment</label>
                    <input type="date" name="tanggal_adjustment" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Produk</label>
                    <select name="produk_id" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required>
                        <option value="">Pilih Produk</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                                {{ $produk->kode_produk }} - {{ $produk->nama_produk }} (Stok: {{ $produk->aktual_stok }})
                            </option>
                        @endforeach
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tipe Adjustment</label>
                    <select name="tipe_adjustment" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required>
                        <option value="penambahan" {{ old('tipe_adjustment') == 'penambahan' ? 'selected' : '' }}>Penambahan (+)</option>
                        <option value="pengurangan" {{ old('tipe_adjustment') == 'pengurangan' ? 'selected' : '' }}>Pengurangan (-)</option>
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Koreksi Stock (Qty)</label>
                    <input type="number" name="koreksi_stock" min="1" value="{{ old('koreksi_stock') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" placeholder="Contoh: 10" required />
                </div>
                 <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Stock</label>
                    <textarea name="catatan" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" placeholder="Opsional">{{ old('catatan') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('stock-opname.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Simpan Adjustment</button>
            </div>
        </div>
    </div>
</div>
@endsection
