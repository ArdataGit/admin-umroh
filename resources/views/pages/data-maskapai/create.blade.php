@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Data Maskapai" :breadcrumbs="[
    ['label' => 'Data Maskapai', 'url' => route('data-maskapai')]
]" />
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        <div class="col-span-12">
            <x-common.component-card title="Form Tambah Maskapai">
                <form action="{{ route('data-maskapai.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2" x-data="{ kurs: '{{ old('kurs', 'IDR') }}' }">

                        <!-- Foto Maskapai -->
                        <div class="col-span-1 md:col-span-2">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Foto Maskapai (Opsional)
                            </label>
                            <input type="file" name="foto_maskapai" accept="image/*"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, PNG, GIF. Maks: 2MB</p>
                            @error('foto_maskapai')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Kode Maskapai (Readonly) -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kode Maskapai <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode_maskapai" value="{{ $kodeMaskapai }}" readonly
                                class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kode maskapai di-generate otomatis</p>
                            @error('kode_maskapai')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Maskapai -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nama Maskapai <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_maskapai" value="{{ old('nama_maskapai') }}" placeholder="Masukkan nama maskapai" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nama_maskapai') border-red-500 @enderror" />
                            @error('nama_maskapai')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rute Penerbangan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Rute Penerbangan <span class="text-red-500">*</span>
                            </label>
                            <div x-data="{ isOptionSelected: {{ old('rute_penerbangan') ? 'true' : 'false' }} }" class="relative z-20 bg-transparent">
                                <select name="rute_penerbangan" required
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
                                    <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Pilih Rute
                                    </option>
                                    <option value="Direct" {{ old('rute_penerbangan') == 'Direct' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Direct
                                    </option>
                                    <option value="Transit" {{ old('rute_penerbangan') == 'Transit' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Transit
                                    </option>
                                </select>
                                <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                            @error('rute_penerbangan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lama Perjalanan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Lama Perjalanan (Jam) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="lama_perjalanan" value="{{ old('lama_perjalanan') }}" placeholder="9" required min="0"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('lama_perjalanan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Kurs -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Jenis Kurs <span class="text-red-500">*</span>
                            </label>
                            <div class="relative z-20 bg-transparent">
                                <select name="kurs" required x-model="kurs"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="kurs !== '' && 'text-gray-800 dark:text-white/90'">
                                    <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Pilih Kurs
                                    </option>
                                    <option value="IDR" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Rupiah (IDR)
                                    </option>
                                    <option value="USD" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        USD
                                    </option>
                                    <option value="SAR" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        SAR
                                    </option>
                                    <option value="MYR" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        RM (Ringgit)
                                    </option>
                                </select>
                                <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                            @error('kurs')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Harga Tiket -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Harga Tiket <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium" x-text="kurs === 'IDR' || kurs === '' ? 'Rp' : (kurs === 'MYR' ? 'RM' : kurs)">
                                    Rp
                                </span>
                                <input type="number" name="harga_tiket" value="{{ old('harga_tiket') }}" placeholder="15000000" required min="0" step="0.01"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            </div>
                            @error('harga_tiket')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            
                            <!-- Kurs Info Compact -->
                            <div class="mt-3 flex items-center gap-4 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10 p-2 rounded-lg border border-blue-100 dark:border-blue-900/30 w-fit" x-show="['USD', 'SAR', 'MYR'].includes(kurs)" x-cloak>
                                <div class="flex items-center gap-1.5" x-show="kurs === 'USD'">
                                    <span class="opacity-70">Kurs USD Hari Ini:</span>
                                    <span>Rp {{ number_format($kursUsd, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center gap-1.5" x-show="kurs === 'SAR'">
                                    <span class="opacity-70">Kurs SAR Hari Ini:</span>
                                    <span>Rp {{ number_format($kursSar, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center gap-1.5" x-show="kurs === 'MYR'">
                                    <span class="opacity-70">Kurs RM Hari Ini:</span>
                                    <span>Rp {{ number_format($kursMyr, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Penerbangan -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Catatan Penerbangan
                        </label>
                        <textarea name="catatan_penerbangan" placeholder="Masukkan catatan atau keterangan tambahan tentang penerbangan..." rows="4"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('catatan_penerbangan') }}</textarea>
                        @error('catatan_penerbangan')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
                        <a href="{{ route('data-maskapai') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.8334 10H4.16675M4.16675 10L10.0001 15.8333M4.16675 10L10.0001 4.16667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Simpan Data Maskapai
                        </button>
                    </div>
                </form>
            </x-common.component-card>
        </div>

    </div>
@endsection
