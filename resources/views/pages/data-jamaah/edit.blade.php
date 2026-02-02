@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Data Jamaah" :breadcrumbs="[
    ['label' => 'Data Jamaah', 'url' => route('data-jamaah')],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <form action="{{ route('data-jamaah.update', $jamaah->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-8 border-b border-gray-200 pb-8 dark:border-gray-700">
                    <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Data Pribadi Jamaah</h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Jamaah</label>
                            <input type="text" name="kode_jamaah" value="{{ $jamaah->kode_jamaah }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">NIK Jamaah</label>
                            <input type="text" name="nik_jamaah" value="{{ old('nik_jamaah', $jamaah->nik_jamaah) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Jamaah (Sesuai KTP)</label>
                            <input type="text" name="nama_jamaah" value="{{ old('nama_jamaah', $jamaah->nama_jamaah) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                                <option value="L" {{ old('jenis_kelamin', $jamaah->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $jamaah->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $jamaah->tempat_lahir) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $jamaah->tanggal_lahir) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kontak Jamaah</label>
                            <input type="text" name="kontak_jamaah" value="{{ old('kontak_jamaah', $jamaah->kontak_jamaah) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Email Jamaah</label>
                            <input type="email" name="email_jamaah" value="{{ old('email_jamaah', $jamaah->email_jamaah) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan', $jamaah->kecamatan) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kabupaten/Kota</label>
                            <input type="text" name="kabupaten_kota" value="{{ old('kabupaten_kota', $jamaah->kabupaten_kota) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Provinsi</label>
                            <input type="text" name="provinsi" value="{{ old('provinsi', $jamaah->provinsi) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                        </div>
                         <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat Singkat</label>
                            <input type="text" name="alamat_jamaah" value="{{ old('alamat_jamaah', $jamaah->alamat_jamaah) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" rows="2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required>{{ old('alamat_lengkap', $jamaah->alamat_lengkap) }}</textarea>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Jamaah</label>
                            <textarea name="catatan_jamaah" rows="2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">{{ old('catatan_jamaah', $jamaah->catatan_jamaah) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-8 border-b border-gray-200 pb-8 dark:border-gray-700">
                    <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Data Paspor Jamaah</h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama di Paspor</label>
                            <input type="text" name="nama_paspor" value="{{ old('nama_paspor', $jamaah->nama_paspor) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Paspor</label>
                            <input type="text" name="nomor_paspor" value="{{ old('nomor_paspor', $jamaah->nomor_paspor) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kantor Imigrasi Penerbit</label>
                            <input type="text" name="kantor_imigrasi" value="{{ old('kantor_imigrasi', $jamaah->kantor_imigrasi) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Paspor Aktif</label>
                            <input type="date" name="tgl_paspor_aktif" value="{{ old('tgl_paspor_aktif', $jamaah->tgl_paspor_aktif) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Paspor Expired</label>
                            <input type="date" name="tgl_paspor_expired" value="{{ old('tgl_paspor_expired', $jamaah->tgl_paspor_expired) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Upload Dokumen</h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Jamaah</label>
                            @if($jamaah->foto_jamaah)
                                <img src="{{ asset('storage/' . $jamaah->foto_jamaah) }}" class="mb-2 h-20 w-20 rounded-md object-cover">
                            @endif
                            <input type="file" name="foto_jamaah" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm" accept="image/*" />
                        </div>
                         <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto KTP</label>
                            @if($jamaah->foto_ktp)
                                <img src="{{ asset('storage/' . $jamaah->foto_ktp) }}" class="mb-2 h-20 w-30 rounded-md object-cover">
                            @endif
                            <input type="file" name="foto_ktp" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm" accept="image/*" />
                        </div>
                         <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto KK</label>
                             @if($jamaah->foto_kk)
                                <img src="{{ asset('storage/' . $jamaah->foto_kk) }}" class="mb-2 h-20 w-30 rounded-md object-cover">
                            @endif
                            <input type="file" name="foto_kk" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm" accept="image/*" />
                        </div>
                         <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Paspor 1</label>
                             @if($jamaah->foto_paspor_1)
                                <img src="{{ asset('storage/' . $jamaah->foto_paspor_1) }}" class="mb-2 h-20 w-30 rounded-md object-cover">
                            @endif
                            <input type="file" name="foto_paspor_1" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm" accept="image/*" />
                        </div>
                         <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Paspor 2</label>
                             @if($jamaah->foto_paspor_2)
                                <img src="{{ asset('storage/' . $jamaah->foto_paspor_2) }}" class="mb-2 h-20 w-30 rounded-md object-cover">
                            @endif
                            <input type="file" name="foto_paspor_2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm" accept="image/*" />
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('data-jamaah') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
