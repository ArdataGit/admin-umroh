@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Stock Opname" :breadcrumbs="[
    ['label' => 'Stock Opname', 'url' => route('stock-opname.index')],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <form action="{{ route('stock-opname.update', $stockOpname->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Edit Stock Opname</h3>
            
            <div class="mb-6 rounded-lg bg-yellow-50 p-4 text-sm text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                <span class="font-bold">Perhatian:</span> Mengedit data ini akan membatalkan perubahan stock lama dan menerapkan perubahan baru.
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Adjustment</label>
                    <input type="text" value="{{ $stockOpname->kode_adjustment }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 cursor-not-allowed" />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Adjustment</label>
                    <input type="date" name="tanggal_adjustment" value="{{ $stockOpname->tanggal_adjustment }}" min="1900-01-01" max="9999-12-31" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Produk</label>
                    <input type="text" value="{{ $stockOpname->produk->nama_produk }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 cursor-not-allowed" />
                     <p class="mt-1 text-xs text-gray-500">Produk tidak dapat diubah saat edit.</p>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tipe Adjustment</label>
                    <select name="tipe_adjustment" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required>
                        <option value="penambahan" {{ $stockOpname->tipe_adjustment == 'penambahan' ? 'selected' : '' }}>Penambahan (+)</option>
                        <option value="pengurangan" {{ $stockOpname->tipe_adjustment == 'pengurangan' ? 'selected' : '' }}>Pengurangan (-)</option>
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Koreksi Stock (Qty)</label>
                    <input type="number" name="koreksi_stock" min="1" value="{{ $stockOpname->koreksi_stock }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" placeholder="Contoh: 10" required />
                </div>
                 <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Stock</label>
                    <textarea name="catatan" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" placeholder="Opsional">{{ $stockOpname->catatan }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('stock-opname.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>
@endsection
