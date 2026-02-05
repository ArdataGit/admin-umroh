@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Data Kota" :breadcrumbs="[
    ['label' => 'Data Kota', 'url' => route('data-kota.index')],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
    <form action="{{ route('data-kota.update', $kota->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Kota <span class="text-red-500">*</span></label>
                <input type="text" name="kode_kota" value="{{ old('kode_kota', $kota->kode_kota) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm @error('kode_kota') border-red-500 @enderror" required />
                @error('kode_kota') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Kota <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kota" value="{{ old('nama_kota', $kota->nama_kota) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm @error('nama_kota') border-red-500 @enderror" required />
                @error('nama_kota') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-8 flex items-center justify-end gap-3">
            <a href="{{ route('data-kota.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Batal</a>
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
