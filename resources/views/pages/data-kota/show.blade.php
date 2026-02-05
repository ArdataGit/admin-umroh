@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Kota" :breadcrumbs="[
    ['label' => 'Data Kota', 'url' => route('data-kota.index')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-800 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Informasi Kota</h3>
        <div class="flex gap-2">
            <a href="{{ route('data-kota.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Kembali</a>
            <a href="{{ route('data-kota.edit', $kota->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div class="rounded-lg border border-gray-100 p-4 dark:border-gray-800">
            <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Kode Kota</span>
            <span class="mt-1 block text-lg font-medium text-gray-800 dark:text-white">{{ $kota->kode_kota }}</span>
        </div>
        <div class="rounded-lg border border-gray-100 p-4 dark:border-gray-800">
            <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Nama Kota</span>
            <span class="mt-1 block text-lg font-medium text-gray-800 dark:text-white">{{ $kota->nama_kota }}</span>
        </div>
        <div class="rounded-lg border border-gray-100 p-4 dark:border-gray-800">
            <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Terdaftar Pada</span>
            <span class="mt-1 block text-sm text-gray-800 dark:text-white">{{ $kota->created_at->format('d F Y, H:i') }}</span>
        </div>
        <div class="rounded-lg border border-gray-100 p-4 dark:border-gray-800">
            <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Pembaruan Terakhir</span>
            <span class="mt-1 block text-sm text-gray-800 dark:text-white">{{ $kota->updated_at->format('d F Y, H:i') }}</span>
        </div>
    </div>
</div>
@endsection
