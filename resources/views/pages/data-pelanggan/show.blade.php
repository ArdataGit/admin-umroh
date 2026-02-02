@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Data Pelanggan" :breadcrumbs="[
    ['label' => 'Data Pelanggan', 'url' => route('data-pelanggan')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Informasi Pelanggan</h3>
                <div class="flex gap-2">
                    <a href="{{ route('data-pelanggan.edit', $pelanggan->id) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                        Edit
                    </a>
                    <a href="{{ route('data-pelanggan') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                        Kembali
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                
                <!-- Use 1/3 width for photo on desktop -->
                <div class="flex flex-col items-center justify-center md:col-span-1">
                     <div class="h-40 w-40 overflow-hidden rounded-full border-4 border-gray-100 dark:border-gray-800">
                        <img src="{{ $pelanggan->foto_pelanggan ? asset('storage/' . $pelanggan->foto_pelanggan) : 'https://ui-avatars.com/api/?name=' . $pelanggan->nama_pelanggan . '&background=random&size=256' }}" alt="Foto Pelanggan" class="h-full w-full object-cover"/>
                    </div>
                </div>

                <div class="md:col-span-2 grid grid-cols-1 gap-6 md:grid-cols-2">
                     <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kode Pelanggan</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $pelanggan->kode_pelanggan }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Pelanggan</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $pelanggan->nama_pelanggan }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kontak Pelanggan</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $pelanggan->kontak_pelanggan }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email Pelanggan</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $pelanggan->email_pelanggan }}</p>
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status Pelanggan</label>
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $pelanggan->status_pelanggan === 'Active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ $pelanggan->status_pelanggan }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kabupaten/Kota</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $pelanggan->kabupaten_kota }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $pelanggan->jenis_kelamin }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Pelanggan</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $pelanggan->alamat_pelanggan }}</p>
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Catatan Pelanggan</label>
                            <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $pelanggan->catatan_pelanggan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
