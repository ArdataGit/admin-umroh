@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Data Hotel" :breadcrumbs="[
    ['label' => 'Data Hotel', 'url' => route('data-hotel')]
]" />
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        <div class="col-span-12">
            <x-common.component-card title="Form Tambah Hotel">
                <form action="{{ route('data-hotel.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Kode Hotel (Readonly) -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kode Hotel <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode_hotel" value="HT" readonly
                                class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kode hotel akan di-generate otomatis</p>
                        </div>

                        <!-- Nama Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nama Hotel <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_hotel" value="{{ old('nama_hotel') }}" placeholder="Masukkan nama hotel" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nama_hotel') border-red-500 @enderror" />
                            @error('nama_hotel')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lokasi Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Lokasi Hotel <span class="text-red-500">*</span>
                            </label>
                            <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                <select name="lokasi_hotel" required
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
                                    <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Pilih Lokasi
                                    </option>
                                    <option value="Mekkah" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Mekkah
                                    </option>
                                    <option value="Madinah" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Madinah
                                    </option>
                                    <option value="Jeddah" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Jeddah
                                    </option>
                                    <option value="Transit" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Transit
                                    </option>
                                </select>
                                <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <!-- Kontak Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kontak Hotel <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="kontak_hotel" placeholder="+966 12 345 6789" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>

                        <!-- Email Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Email Hotel <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email_hotel" placeholder="info@hotel.com" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>

                        <!-- Rating Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Rating Hotel <span class="text-red-500">*</span>
                            </label>
                            <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                <select name="rating_hotel" required
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
                                    <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Pilih Rating
                                    </option>
                                    <option value="1" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        ⭐ Bintang 1
                                    </option>
                                    <option value="2" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        ⭐⭐ Bintang 2
                                    </option>
                                    <option value="3" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        ⭐⭐⭐ Bintang 3
                                    </option>
                                    <option value="4" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        ⭐⭐⭐⭐ Bintang 4
                                    </option>
                                    <option value="5" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        ⭐⭐⭐⭐⭐ Bintang 5
                                    </option>
                                </select>
                                <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <!-- Harga Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Harga Hotel (per malam) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400">
                                    Rp
                                </span>
                                <input type="number" name="harga_hotel" placeholder="2500000" required min="0"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Hotel -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Catatan Hotel
                        </label>
                        <textarea name="catatan_hotel" placeholder="Masukkan catatan atau keterangan tambahan tentang hotel..." rows="4"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"></textarea>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
                        <a href="{{ route('data-hotel') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.8334 10H4.16675M4.16675 10L10.0001 15.8333M4.16675 10L10.0001 4.16667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Simpan Data Hotel
                        </button>
                    </div>
                </form>
            </x-common.component-card>
        </div>

    </div>
@endsection
