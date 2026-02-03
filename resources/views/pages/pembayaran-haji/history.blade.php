@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Riwayat Pembayaran Haji" :breadcrumbs="[
    ['label' => 'Pembayaran Haji', 'url' => route('pembayaran-haji.index')],
    ['label' => 'History', 'url' => '#']
]" />

@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/20 dark:text-green-400">
    <div class="flex items-center gap-2">
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

<div class="space-y-6">
    <!-- Header Info -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
            <div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $customerHaji->jamaah->nama_jamaah }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Paket: {{ $customerHaji->keberangkatanHaji->paketHaji->nama_paket ?? '-' }} | 
                    Keberangkatan: {{ $customerHaji->keberangkatanHaji->nama_keberangkatan }}
                </p>
            </div>
            <div class="flex flex-col items-end">
                 <div class="text-sm text-gray-500">Total Tagihan</div>
                 <div class="text-lg font-bold text-gray-800 dark:text-white">Rp {{ number_format($customerHaji->total_tagihan, 0, ',', '.') }}</div>
                 <div class="text-sm {{ $customerHaji->sisa_tagihan > 0 ? 'text-red-600' : 'text-green-600' }} font-medium">
                    Sisa: Rp {{ number_format($customerHaji->sisa_tagihan, 0, ',', '.') }}
                 </div>
            </div>
        </div>
    </div>

    <!-- Payment List -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-800">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Transaksi</h4>
            <a href="{{ route('pembayaran-haji.create-payment', $customerHaji->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                + Tambah Pembayaran
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                         <th class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">Tanggal</th>
                         <th class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">Kode Transaksi</th>
                         <th class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">Metode</th>
                         <th class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">Jumlah</th>
                         <th class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400">Status</th>
                         <th class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($pembayarans as $pembayaran)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-6 py-4 text-gray-800 dark:text-white">
                            {{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-blue-600 font-medium">
                            {{ $pembayaran->kode_transaksi }}
                        </td>
                        <td class="px-6 py-4 text-gray-800 dark:text-white">
                            {{ ucfirst($pembayaran->metode_pembayaran) }}
                        </td>
                         <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">
                            Rp {{ number_format($pembayaran->jumlah_pembayaran, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium 
                                @if($pembayaran->status_pembayaran == 'paid' || $pembayaran->status_pembayaran == 'checked') bg-green-100 text-green-700
                                @elseif($pembayaran->status_pembayaran == 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($pembayaran->status_pembayaran) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                             <a href="{{ route('pembayaran-haji.detail', $pembayaran->id) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Detail">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                             </a>
                             <a href="{{ route('pembayaran-haji.edit', $pembayaran->id) }}" class="text-gray-600 hover:text-gray-800" title="Edit">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                             </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
