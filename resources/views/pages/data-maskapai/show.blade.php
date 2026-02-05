@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Data Maskapai" :breadcrumbs="[
    ['label' => 'Data Maskapai', 'url' => route('data-maskapai')]
]" />
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        <div class="col-span-12">
            <x-common.component-card title="Detail Maskapai">
                <div class="space-y-6">
                    @if($maskapai->foto_maskapai)
                        <div class="flex justify-center">
                            <img src="{{ asset('storage/' . $maskapai->foto_maskapai) }}" alt="{{ $maskapai->nama_maskapai }}" class="h-40 w-40 rounded-xl object-contain shadow-sm border border-gray-100 dark:border-gray-800 bg-white" />
                        </div>
                    @endif
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Kode Maskapai -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kode Maskapai
                            </label>
                            <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                {{ $maskapai->kode_maskapai }}
                            </div>
                        </div>

                        <!-- Nama Maskapai -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nama Maskapai
                            </label>
                            <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                {{ $maskapai->nama_maskapai }}
                            </div>
                        </div>

                        <!-- Rute Penerbangan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Rute Penerbangan
                            </label>
                            <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                {{ $maskapai->rute_penerbangan }}
                            </div>
                        </div>

                        <!-- Lama Perjalanan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Lama Perjalanan (Jam)
                            </label>
                            <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                {{ $maskapai->lama_perjalanan }} Jam
                            </div>
                        </div>

                        <!-- Harga Tiket -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Harga Tiket
                            </label>
                            <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                                Rp {{ number_format($maskapai->harga_tiket, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Penerbangan -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Catatan Penerbangan
                        </label>
                        <div class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 min-h-[100px]">
                            {{ $maskapai->catatan_penerbangan ?? '-' }}
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
                        <a href="{{ route('data-maskapai') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.8334 10H4.16675M4.16675 10L10.0001 15.8333M4.16675 10L10.0001 4.16667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Kembali
                        </a>
                        <a href="{{ route('data-maskapai.edit', $maskapai->id) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
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
