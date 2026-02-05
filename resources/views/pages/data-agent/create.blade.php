@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Data Agent" :breadcrumbs="[
    ['label' => 'Data Agent', 'url' => route('data-agent')],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <form action="{{ route('data-agent.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    
                    <!-- Foto Agent -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Agent (Opsional)</label>
                        <input type="file" name="foto_agent" accept="image/*" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maks: 2MB</p>
                        @error('foto_agent') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Kode Agent (Readonly) -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Agent</label>
                        <input type="text" name="kode_agent" value="{{ $kodeAgent }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" />
                    </div>

                    <!-- NIK Agent -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">NIK Agent</label>
                        <input type="text" name="nik_agent" value="{{ old('nik_agent') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan NIK" oninput="this.value = this.value.replace(/[^0-9]/g, '')" inputmode="numeric" required />
                        @error('nik_agent') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nama Agent -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Agent</label>
                        <input type="text" name="nama_agent" value="{{ old('nama_agent') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Nama Agent" required />
                        @error('nama_agent') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kontak Agent -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kontak Agent</label>
                        <input type="text" name="kontak_agent" value="{{ old('kontak_agent') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Kontak" oninput="this.value = this.value.replace(/[^0-9]/g, '')" inputmode="numeric" required />
                        @error('kontak_agent') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email Agent -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Email Agent</label>
                        <input type="email" name="email_agent" value="{{ old('email_agent') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Email" />
                        @error('email_agent') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kabupaten/Kota -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kabupaten/Kota</label>
                        <input type="text" name="kabupaten_kota" value="{{ old('kabupaten_kota') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Kabupaten/Kota" required />
                        @error('kabupaten_kota') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Tempat Lahir" required />
                        @error('tempat_lahir') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" max="9999-12-31" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                        @error('tanggal_lahir') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status Agent -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Agent</label>
                        <select name="status_agent" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
                            <option value="Active" {{ old('status_agent') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Non Active" {{ old('status_agent') == 'Non Active' ? 'selected' : '' }}>Non Active</option>
                        </select>
                        @error('status_agent') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Komisi Paket Umroh -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Komisi Paket Umroh</label>
                        <input type="number" name="komisi_paket_umroh" value="{{ old('komisi_paket_umroh', 0) }}" min="0" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" required />
                        @error('komisi_paket_umroh') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                     <!-- Komisi Paket Haji -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Komisi Paket Haji</label>
                        <input type="number" name="komisi_paket_haji" value="{{ old('komisi_paket_haji', 0) }}" min="0" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" required />
                        @error('komisi_paket_haji') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alamat Agent -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat Agent</label>
                        <textarea name="alamat_agent" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Alamat Lengkap" required>{{ old('alamat_agent') }}</textarea>
                        @error('alamat_agent') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan Agent -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Agent</label>
                        <textarea name="catatan_agent" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Catatan (Opsional)">{{ old('catatan_agent') }}</textarea>
                        @error('catatan_agent') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('data-agent') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
