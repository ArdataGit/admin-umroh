@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Surat Rekomendasi" :breadcrumbs="[
    ['label' => 'Surat Rekomendasi', 'url' => route('surat-rekomendasi.index')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Detail Surat - {{ $surat->nomor_dokumen }}</h3>
                    <p class="text-sm text-gray-500">Tanggal Buat: {{ $surat->created_at->format('d F Y H:i') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('surat-rekomendasi.edit', $surat->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Edit</a>
                    <a href="{{ route('surat-rekomendasi.print-pdf', $surat->id) }}" target="_blank" class="rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">Print</a>
                    <a href="{{ route('surat-rekomendasi.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Kembali</a>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Nomor Dokumen:</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $surat->nomor_dokumen }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Nama Jamaah:</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $surat->jamaah->nama_jamaah ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Keberangkatan:</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $surat->keberangkatanUmroh->nama_keberangkatan ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Kantor Imigrasi:</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $surat->kantor_imigrasi }}</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                         <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Nama Ayah:</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $surat->nama_ayah }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Nama Kakek:</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $surat->nama_kakek }}</span>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Catatan:</h4>
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg text-gray-700 dark:text-gray-300 italic">
                            {{ $surat->catatan ?? 'Tidak ada catatan.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
