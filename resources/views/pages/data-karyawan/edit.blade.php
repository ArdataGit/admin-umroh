@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Data Karyawan" :breadcrumbs="[
    ['label' => 'Data Karyawan', 'url' => route('data-karyawan')]
]" />
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        <div class="col-span-12">
            <x-common.component-card title="Form Edit Karyawan">
                <form action="{{ route('data-karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Kode Karyawan (Readonly) -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kode Karyawan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode_karyawan" value="{{ $karyawan->kode_karyawan }}" readonly
                                class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kode karyawan tidak dapat diubah</p>
                        </div>

                        <!-- NIK Karyawan -->
                         <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                NIK Karyawan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nik_karyawan" value="{{ old('nik_karyawan', $karyawan->nik_karyawan) }}" placeholder="Masukkan NIK" required maxlength="16"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nik_karyawan') border-red-500 @enderror" />
                            @error('nik_karyawan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Karyawan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nama Karyawan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_karyawan" value="{{ old('nama_karyawan', $karyawan->nama_karyawan) }}" placeholder="Masukkan nama karyawan" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nama_karyawan') border-red-500 @enderror" />
                            @error('nama_karyawan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kontak Karyawan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kontak Karyawan <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="kontak_karyawan" value="{{ old('kontak_karyawan', $karyawan->kontak_karyawan) }}" placeholder="08xxxxxxxxxx" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('kontak_karyawan') border-red-500 @enderror" />
                             @error('kontak_karyawan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Karyawan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Email Karyawan <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email_karyawan" value="{{ old('email_karyawan', $karyawan->email_karyawan) }}" placeholder="email@example.com" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('email_karyawan') border-red-500 @enderror" />
                             @error('email_karyawan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kabupaten/Kota -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kota/Kabupaten <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kabupaten_kota" value="{{ old('kabupaten_kota', $karyawan->kabupaten_kota) }}" placeholder="Masukkan Kota/Kabupaten" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('kabupaten_kota') border-red-500 @enderror" />
                             @error('kabupaten_kota')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <div x-data="{ isOptionSelected: true }" class="relative z-20 bg-transparent">
                                <select name="jenis_kelamin" required
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
                                    <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Pilih Jenis Kelamin
                                    </option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Laki-laki
                                    </option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Perempuan
                                    </option>
                                </select>
                                <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                         <!-- Tempat Lahir -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Tempat Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $karyawan->tempat_lahir) }}" placeholder="Masukkan Tempat Lahir" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('tempat_lahir') border-red-500 @enderror" />
                             @error('tempat_lahir')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                         <!-- Tanggal Lahir -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir) }}" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('tanggal_lahir') border-red-500 @enderror" />
                             @error('tanggal_lahir')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gaji -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Gaji <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">Rp</span>
                                <input type="number" name="gaji" value="{{ old('gaji', $karyawan->gaji) }}" placeholder="0" required min="0" step="0.01"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-11 pr-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('gaji') border-red-500 @enderror" />
                            </div>
                             @error('gaji')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto Karyawan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Foto Karyawan
                            </label>
                            <input type="file" name="foto_karyawan" accept="image/*"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('foto_karyawan') border-red-500 @enderror" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, PNG, GIF. Maks: 2MB</p>
                             @error('foto_karyawan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            
                            @if($karyawan->foto_karyawan)
                                <div class="mt-2">
                                    <p class="text-xs text-gray-500 mb-1">Foto Saat Ini:</p>
                                    <div class="h-24 w-24 overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                        <img src="{{ asset('storage/' . $karyawan->foto_karyawan) }}" alt="Foto Karyawan" class="h-full w-full object-cover">
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>

                    <!-- Alamat Karyawan -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Alamat Karyawan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat_karyawan" placeholder="Masukkan alamat lengkap" rows="3" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('alamat_karyawan') border-red-500 @enderror">{{ old('alamat_karyawan', $karyawan->alamat_karyawan) }}</textarea>
                         @error('alamat_karyawan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan Karyawan -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Catatan Karyawan
                        </label>
                        <textarea name="catatan_karyawan" placeholder="Masukkan catatan tambahan..." rows="3"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('catatan_karyawan', $karyawan->catatan_karyawan) }}</textarea>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
                        <a href="{{ route('data-karyawan') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.8334 10H4.16675M4.16675 10L10.0001 15.8333M4.16675 10L10.0001 4.16667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Update Data Karyawan
                        </button>
                    </div>
                </form>
            </x-common.component-card>
        </div>

    </div>
@endsection
