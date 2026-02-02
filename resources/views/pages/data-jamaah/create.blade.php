@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Data Jamaah" :breadcrumbs="[
    ['label' => 'Data Jamaah', 'url' => route('data-jamaah')],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <form action="{{ route('data-jamaah.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Data Pribadi Section -->
                <div class="mb-8 border-b border-gray-200 pb-8 dark:border-gray-700">
                    <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Data Pribadi Jamaah</h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        
                        <!-- Kode Jamaah -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Jamaah</label>
                            <input type="text" name="kode_jamaah" value="{{ $kodeJamaah }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" />
                        </div>

                        <!-- NIK -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">NIK Jamaah</label>
                            <input type="text" name="nik_jamaah" value="{{ old('nik_jamaah') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan NIK" required />
                            @error('nik_jamaah') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nama Jamaah (KTP) -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Jamaah (Sesuai KTP)</label>
                            <input type="text" name="nama_jamaah" value="{{ old('nama_jamaah') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Nama" required />
                            @error('nama_jamaah') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tempat Lahir -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Tempat Lahir" required />
                            @error('tempat_lahir') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" required />
                            @error('tanggal_lahir') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kontak Jamaah -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kontak Jamaah</label>
                            <input type="text" name="kontak_jamaah" value="{{ old('kontak_jamaah') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="No HP/WA" required />
                            @error('kontak_jamaah') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email Jamaah -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Email Jamaah</label>
                            <input type="email" name="email_jamaah" value="{{ old('email_jamaah') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Email" />
                            @error('email_jamaah') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Kecamatan" required />
                            @error('kecamatan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kabupaten/Kota -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kabupaten/Kota</label>
                            <input type="text" name="kabupaten_kota" value="{{ old('kabupaten_kota') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Kabupaten/Kota" required />
                            @error('kabupaten_kota') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Provinsi -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Provinsi</label>
                            <input type="text" name="provinsi" value="{{ old('provinsi') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Provinsi" required />
                            @error('provinsi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Alamat Jamaah (Pendek) -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat Singkat</label>
                            <input type="text" name="alamat_jamaah" value="{{ old('alamat_jamaah') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Jl. Contoh No. 123" required />
                            @error('alamat_jamaah') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="col-span-1 md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" rows="2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Alamat lengkap sesuai KTP" required>{{ old('alamat_lengkap') }}</textarea>
                            @error('alamat_lengkap') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="col-span-1 md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Jamaah</label>
                            <textarea name="catatan_jamaah" rows="2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('catatan_jamaah') }}</textarea>
                            @error('catatan_jamaah') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Data Paspor Section -->
                <div class="mb-8 border-b border-gray-200 pb-8 dark:border-gray-700">
                    <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Data Paspor Jamaah</h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        
                        <!-- Nama Paspor -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama di Paspor</label>
                            <input type="text" name="nama_paspor" value="{{ old('nama_paspor') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Nama Paspor" />
                        </div>

                        <!-- Nomor Paspor -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Paspor</label>
                            <input type="text" name="nomor_paspor" value="{{ old('nomor_paspor') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Nomor Paspor" />
                        </div>

                        <!-- Kantor Imigrasi -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kantor Imigrasi Penerbit</label>
                            <input type="text" name="kantor_imigrasi" value="{{ old('kantor_imigrasi') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Kantor Imigrasi" />
                        </div>

                        <!-- Tgl Paspor Aktif -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Paspor Aktif</label>
                            <input type="date" name="tgl_paspor_aktif" value="{{ old('tgl_paspor_aktif') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        <!-- Tgl Paspor Expired -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Paspor Expired</label>
                            <input type="date" name="tgl_paspor_expired" value="{{ old('tgl_paspor_expired') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>
                    </div>
                </div>

                <!-- Upload Dokumen Section -->
                <div>
                    <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Upload Dokumen</h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        
                        <!-- Foto Jamaah -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Jamaah</label>
                            <input type="file" name="foto_jamaah" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none" accept="image/*" />
                            @error('foto_jamaah') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                         <!-- Foto KTP -->
                         <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto KTP</label>
                            <input type="file" name="foto_ktp" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none" accept="image/*" />
                        </div>

                         <!-- Foto KK -->
                         <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto KK</label>
                            <input type="file" name="foto_kk" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none" accept="image/*" />
                        </div>

                         <!-- Foto Paspor 1 -->
                         <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Paspor 1</label>
                            <input type="file" name="foto_paspor_1" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none" accept="image/*" />
                        </div>

                         <!-- Foto Paspor 2 -->
                         <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Paspor 2</label>
                            <input type="file" name="foto_paspor_2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none" accept="image/*" />
                        </div>

                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('data-jamaah') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
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
