@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Data Layanan" :breadcrumbs="[
    ['label' => 'Data Layanan', 'url' => route('data-layanan')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Informasi Layanan</h3>
                <div class="flex gap-2">
                    <a href="{{ route('data-layanan.edit', $layanan->id) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                        Edit
                    </a>
                    <a href="{{ route('data-layanan') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                        Kembali
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                
                <!-- Use 1/3 width for photo on desktop -->
                <div class="flex flex-col items-center justify-center md:col-span-1">
                     <div class="h-40 w-40 overflow-hidden rounded-lg border-4 border-gray-100 dark:border-gray-800">
                        <img src="{{ $layanan->foto_layanan ? asset('storage/' . $layanan->foto_layanan) : 'https://placehold.co/400x400/e2e8f0/1e293b?text=' . substr($layanan->nama_layanan, 0, 1) }}" alt="Foto Layanan" class="h-full w-full object-cover"/>
                    </div>
                </div>

                <div class="md:col-span-2 grid grid-cols-1 gap-6 md:grid-cols-2">
                     <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kode Layanan</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $layanan->kode_layanan }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Layanan</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $layanan->jenis_layanan }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Layanan</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $layanan->nama_layanan }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Satuan Unit</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $layanan->satuan_unit }}</p>
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status Layanan</label>
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $layanan->status_layanan === 'Active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ $layanan->status_layanan }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Harga Modal</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">
                                @if(!$layanan->kurs || $layanan->kurs === 'IDR')
                                    Rp {{ number_format($layanan->harga_modal, 0, ',', '.') }}
                                @else
                                    {{ $layanan->kurs === 'MYR' ? 'RM' : $layanan->kurs }} {{ number_format($layanan->harga_modal_asing, 0, ',', '.') }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400">(Rp {{ number_format($layanan->harga_modal, 0, ',', '.') }})</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Harga Jual</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">
                                @if(!$layanan->kurs || $layanan->kurs === 'IDR')
                                    Rp {{ number_format($layanan->harga_jual, 0, ',', '.') }}
                                @else
                                    {{ $layanan->kurs === 'MYR' ? 'RM' : $layanan->kurs }} {{ number_format($layanan->harga_jual_asing, 0, ',', '.') }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400">(Rp {{ number_format($layanan->harga_jual, 0, ',', '.') }})</span>
                                @endif
                            </p>
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Catatan Layanan</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $layanan->catatan_layanan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
