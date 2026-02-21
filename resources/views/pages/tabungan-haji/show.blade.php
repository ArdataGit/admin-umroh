@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Tabungan Haji" :breadcrumbs="[
    ['label' => 'Data Tabungan Haji', 'url' => route('tabungan-haji')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-700">
        <div>
             <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $tabungan->kode_tabungan }}</h2>
             <p class="text-sm text-gray-500 dark:text-gray-300">Jamaah: {{ $tabungan->jamaah->nama_lengkap }}</p>
        </div>
       
        <div class="flex gap-2">
            <a href="{{ route('tabungan-haji') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">Kembali</a>
            <a href="{{ route('tabungan-haji.edit', $tabungan->id) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600 focus:ring-4 focus:ring-blue-500/20">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
        <div>
            <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white">Informasi Tabungan</h3>
            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-300">Tanggal Pendaftaran</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $tabungan->tanggal_pendaftaran }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-300">Status</span>
                    <span class="px-2 py-1 rounded text-xs font-semibold uppercase {{ $tabungan->status_tabungan == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300' }}">{{ $tabungan->status_tabungan }}</span>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white">Detail Pembayaran</h3>
            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-300">Bank</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $tabungan->bank_tabungan }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-300">No. Rekening</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $tabungan->rekening_tabungan }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-300">Setoran Tabungan</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">Rp {{ number_format($tabungan->setoran_tabungan, 0, ',', '.') }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-300">Metode Pembayaran</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $tabungan->metode_pembayaran }}</span>
                </div>
                 @if($tabungan->catatan_pembayaran) 
                    <div class="mt-2 text-gray-600 dark:text-gray-300 border-t border-gray-100 pt-2"><span class="font-semibold block">Catatan:</span> {{ $tabungan->catatan_pembayaran }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
