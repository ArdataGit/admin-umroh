@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Pendaftaran Haji" :breadcrumbs="[
    ['label' => 'Pendaftaran Haji', 'url' => route('pendaftaran-haji.index')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Main Info (Left Column) -->
    <div class="md:col-span-2 space-y-6">
        <!-- Jamaah Personal Info -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-xl font-semibold text-gray-800 dark:text-white border-b pb-2">Data Jamaah</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Kode Jamaah</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $pendaftaran->jamaah->kode_jamaah }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">NIK</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $pendaftaran->jamaah->nik_jamaah }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Nama Lengkap</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $pendaftaran->jamaah->nama_jamaah }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Jenis Kelamin</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $pendaftaran->jamaah->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Tempat, Tanggal Lahir</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">
                        {{ $pendaftaran->jamaah->tempat_lahir }}, {{ \Carbon\Carbon::parse($pendaftaran->jamaah->tanggal_lahir)->format('d F Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">No. Kontak</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $pendaftaran->jamaah->kontak_jamaah }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs text-gray-500 uppercase">Alamat Lengkap</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">
                        {{ $pendaftaran->jamaah->alamat_jamaah }}<br>
                        <span class="text-sm text-gray-500">
                            {{ $pendaftaran->jamaah->alamat_lengkap }}<br>
                            Kec. {{ $pendaftaran->jamaah->kecamatan }}, {{ $pendaftaran->jamaah->kabupaten_kota }}, {{ $pendaftaran->jamaah->provinsi }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Passport Info -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-xl font-semibold text-gray-800 dark:text-white border-b pb-2">Dokumen Paspor</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Nomor Paspor</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $pendaftaran->jamaah->nomor_paspor ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Nama Di Paspor</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $pendaftaran->jamaah->nama_paspor ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Kantor Imigrasi</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $pendaftaran->jamaah->kantor_imigrasi ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Masa Berlaku</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">
                        @if($pendaftaran->jamaah->tgl_paspor_aktif)
                            {{ \Carbon\Carbon::parse($pendaftaran->jamaah->tgl_paspor_aktif)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($pendaftaran->jamaah->tgl_paspor_expired)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Files / Attachments -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-xl font-semibold text-gray-800 dark:text-white border-b pb-2">Lampiran Dokumen</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @foreach([
                    'foto_jamaah' => 'Foto Jamaah',
                    'foto_ktp' => 'Foto KTP',
                    'foto_kk' => 'Foto KK',
                    'foto_paspor_1' => 'Paspor Hal 1',
                    'foto_paspor_2' => 'Paspor Hal 2'
                ] as $field => $label)
                    <div class="border rounded-lg p-2 text-center">
                        <p class="text-xs text-gray-500 mb-2">{{ $label }}</p>
                        @if($pendaftaran->jamaah->$field)
                            <a href="{{ asset('storage/' . $pendaftaran->jamaah->$field) }}" target="_blank">
                                <img src="{{ asset('storage/' . $pendaftaran->jamaah->$field) }}" class="h-24 w-auto mx-auto object-cover rounded hover:opacity-80 transition" alt="{{ $label }}">
                            </a>
                        @else
                            <div class="h-24 w-full bg-gray-100 flex items-center justify-center rounded text-gray-400 text-xs">
                                Tidak ada
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Sidebar Info (Right Column) -->
    <div class="space-y-6">
        <!-- Paket / Keberangkatan -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white border-b pb-2">Detail Keberangkatan</h3>
            
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500">Paket Haji</p>
                    <p class="font-bold text-blue-600">{{ $pendaftaran->keberangkatanHaji->paketHaji->nama_paket ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Jadwal Keberangkatan</p>
                    <p class="font-medium">{{ $pendaftaran->keberangkatanHaji->nama_keberangkatan }}</p>
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($pendaftaran->keberangkatanHaji->tanggal_keberangkatan)->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Agent</p>
                    <p class="font-medium text-gray-800">{{ $pendaftaran->agent->nama_agent ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Tipe Kamar</p>
                    <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10 uppercase">
                        {{ $pendaftaran->tipe_kamar }}
                    </span>
                </div>
                 <div>
                    <p class="text-xs text-gray-500">Jumlah Jamaah</p>
                    <p class="font-medium">{{ $pendaftaran->jumlah_jamaah }} Pax</p>
                </div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white border-b pb-2">Rincian Keuangan</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Metode Bayar</span>
                    <span class="text-sm font-medium uppercase">{{ $pendaftaran->metode_pembayaran }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Harga Paket</span>
                    <span class="text-sm font-medium">Rp {{ number_format($pendaftaran->harga_paket, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-red-500">
                    <span class="text-sm">Diskon</span>
                    <span class="text-sm font-medium">- Rp {{ number_format($pendaftaran->diskon, 0, ',', '.') }}</span>
                </div>
                <div class="border-t pt-2 flex justify-between font-bold">
                    <span class="text-sm text-gray-800">Total Tagihan</span>
                    <span class="text-sm text-blue-600">Rp {{ number_format($pendaftaran->total_tagihan, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Sudah Dibayar</span>
                    <span class="text-sm font-medium text-green-600">Rp {{ number_format($pendaftaran->total_bayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between bg-red-50 p-2 rounded">
                    <span class="text-sm font-bold text-red-800">Sisa Tagihan</span>
                    <span class="text-sm font-bold text-red-600">Rp {{ number_format($pendaftaran->sisa_tagihan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

         <!-- Status Checklist -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white border-b pb-2">Status Proses</h3>
            
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Proses Visa</span>
                    @if($pendaftaran->status_visa)
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Selesai</span>
                    @else
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">Belum</span>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Proses Tiket</span>
                    @if($pendaftaran->status_tiket)
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Selesai</span>
                    @else
                         <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">Belum</span>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Siskopatuh</span>
                     @if($pendaftaran->status_siskopatuh)
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Selesai</span>
                    @else
                         <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">Belum</span>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Perlengkapan</span>
                     @if($pendaftaran->status_perlengkapan)
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Selesai</span>
                    @else
                         <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">Belum</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('pendaftaran-haji.index') }}" class="w-full text-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Kembali
            </a>
            <a href="{{ route('pendaftaran-haji.edit', $pendaftaran->id) }}" class="w-full text-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                Edit Data
            </a>
        </div>

    </div>
</div>
@endsection
