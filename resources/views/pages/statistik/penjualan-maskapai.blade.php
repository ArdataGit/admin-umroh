@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Statistik Penjualan Maskapai" :breadcrumbs="[
    ['label' => 'Statistik', 'url' => '#'],
    ['label' => 'Penjualan Maskapai', 'url' => '#']
]" />

<div class="space-y-6">
    <!-- Quick Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 rounded-xl border border-gray-200 bg-white p-2 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <a href="{{ route('statistik.penjualan-maskapai', ['range' => 'all']) }}" 
           class="rounded-lg px-4 py-2 text-sm font-medium transition {{ $filters['range'] == 'all' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
            Semua
        </a>
        <a href="{{ route('statistik.penjualan-maskapai', ['range' => 'today']) }}" 
           class="rounded-lg px-4 py-2 text-sm font-medium transition {{ $filters['range'] == 'today' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
            Hari Ini
        </a>
        <a href="{{ route('statistik.penjualan-maskapai', ['range' => 'month']) }}" 
           class="rounded-lg px-4 py-2 text-sm font-medium transition {{ $filters['range'] == 'month' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
            Bulan Ini
        </a>
        <a href="{{ route('statistik.penjualan-maskapai', ['range' => 'year']) }}" 
           class="rounded-lg px-4 py-2 text-sm font-medium transition {{ $filters['range'] == 'year' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
            Tahun Ini
        </a>
    </div>

    <!-- Filter Section (Commented by user) -->
    <!-- <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <form action="{{ route('statistik.penjualan-maskapai') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <input type="hidden" name="range" value="{{ $filters['range'] }}">
            
            <div class="flex-1 min-w-[150px]">
                <label for="day" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Hari (Tanggal)</label>
                <select name="day" id="day" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-gray-800 outline-none transition focus:border-blue-500 dark:border-gray-700 dark:text-white">
                    <option value="">Semua Hari</option>
                    @for($i = 1; $i <= 31; $i++)
                        <option value="{{ $i }}" {{ ($filters['day'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="flex-1 min-w-[150px]">
                <label for="month" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Bulan</label>
                <select name="month" id="month" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-gray-800 outline-none transition focus:border-blue-500 dark:border-gray-700 dark:text-white">
                    <option value="">Semua Bulan</option>
                    @php
                        $months = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @foreach($months as $key => $name)
                        <option value="{{ $key }}" {{ ($filters['month'] ?? '') == $key ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[150px]">
                <label for="year" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tahun</label>
                <select name="year" id="year" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-gray-800 outline-none transition focus:border-blue-500 dark:border-gray-700 dark:text-white">
                    <option value="">Semua Tahun</option>
                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ ($filters['year'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-6 py-2.5 text-center font-medium text-white hover:bg-blue-700 transition">
                    Filter
                </button>
                <a href="{{ route('statistik.penjualan-maskapai') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-center font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 transition">
                    Reset
                </a>
            </div>
        </form>
    </div> -->

    <!-- Data List -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white">List Penjualan per Maskapai (Nominal)</h3>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-800">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-4">No</th>
                        <th scope="col" class="px-6 py-4">Nama Maskapai</th>
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
                                    {{ $item->nama_maskapai }}
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
