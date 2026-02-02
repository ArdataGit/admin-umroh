@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Data Karyawan" :breadcrumbs="[
    ['label' => 'Data Karyawan', 'url' => route('data-karyawan')]
]" />
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        <div class="col-span-12">
            <x-common.component-card title="Detail Karyawan">
                <div class="space-y-6">
                    
                    <!-- Foto Karyawan -->
                    <div class="flex justify-center md:justify-start">
                        <div class="h-32 w-32 overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 ring-2 ring-white dark:ring-gray-900 shadow-md">
                            @if($karyawan->foto_karyawan)
                                <img src="{{ asset('storage/' . $karyawan->foto_karyawan) }}" alt="{{ $karyawan->nama_karyawan }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-gray-300 dark:text-gray-600">
                                    <svg class="h-16 w-16" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Kode Karyawan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kode Karyawan
                            </label>
                            <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                {{ $karyawan->kode_karyawan }}
                            </div>
                        </div>

                         <!-- NIK Karyawan -->
                         <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                NIK Karyawan
                            </label>
                            <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                {{ $karyawan->nik_karyawan }}
                            </div>
                        </div>

                        <!-- Nama Karyawan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nama Karyawan
                            </label>
                            <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                {{ $karyawan->nama_karyawan }}
                            </div>
                        </div>

                        <!-- Kontak Karyawan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kontak Karyawan
                            </label>
                            <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                {{ $karyawan->kontak_karyawan }}
                            </div>
                        </div>

                        <!-- Email Karyawan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Email Karyawan
                            </label>
                            <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                {{ $karyawan->email_karyawan }}
                            </div>
                        </div>

                         <!-- Kabupaten/Kota -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kabupaten/Kota
                            </label>
                            <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                {{ $karyawan->kabupaten_kota }}
                            </div>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Jenis Kelamin
                            </label>
                            <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                {{ $karyawan->jenis_kelamin }}
                            </div>
                        </div>

                        <!-- TTL -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Tempat, Tanggal Lahir
                            </label>
                            <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                {{ $karyawan->tempat_lahir }}, {{ \Carbon\Carbon::parse($karyawan->tanggal_lahir)->translatedFormat('d F Y') }}
                            </div>
                        </div>
                    </div>

                    <!-- Alamat Karyawan -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Alamat Karyawan
                        </label>
                        <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 min-h-[60px]">
                            {{ $karyawan->alamat_karyawan }}
                        </div>
                    </div>

                    <!-- Catatan Karyawan -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Catatan Karyawan
                        </label>
                        <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 min-h-[60px]">
                            {{ $karyawan->catatan_karyawan ?? '-' }}
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
                        <a href="{{ route('data-karyawan') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.8334 10H4.16675M4.16675 10L10.0001 15.8333M4.16675 10L10.0001 4.16667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Kembali
                        </a>
                        <a href="{{ route('data-karyawan.edit', $karyawan->id) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.5858 3.58579C14.3668 2.80474 15.6332 2.80474 16.4142 3.58579C17.1953 4.36683 17.1953 5.63316 16.4142 6.41421L15.6213 7.20711L12.7929 4.37868L13.5858 3.58579Z" fill="currentColor"/>
                                <path d="M11.3787 5.79289L3 14.1716V17H5.82842L14.2071 8.62132L11.3787 5.79289Z" fill="currentColor"/>
                            </svg>
                            Edit
                        </a>
                    </div>
                </div>
            </x-common.component-card>
        </div>

    </div>
@endsection
