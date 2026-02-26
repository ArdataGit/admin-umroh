@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Riwayat Pembayaran Bonus" :breadcrumbs="[
    ['label' => 'Bonus Agent', 'url' => route('bonus-agent.index')],
    ['label' => 'Riwayat Pembayaran', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 space-y-6">
        
        <!-- Agent Summary Cards -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <!-- Agent Info -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Informasi Agent</h3>
                <div class="space-y-2">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Nama Agent</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $agent->nama_agent }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kode Agent</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $agent->kode_agent }}</p>
                    </div>
                </div>
            </div>

            <!-- Performance -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Performa</h3>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Jamaah Umroh</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                            {{ $agent->umroh_count }} Pax
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Jamaah Haji</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                            {{ $agent->haji_count }} Pax
                        </span>
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Bonus</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Total Bonus</span>
                        <span class="font-semibold text-blue-600">Rp {{ number_format($agent->total_bonus, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Sudah Dibayar</span>
                        <span class="font-semibold text-green-600">Rp {{ number_format($agent->sudah_dibayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-2 border-t border-gray-100 dark:border-gray-800 flex justify-between">
                        <span class="text-xs font-medium text-gray-900 dark:text-white">Sisa Bonus</span>
                        <span class="font-bold {{ $agent->sisa_bonus > 0 ? 'text-red-600' : 'text-gray-500' }}">Rp {{ number_format($agent->sisa_bonus, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History Table -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Pembayaran</h3>
                <div class="flex gap-2">
                    <a href="{{ route('bonus-agent.show', $agent->id) }}" class="flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white shadow-theme-xs hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Pembayaran
                    </a>
                    <a href="{{ route('bonus-agent.print-detail', $agent->id) }}" target="_blank" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print
                    </a>
                    <a href="{{ route('bonus-agent.export-detail', $agent->id) }}" target="_blank" class="flex items-center gap-2 rounded-lg bg-green-600 px-3 py-1.5 text-xs font-medium text-white shadow-theme-xs hover:bg-green-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">No</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pembayaran</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Kode Transaksi</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Nama Agent</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Pembayaran</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Metode Pembayaran</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Kode Referensi</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($agent->bonusPayouts as $index => $payout)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($payout->tanggal_bayar)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">
                                    {{ $payout->kode_transaksi }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $agent->nama_agent }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-green-600">
                                    Rp {{ number_format($payout->jumlah_bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $payout->metode_pembayaran }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($payout->status_pembayaran == 'Confirmed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            {{ $payout->status_pembayaran }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                            {{ $payout->status_pembayaran }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $payout->kode_referensi_mutasi ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        @if($canEdit)
                                        <a href="{{ route('bonus-agent.edit', $payout->id) }}" class="text-gray-500 hover:text-yellow-600 dark:text-gray-400 dark:hover:text-yellow-500" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @endif
                                        @if($payout->bukti_pembayaran)
                                            <a href="{{ asset('storage/' . $payout->bukti_pembayaran) }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Lihat Bukti">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada riwayat pembayaran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
