@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Jamaah Manifest" :breadcrumbs="[
    ['label' => 'Manifest Jamaah', 'url' => route('customer-umroh.index', $customer->keberangkatan_umroh_id)],
    ['label' => 'Detail Jamaah', 'url' => '#']
]" />

<div class="space-y-6">
    <!-- Main Info -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <div class="mb-6 flex items-center justify-between border-b pb-4 border-gray-100 dark:border-gray-700">
            <div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $customer->jamaah->nama_lengkap }}</h3>
                <p class="text-sm text-gray-500">Kode Jamaah: {{ $customer->jamaah->kode_jamaah }} | NIK: {{ $customer->jamaah->nik_jamaah }}</p>
            </div>
            <div class="text-right">
                 @if($customer->sisa_tagihan <= 0)
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold uppercase">Lunas</span>
                @else
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold uppercase">Belum Lunas</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 mb-8">
            <!-- Personal Info -->
             <div>
                <h4 class="text-sm font-semibold uppercase text-gray-500 mb-2">Informasi Pribadi</h4>
                <div class="space-y-2 text-sm text-gray-800 dark:text-white">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Paspor:</span>
                        <span class="font-medium">{{ $customer->jamaah->nomor_paspor ?? '-' }}</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-500">Jenis Kelamin:</span>
                        <span>{{ $customer->jamaah->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-500">Nama Keluarga:</span>
                        <span class="font-medium">{{ $customer->nama_keluarga ?? '-' }}</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-500">Agent:</span>
                        <span class="font-medium text-blue-500">{{ $customer->agent->nama_agent ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Package Info -->
             <div>
                <h4 class="text-sm font-semibold uppercase text-gray-500 mb-2">Paket & Keberangkatan</h4>
                 <div class="space-y-2 text-sm text-gray-800 dark:text-white">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Keberangkatan:</span>
                        <span class="font-medium">{{ $customer->keberangkatanUmroh->nama_keberangkatan }}</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-500">Tipe Kamar:</span>
                        <span class="font-medium uppercase">{{ $customer->tipe_kamar }}</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-500">Jumlah Pax:</span>
                        <span class="font-medium">{{ $customer->jumlah_jamaah }}</span>
                    </div>
                </div>
            </div>

             <!-- Financial Info -->
             <div>
                <h4 class="text-sm font-semibold uppercase text-gray-500 mb-2">Keuangan</h4>
                 <div class="space-y-2 text-sm text-gray-800 dark:text-white">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Harga Paket (Total):</span>
                        <span class="font-medium">Rp {{ number_format($customer->harga_paket * $customer->jumlah_jamaah, 0, ',', '.') }}</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-500">Diskon:</span>
                        <span class="font-medium text-red-500">- Rp {{ number_format($customer->diskon, 0, ',', '.') }}</span>
                    </div>
                     <div class="flex justify-between border-t py-1">
                        <span class="text-gray-500 font-semibold">Total Tagihan:</span>
                        <span class="font-bold">Rp {{ number_format($customer->total_tagihan, 0, ',', '.') }}</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-500">Sudah Bayar:</span>
                        <span class="font-medium text-green-600">Rp {{ number_format($customer->total_bayar, 0, ',', '.') }}</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-500">Sisa Tagihan:</span>
                        <span class="font-bold text-red-600">Rp {{ number_format($customer->sisa_tagihan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            
             <!-- Status Checks -->
              <div class="lg:col-span-3">
                <h4 class="text-sm font-semibold uppercase text-gray-500 mb-3">Status Dokumen & Perlengkapan</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                     <div class="flex items-center space-x-2 border p-3 rounded-lg dark:border-gray-700">
                        <span class="{{ $customer->status_visa ? 'text-green-500' : 'text-gray-400' }}">
                             @if($customer->status_visa)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                  <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                </svg>
                             @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                             @endif
                        </span>
                        <div>
                            <span class="block text-sm font-medium text-gray-800 dark:text-white">Visa</span>
                            <span class="text-xs text-gray-500">{{ $customer->status_visa ? 'Terproses' : 'Pending' }}</span>
                        </div>
                    </div>
                    
                     <div class="flex items-center space-x-2 border p-3 rounded-lg dark:border-gray-700">
                        <span class="{{ $customer->status_tiket ? 'text-green-500' : 'text-gray-400' }}">
                             @if($customer->status_tiket)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                  <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                </svg>
                             @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                             @endif
                        </span>
                         <div>
                            <span class="block text-sm font-medium text-gray-800 dark:text-white">Tiket</span>
                            <span class="text-xs text-gray-500">{{ $customer->status_tiket ? 'Terproses' : 'Pending' }}</span>
                        </div>
                    </div>
                    
                     <div class="flex items-center space-x-2 border p-3 rounded-lg dark:border-gray-700">
                        <span class="{{ $customer->status_siskopatuh ? 'text-green-500' : 'text-gray-400' }}">
                             @if($customer->status_siskopatuh)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                  <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                </svg>
                             @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                             @endif
                        </span>
                         <div>
                            <span class="block text-sm font-medium text-gray-800 dark:text-white">Siskopatuh</span>
                            <span class="text-xs text-gray-500">{{ $customer->status_siskopatuh ? 'Terproses' : 'Pending' }}</span>
                        </div>
                    </div>
                    
                     <div class="flex items-center space-x-2 border p-3 rounded-lg dark:border-gray-700">
                        <span class="{{ $customer->status_perlengkapan ? 'text-green-500' : 'text-gray-400' }}">
                             @if($customer->status_perlengkapan)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                  <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                </svg>
                             @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                             @endif
                        </span>
                         <div>
                            <span class="block text-sm font-medium text-gray-800 dark:text-white">Perlengkapan</span>
                            <span class="text-xs text-gray-500">{{ $customer->status_perlengkapan ? 'Diterima' : 'Belum' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
             <div class="col-span-1 lg:col-span-3">
                <h4 class="text-sm font-semibold uppercase text-gray-500 mb-2">Catatan</h4>
                 <div class="bg-gray-50 rounded p-4 text-sm text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                    {{ $customer->catatan ?? 'Tidak ada catatan' }}
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('customer-umroh.index', $customer->keberangkatan_umroh_id) }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Kembali</a>
            <a href="{{ route('customer-umroh.edit', $customer->id) }}" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Edit Data</a>
        </div>
    </div>
</div>
@endsection
