@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Paket Umroh" :breadcrumbs="[
    ['label' => 'Data Paket', 'url' => route('paket-umroh')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-700">
        <div>
             <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $paketUmroh->nama_paket }}</h2>
             <p class="text-sm text-gray-500">{{ $paketUmroh->kode_paket }}</p>
        </div>
       
        <div class="flex gap-2">
            <a href="{{ route('paket-umroh') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50">Kembali</a>
            <a href="{{ route('paket-umroh.edit', $paketUmroh->id) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
        <div>
            <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white">Informasi Penerbangan</h3>
            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Tanggal Keberangkatan</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $paketUmroh->tanggal_keberangkatan }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Jumlah Hari</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $paketUmroh->jumlah_hari }} Hari</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Maskapai</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $paketUmroh->maskapai->nama_maskapai }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Rute</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white uppercase">{{ $paketUmroh->rute_penerbangan }}</span>
                </div>
                 <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Lokasi Keberangkatan</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $paketUmroh->lokasi_keberangkatan }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Status</span>
                    <span class="px-2 py-1 rounded text-xs font-semibold uppercase {{ $paketUmroh->status_paket == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $paketUmroh->status_paket }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
                    <span class="text-sm text-gray-500">Kuota</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $paketUmroh->kuota_jamaah }} Pax</span>
                </div>
            </div>
        </div>

        <div>
             <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white">Brosur Paket</h3>
             @if($paketUmroh->foto_brosur)
                <img src="{{ asset('storage/' . $paketUmroh->foto_brosur) }}" class="w-full rounded-lg border border-gray-200">
             @else
                <div class="flex h-48 w-full items-center justify-center rounded-lg bg-gray-100 text-gray-400">No Brochure</div>
             @endif
        </div>
    </div>

    <!-- Variants -->
    <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Varian 1 -->
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
            <h4 class="mb-3 text-lg font-semibold text-blue-600">{{ $paketUmroh->jenis_paket_1 }}</h4>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Hotel Mekkah:</span> {{ $paketUmroh->hotelMekkah1->nama_hotel }} ({{ $paketUmroh->hari_mekkah_1 }} Hari)</p>
                <p><span class="text-gray-500">Hotel Madinah:</span> {{ $paketUmroh->hotelMadinah1->nama_hotel }} ({{ $paketUmroh->hari_madinah_1 }} Hari)</p>
                @if($paketUmroh->hotelTransit1)
                    <p><span class="text-gray-500">Hotel Transit:</span> {{ $paketUmroh->hotelTransit1->nama_hotel }} ({{ $paketUmroh->hari_transit_1 }} Hari)</p>
                @endif
                <div class="mt-4 border-t border-gray-100 pt-2 dark:border-gray-800">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs text-gray-500">General HPP:</span>
                        <span class="text-sm font-semibold">Rp {{ number_format($paketUmroh->harga_hpp_1, 0, ',', '.') }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-1 mt-2">
                        <div class="text-[10px] uppercase text-gray-400 font-bold col-start-1">Tipe Kamar</div>
                        <div class="text-[10px] uppercase text-gray-400 font-bold text-right">HPP / Harga Jual</div>
                        
                        <div class="text-xs text-gray-600 dark:text-gray-400">Quad</div>
                        <div class="text-xs text-right">
                           <span class="text-gray-400 italic">Rp {{ number_format($paketUmroh->hpp_quad1, 0, ',', '.') }}</span> / 
                           <span class="font-semibold text-gray-800 dark:text-white">Rp {{ number_format($paketUmroh->harga_quad_1, 0, ',', '.') }}</span>
                        </div>

                        <div class="text-xs text-gray-600 dark:text-gray-400">Triple</div>
                        <div class="text-xs text-right">
                           <span class="text-gray-400 italic">Rp {{ number_format($paketUmroh->hpp_triple1, 0, ',', '.') }}</span> / 
                           <span class="font-semibold text-gray-800 dark:text-white">Rp {{ number_format($paketUmroh->harga_triple_1, 0, ',', '.') }}</span>
                        </div>

                        <div class="text-xs text-gray-600 dark:text-gray-400">Double</div>
                        <div class="text-xs text-right">
                           <span class="text-gray-400 italic">Rp {{ number_format($paketUmroh->hpp_double1, 0, ',', '.') }}</span> / 
                           <span class="font-semibold text-gray-800 dark:text-white">Rp {{ number_format($paketUmroh->harga_double_1, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Varian 2 -->
        @if($paketUmroh->jenis_paket_2)
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
            <h4 class="mb-3 text-lg font-semibold text-blue-600">{{ $paketUmroh->jenis_paket_2 }}</h4>
             <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Hotel Mekkah:</span> {{ $paketUmroh->hotelMekkah2->nama_hotel ?? '-' }} @if($paketUmroh->hotel_mekkah_2) ({{ $paketUmroh->hari_mekkah_2 }} Hari) @endif</p>
                <p><span class="text-gray-500">Hotel Madinah:</span> {{ $paketUmroh->hotelMadinah2->nama_hotel ?? '-' }} @if($paketUmroh->hotel_madinah_2) ({{ $paketUmroh->hari_madinah_2 }} Hari) @endif</p>
                @if($paketUmroh->hotelTransit2)
                    <p><span class="text-gray-500">Hotel Transit:</span> {{ $paketUmroh->hotelTransit2->nama_hotel }} ({{ $paketUmroh->hari_transit_2 }} Hari)</p>
                @endif
                <div class="mt-4 border-t border-gray-100 pt-2 dark:border-gray-800">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs text-gray-500">General HPP:</span>
                        <span class="text-sm font-semibold">Rp {{ number_format($paketUmroh->harga_hpp_2, 0, ',', '.') }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-1 mt-2">
                        <div class="text-[10px] uppercase text-gray-400 font-bold col-start-1">Tipe Kamar</div>
                        <div class="text-[10px] uppercase text-gray-400 font-bold text-right">HPP / Harga Jual</div>
                        
                        <div class="text-xs text-gray-600 dark:text-gray-400">Quad</div>
                        <div class="text-xs text-right">
                           <span class="text-gray-400 italic">Rp {{ number_format($paketUmroh->hpp_quad2, 0, ',', '.') }}</span> / 
                           <span class="font-semibold text-gray-800 dark:text-white">Rp {{ number_format($paketUmroh->harga_quad_2, 0, ',', '.') }}</span>
                        </div>

                        <div class="text-xs text-gray-600 dark:text-gray-400">Triple</div>
                        <div class="text-xs text-right">
                           <span class="text-gray-400 italic">Rp {{ number_format($paketUmroh->hpp_triple2, 0, ',', '.') }}</span> / 
                           <span class="font-semibold text-gray-800 dark:text-white">Rp {{ number_format($paketUmroh->harga_triple_2, 0, ',', '.') }}</span>
                        </div>

                        <div class="text-xs text-gray-600 dark:text-gray-400">Double</div>
                        <div class="text-xs text-right">
                           <span class="text-gray-400 italic">Rp {{ number_format($paketUmroh->hpp_double2, 0, ',', '.') }}</span> / 
                           <span class="font-semibold text-gray-800 dark:text-white">Rp {{ number_format($paketUmroh->harga_double_2, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Details Text -->
    <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2">
        <div>
            <h4 class="mb-2 font-semibold text-gray-800 dark:text-white">Layanan Tambahan</h4>
            <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                @if($paketUmroh->layanans->count() > 0)
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($paketUmroh->layanans as $layanan)
                            <li>{{ $layanan->nama_layanan }} ({{ $layanan->jenis_layanan }})</li>
                        @endforeach
                    </ul>
                @else
                    <p class="italic text-gray-500">Tidak ada layanan tambahan.</p>
                @endif
            </div>
        </div>
        <div>
            <h4 class="mb-2 font-semibold text-gray-800 dark:text-white">Termasuk Paket</h4>
            <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-300 whitespace-pre-line">{{ $paketUmroh->termasuk_paket }}</div>
        </div>
         <div>
            <h4 class="mb-2 font-semibold text-gray-800 dark:text-white">Tidak Termasuk Paket</h4>
            <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-300 whitespace-pre-line">{{ $paketUmroh->tidak_termasuk_paket }}</div>
        </div>
         <div>
            <h4 class="mb-2 font-semibold text-gray-800 dark:text-white">Syarat & Ketentuan</h4>
            <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-300 whitespace-pre-line">{{ $paketUmroh->syarat_ketentuan }}</div>
        </div>
         <div>
            <h4 class="mb-2 font-semibold text-gray-800 dark:text-white">Catatan</h4>
            <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-300 whitespace-pre-line">{{ $paketUmroh->catatan_paket }}</div>
        </div>
    </div>

</div>
@endsection
