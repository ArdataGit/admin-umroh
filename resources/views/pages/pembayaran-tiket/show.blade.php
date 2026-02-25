@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Riwayat Pembayaran Tiket" :breadcrumbs="[
    ['label' => 'Pembayaran Tiket', 'url' => route('pembayaran-tiket.index')],
    ['label' => 'History', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <!-- Transaction Details -->
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Informasi Transaksi</h3>
            </div>
            <div class="p-6">
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Kode Transaksi</p>
                        <h4 class="text-base font-medium text-gray-800 dark:text-white">{{ $transaksi->kode_transaksi }}</h4>
                    </div>
                     <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Tanggal Transaksi</p>
                        <h4 class="text-base font-medium text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d F Y') }}</h4>
                    </div>
                     <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Nama Mitra</p>
                        <h4 class="text-base font-medium text-gray-800 dark:text-white">{{ $transaksi->pelanggan->nama_pelanggan ?? '-' }}</h4>
                    </div>
                     <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Tagihan</p>
                        <h4 class="text-base font-medium text-gray-800 dark:text-white">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</h4>
                    </div>
                     <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Terbayar</p>
                        <h4 class="text-base font-medium text-green-600">Rp {{ number_format($pembayarans->sum('jumlah_pembayaran'), 0, ',', '.') }}</h4>
                    </div>
                     <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Sisa Pembayaran</p>
                        @php
                            $sisa = $transaksi->total_transaksi - $pembayarans->sum('jumlah_pembayaran');
                        @endphp
                        <h4 class="text-base font-medium {{ $sisa > 0 ? 'text-red-500' : 'text-gray-800 dark:text-white' }}">
                            Rp {{ number_format($sisa, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment List -->
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-800">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Riwayat Pembayaran</h4>
                <a href="{{ route('pembayaran-tiket.create-payment', $transaksi->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                    + Tambah Pembayaran
                </a>
            </div>
            
            <div class="overflow-x-auto">
                 <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800 text-left bg-gray-50 dark:bg-gray-800/50">
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Kode Pembayaran</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Metode</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Bukti</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($pembayarans as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white font-medium">{{ $payment->kode_transaksi }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->translatedFormat('d F Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 capitalize">{{ $payment->metode_pembayaran == 'cash' ? 'Cash' : $payment->metode_pembayaran }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white font-medium">Rp {{ number_format($payment->jumlah_pembayaran, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($payment->bukti_pembayaran)
                                    <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $payment->catatan ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('pembayaran-tiket.detail', $payment->id) }}" class="text-gray-500 hover:text-gray-700" title="Lihat">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('pembayaran-tiket.edit', $payment->id) }}" class="text-blue-500 hover:text-blue-700" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('pembayaran-tiket.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pembayaran ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada riwayat pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
