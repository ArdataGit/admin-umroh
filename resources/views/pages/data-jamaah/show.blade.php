@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Data Jamaah" :breadcrumbs="[
    ['label' => 'Data Jamaah', 'url' => route('data-jamaah')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Informasi Jamaah</h2>
        <div class="flex gap-2">
            <a href="{{ route('data-jamaah') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50">Kembali</a>
            <a href="{{ route('data-jamaah.edit', $jamaah->id) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">Edit</a>
        </div>
    </div>

    <!-- Data Pribadi -->
    <div class="mb-6">
        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Data Pribadi</h3>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <p class="text-sm font-medium text-gray-500">Kode Jamaah</p>
                <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">{{ $jamaah->kode_jamaah }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">NIK</p>
                <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">{{ $jamaah->nik_jamaah }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Nama</p>
                <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">{{ $jamaah->nama_jamaah }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Jenis Kelamin</p>
                <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $jamaah->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">TTL</p>
                <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $jamaah->tempat_lahir }}, {{ $jamaah->tanggal_lahir }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Kontak</p>
                <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $jamaah->kontak_jamaah }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Email</p>
                <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $jamaah->email_jamaah ?? '-' }}</p>
            </div>
             <div>
                <p class="text-sm font-medium text-gray-500">Alamat Singkat</p>
                <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $jamaah->alamat_jamaah }}</p>
            </div>
            <div class="col-span-1 md:col-span-2">
                <p class="text-sm font-medium text-gray-500">Alamat Lengkap</p>
                <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $jamaah->alamat_lengkap }}, {{ $jamaah->kecamatan }}, {{ $jamaah->kabupaten_kota }}, {{ $jamaah->provinsi }}</p>
            </div>
            <div class="col-span-1 md:col-span-2">
                <p class="text-sm font-medium text-gray-500">Catatan</p>
                <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $jamaah->catatan_jamaah ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Data Paspor -->
    <div class="mb-6 border-t border-gray-200 pt-6">
        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Data Paspor</h3>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <p class="text-sm font-medium text-gray-500">Nama di Paspor</p>
                <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $jamaah->nama_paspor ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Nomor Paspor</p>
                <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $jamaah->nomor_paspor ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Kantor Imigrasi Penerbit</p>
                <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $jamaah->kantor_imigrasi ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Masa Berlaku</p>
                <p class="mt-1 text-base text-gray-800 dark:text-white">
                    @if($jamaah->tgl_paspor_aktif && $jamaah->tgl_paspor_expired)
                        {{ $jamaah->tgl_paspor_aktif }} s/d {{ $jamaah->tgl_paspor_expired }}
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Dokumen -->
    <div class="border-t border-gray-200 pt-6">
        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Dokumen Jamaah</h3>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
            <div>
                <p class="mb-2 text-sm font-medium text-gray-500">Foto Jamaah</p>
                @if($jamaah->foto_jamaah)
                    <img src="{{ asset('storage/' . $jamaah->foto_jamaah) }}" class="h-32 w-full rounded-lg object-cover border border-gray-200">
                @else
                    <div class="flex h-32 w-full items-center justify-center rounded-lg bg-gray-100 text-gray-400">No Image</div>
                @endif
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-500">Foto KTP</p>
                 @if($jamaah->foto_ktp)
                    <img src="{{ asset('storage/' . $jamaah->foto_ktp) }}" class="h-32 w-full rounded-lg object-cover border border-gray-200">
                @else
                    <div class="flex h-32 w-full items-center justify-center rounded-lg bg-gray-100 text-gray-400">No Image</div>
                @endif
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-500">Foto KK</p>
                 @if($jamaah->foto_kk)
                    <img src="{{ asset('storage/' . $jamaah->foto_kk) }}" class="h-32 w-full rounded-lg object-cover border border-gray-200">
                @else
                    <div class="flex h-32 w-full items-center justify-center rounded-lg bg-gray-100 text-gray-400">No Image</div>
                @endif
            </div>
             <div>
                <p class="mb-2 text-sm font-medium text-gray-500">Foto Paspor 1</p>
                 @if($jamaah->foto_paspor_1)
                    <img src="{{ asset('storage/' . $jamaah->foto_paspor_1) }}" class="h-32 w-full rounded-lg object-cover border border-gray-200">
                @else
                    <div class="flex h-32 w-full items-center justify-center rounded-lg bg-gray-100 text-gray-400">No Image</div>
                @endif
            </div>
             <div>
                <p class="mb-2 text-sm font-medium text-gray-500">Foto Paspor 2</p>
                 @if($jamaah->foto_paspor_2)
                    <img src="{{ asset('storage/' . $jamaah->foto_paspor_2) }}" class="h-32 w-full rounded-lg object-cover border border-gray-200">
                @else
                    <div class="flex h-32 w-full items-center justify-center rounded-lg bg-gray-100 text-gray-400">No Image</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
