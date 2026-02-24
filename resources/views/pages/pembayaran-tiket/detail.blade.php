@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Pembayaran Tiket" :breadcrumbs="[
    ['label' => 'Pembayaran Tiket', 'url' => route('pembayaran-tiket.index')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Detail Pembayaran - {{ $pembayaran->kode_transaksi }}</h3>
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                        Tiket: {{ $pembayaran->transaksiTiket->details->map(fn($d) => $d->ticket->nama_tiket ?? '-')->unique()->implode(', ') }}
                    </p>
                    <p class="text-xs text-gray-500">Transaksi Tiket: {{ $pembayaran->transaksiTiket->kode_transaksi }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('pembayaran-tiket.edit', $pembayaran->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Edit</a>
                    <a href="{{ route('pembayaran-tiket.show', $pembayaran->transaksi_tiket_id) }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Kembali</a>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Pelanggan:</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $pembayaran->transaksiTiket->pelanggan->nama_pelanggan ?? 'Umum' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Tanggal Pembayaran:</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ date('d F Y', strtotime($pembayaran->tanggal_pembayaran)) }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Jumlah Pembayaran:</span>
                            <span class="text-lg font-bold text-blue-600">Rp {{ number_format($pembayaran->jumlah_pembayaran, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Metode Pembayaran:</span>
                            <span class="font-medium text-gray-800 dark:text-white capitalize">{{ $pembayaran->metode_pembayaran }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                {{ $pembayaran->status_pembayaran }}
                            </span>
                        </div>
                         <div class="flex justify-between border-b pb-2 dark:border-gray-800">
                            <span class="text-gray-500">Kode Referensi:</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $pembayaran->kode_referensi ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Catatan:</h4>
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg text-gray-700 dark:text-gray-300 italic mb-4">
                            {{ $pembayaran->catatan ?? 'Tidak ada catatan.' }}
                        </div>

                        @if($pembayaran->bukti_pembayaran)
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Bukti Pembayaran:</h4>
                            <div>
                                @php
                                    $extension = pathinfo($pembayaran->bukti_pembayaran, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp
                                @if($isImage)
                                    <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="h-48 rounded-lg border border-gray-200 shadow-sm dark:border-gray-700 hover:opacity-90 transition-opacity">
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Unduh / Lihat File Bukti ({{ strtoupper($extension) }})
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
