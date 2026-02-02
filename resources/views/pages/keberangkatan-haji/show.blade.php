@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Keberangkatan Haji" :breadcrumbs="[
    ['label' => 'Keberangkatan Haji', 'url' => route('keberangkatan-haji.index')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="mb-6 flex items-center justify-between border-b pb-4 border-gray-100 dark:border-gray-700">
        <div>
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $keberangkatan->nama_keberangkatan }}</h3>
            <p class="text-sm text-gray-500">{{ $keberangkatan->kode_keberangkatan }}</p>
        </div>
        <div class="text-right">
             <span class="@if($keberangkatan->status_keberangkatan == 'active') bg-blue-100 text-blue-800 @else bg-green-100 text-green-800 @endif px-3 py-1 rounded-full text-sm font-semibold uppercase">
                {{ $keberangkatan->status_keberangkatan }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 mb-8">
        <div>
            <h4 class="text-sm font-semibold uppercase text-gray-500 mb-2">Tanggal Keberangkatan</h4>
            <div class="text-gray-800 dark:text-white font-medium">
                {{ \Carbon\Carbon::parse($keberangkatan->tanggal_keberangkatan)->translatedFormat('d F Y') }}
            </div>
        </div>
         <div>
            <h4 class="text-sm font-semibold uppercase text-gray-500 mb-2">Durasi</h4>
            <div class="text-gray-800 dark:text-white font-medium">
                {{ $keberangkatan->jumlah_hari }} Hari
            </div>
        </div>
         <div>
            <h4 class="text-sm font-semibold uppercase text-gray-500 mb-2">Kuota Jamaah</h4>
            <div class="text-gray-800 dark:text-white font-medium">
                {{ $keberangkatan->kuota_jamaah }} Pax
            </div>
        </div>
         <div>
            <h4 class="text-sm font-semibold uppercase text-gray-500 mb-2">Paket Haji</h4>
            <div class="text-gray-800 dark:text-white">
                <a href="#" class="text-blue-500 hover:underline">{{ $keberangkatan->paketHaji->nama_paket }} ({{ $keberangkatan->paketHaji->kode_paket }})</a>
            </div>
        </div>
          <div class="md:col-span-2 lg:col-span-3">
            <h4 class="text-sm font-semibold uppercase text-gray-500 mb-2">Catatan</h4>
            <div class="text-gray-800 dark:text-white">
                {{ $keberangkatan->catatan ?? '-' }}
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-end gap-3">
        <a href="{{ route('keberangkatan-haji.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Kembali</a>
        <a href="{{ route('keberangkatan-haji.edit', $keberangkatan->id) }}" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Edit Data</a>
    </div>
</div>
@endsection
