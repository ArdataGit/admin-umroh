@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Paket Haji" :breadcrumbs="[
    ['label' => 'Data Paket', 'url' => route('paket-haji')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-700">
        <div>
             <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $paketHaji->nama_paket }}</h2>
             <p class="text-sm text-gray-500">{{ $paketHaji->kode_paket }}</p>
        </div>
       
        <div class="flex gap-2">
            <a href="{{ route('paket-haji') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50">Kembali</a>
            <a href="{{ route('paket-haji.edit', $paketHaji->id) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
        <div>
            <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white">Informasi Penerbangan</h3>
            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Tanggal Keberangkatan</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $paketHaji->tanggal_keberangkatan }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Jumlah Hari</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $paketHaji->jumlah_hari }} Hari</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Maskapai</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $paketHaji->maskapai->nama_maskapai }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Rute</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white uppercase">{{ $paketHaji->rute_penerbangan }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Lokasi Keberangkatan</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $paketHaji->lokasi_keberangkatan }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Status</span>
                    <span class="px-2 py-1 rounded text-xs font-semibold uppercase {{ $paketHaji->status_paket == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $paketHaji->status_paket }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Kuota</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $paketHaji->kuota_jamaah }} Pax</span>
                </div>
            </div>
        </div>

        <div>
             <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white">Brosur Paket</h3>
             @if($paketHaji->foto_brosur)
                <img src="{{ asset('storage/' . $paketHaji->foto_brosur) }}" class="w-full rounded-lg border border-gray-200">
             @else
                <div class="flex h-48 w-full items-center justify-center rounded-lg bg-gray-100 text-gray-400">No Brochure</div>
             @endif
        </div>
    </div>

    <!-- Variants -->
    <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Varian 1 -->
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
            <h4 class="mb-3 text-lg font-semibold text-blue-600">{{ $paketHaji->jenis_paket_1 }}</h4>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Hotel Mekkah:</span> {{ $paketHaji->hotelMekkah1->nama_hotel }}</p>
                <p><span class="text-gray-500">Hotel Madinah:</span> {{ $paketHaji->hotelMadinah1->nama_hotel }}</p>
                @if($paketHaji->hotelTransit1)
                    <p><span class="text-gray-500">Hotel Transit:</span> {{ $paketHaji->hotelTransit1->nama_hotel }}</p>
                @endif
                <div class="mt-4 space-y-1 border-t border-gray-100 pt-2 dark:border-gray-800">
                    <div class="flex justify-between"><span>Quad:</span> <span class="font-semibold">Rp {{ number_format($paketHaji->harga_quad_1, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Triple:</span> <span class="font-semibold">Rp {{ number_format($paketHaji->harga_triple_1, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Double:</span> <span class="font-semibold">Rp {{ number_format($paketHaji->harga_double_1, 0, ',', '.') }}</span></div>
                </div>
            </div>
        </div>

        <!-- Varian 2 -->
        @if($paketHaji->jenis_paket_2)
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
            <h4 class="mb-3 text-lg font-semibold text-blue-600">{{ $paketHaji->jenis_paket_2 }}</h4>
             <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Hotel Mekkah:</span> {{ $paketHaji->hotelMekkah2->nama_hotel ?? '-' }}</p>
                <p><span class="text-gray-500">Hotel Madinah:</span> {{ $paketHaji->hotelMadinah2->nama_hotel ?? '-' }}</p>
                @if($paketHaji->hotelTransit2)
                    <p><span class="text-gray-500">Hotel Transit:</span> {{ $paketHaji->hotelTransit2->nama_hotel }}</p>
                @endif
                <div class="mt-4 space-y-1 border-t border-gray-100 pt-2 dark:border-gray-800">
                    <div class="flex justify-between"><span>Quad:</span> <span class="font-semibold">Rp {{ number_format($paketHaji->harga_quad_2, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Triple:</span> <span class="font-semibold">Rp {{ number_format($paketHaji->harga_triple_2, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Double:</span> <span class="font-semibold">Rp {{ number_format($paketHaji->harga_double_2, 0, ',', '.') }}</span></div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Details Text -->
    <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2">
        <div>
            <h4 class="mb-2 font-semibold text-gray-800 dark:text-white">Termasuk Paket</h4>
            <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-300 whitespace-pre-line">{{ $paketHaji->termasuk_paket }}</div>
        </div>
         <div>
            <h4 class="mb-2 font-semibold text-gray-800 dark:text-white">Tidak Termasuk Paket</h4>
            <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-300 whitespace-pre-line">{{ $paketHaji->tidak_termasuk_paket }}</div>
        </div>
         <div>
            <h4 class="mb-2 font-semibold text-gray-800 dark:text-white">Syarat & Ketentuan</h4>
            <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-300 whitespace-pre-line">{{ $paketHaji->syarat_ketentuan }}</div>
        </div>
         <div>
            <h4 class="mb-2 font-semibold text-gray-800 dark:text-white">Catatan</h4>
            <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-300 whitespace-pre-line">{{ $paketHaji->catatan_paket }}</div>
        </div>
    </div>

</div>
@endsection
