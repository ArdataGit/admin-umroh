@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Statistik Penjualan Pelanggan" :breadcrumbs="[
    ['label' => 'Statistik', 'url' => '#'],
    ['label' => 'Penjualan Pelanggan', 'url' => '#']
]" />

<div class="space-y-6">
    <!-- Quick Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 rounded-xl border border-gray-200 bg-white p-2 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <a href="{{ route('statistik.penjualan-pelanggan', ['range' => 'all']) }}" 
           class="rounded-lg px-4 py-2 text-sm font-medium transition {{ $filters['range'] == 'all' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
            Semua
        </a>
        <a href="{{ route('statistik.penjualan-pelanggan', ['range' => 'today']) }}" 
           class="rounded-lg px-4 py-2 text-sm font-medium transition {{ $filters['range'] == 'today' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
            Hari Ini
        </a>
        <a href="{{ route('statistik.penjualan-pelanggan', ['range' => 'month']) }}" 
           class="rounded-lg px-4 py-2 text-sm font-medium transition {{ $filters['range'] == 'month' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
            Bulan Ini
        </a>
        <a href="{{ route('statistik.penjualan-pelanggan', ['range' => 'year']) }}" 
           class="rounded-lg px-4 py-2 text-sm font-medium transition {{ $filters['range'] == 'year' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
            Tahun Ini
        </a>
    </div>

    <!-- Summary Stats -->
    <!-- <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Keseluruhan Nominal</h4>
                    <p class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">Rp {{ number_format($grandTotal ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-lg bg-green-50 p-3 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="text-xs font-medium text-blue-600">Berdasarkan filter aktif</span>
            </div>
        </div>
    </div> -->

    <!-- Data List -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white">List Penjualan per Pelanggan (Nominal)</h3>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-800">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-4">No</th>
                        <th scope="col" class="px-6 py-4">Nama Pelanggan</th>
                        <th scope="col" class="px-6 py-4 text-right">Total Nominal (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($salesData as $index => $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $item->nama_pelanggan }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">
                                Rp {{ number_format($item->total_nominal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span>Data tidak ditemukan dengan kriteria filter tersebut</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
