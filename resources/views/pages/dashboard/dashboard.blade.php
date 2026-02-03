@extends('layouts.app')

@section('content')
  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <!-- Header Section -->
    <div class="col-span-12">
      <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Data Dashboard</h2>
      <hr class="mt-4 border-gray-200 dark:border-gray-800">
    </div>

    <!-- Metrics Section -->
    <div class="col-span-12">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 md:gap-6">
        
        <!-- Total Transaksi Haji -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-xl dark:bg-blue-900/20">
            <svg class="fill-blue-600 dark:fill-blue-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M20 7h-4V5l-2-2h-4L8 5v2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 5h4v2h-4V5zm10 15H4V9h16v11z"/>
            </svg>
          </div>
          <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Total Transaksi Haji</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ number_format($totalTransaksiHaji) }}</h4>
          </div>
        </div>

        <!-- Total Transaksi Umroh -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-xl dark:bg-green-900/20">
            <svg class="fill-green-600 dark:fill-green-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M20 7h-4V5l-2-2h-4L8 5v2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 5h4v2h-4V5zm10 15H4V9h16v11z"/>
            </svg>
          </div>
          <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Total Transaksi Umroh</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ number_format($totalTransaksiUmroh) }}</h4>
          </div>
        </div>

        <!-- Total Jamaah Haji -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-xl dark:bg-purple-900/20">
            <svg class="fill-purple-600 dark:fill-purple-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
          </div>
          <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Total Jamaah Haji</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ number_format($totalJamaahHaji) }}</h4>
          </div>
        </div>

        <!-- Total Jamaah Umroh -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-teal-100 rounded-xl dark:bg-teal-900/20">
            <svg class="fill-teal-600 dark:fill-teal-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
          </div>
          <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Total Jamaah Umroh</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ number_format($totalJamaahUmroh) }}</h4>
          </div>
        </div>

        <!-- Sudah Pembayaran Haji -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-emerald-100 rounded-xl dark:bg-emerald-900/20">
            <svg class="fill-emerald-600 dark:fill-emerald-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
            </svg>
          </div>
          <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Sudah Pembayaran Haji</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ number_format($sudahBayarHaji) }}</h4>
          </div>
        </div>

        <!-- Sudah Pembayaran Umroh -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-lime-100 rounded-xl dark:bg-lime-900/20">
            <svg class="fill-lime-600 dark:fill-lime-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
            </svg>
          </div>
          <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Sudah Pembayaran Umroh</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ number_format($sudahBayarUmroh) }}</h4>
          </div>
        </div>

        <!-- Sisa Tagihan Haji -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-xl dark:bg-orange-900/20">
            <svg class="fill-orange-600 dark:fill-orange-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
            </svg>
          </div>
          <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Sisa Tagihan Haji</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ number_format($sisaTagihanHaji) }}</h4>
          </div>
        </div>

        <!-- Sisa Tagihan Umroh -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-amber-100 rounded-xl dark:bg-amber-900/20">
            <svg class="fill-amber-600 dark:fill-amber-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
            </svg>
          </div>
          <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Sisa Tagihan Umroh</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ number_format($sisaTagihanUmroh) }}</h4>
          </div>
        </div>

      </div>


    <!-- Data Tabungan Section -->
    <div class="col-span-12 mt-8">
      <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Data Tabungan</h2>
      <hr class="mt-4 border-gray-200 dark:border-gray-800">
    </div>

    <div class="col-span-12 mt-6">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 md:gap-6">
        
        <!-- Total Tabungan Haji -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-indigo-100 rounded-xl dark:bg-indigo-900/20">
            <svg class="fill-indigo-600 dark:fill-indigo-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
            </svg>
          </div>
          <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Total Tabungan Haji</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">Rp {{ number_format($totalSaldoHaji, 0, ',', '.') }}</h4>
          </div>
        </div>

        <!-- Total Tabungan Umroh -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-cyan-100 rounded-xl dark:bg-cyan-900/20">
            <svg class="fill-cyan-600 dark:fill-cyan-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
            </svg>
          </div>
          <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Total Tabungan Umroh</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">Rp {{ number_format($totalSaldoUmroh, 0, ',', '.') }}</h4>
          </div>
        </div>

        <!-- Rekening Tabungan Aktif Haji -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-rose-100 rounded-xl dark:bg-rose-900/20">
            <svg class="fill-rose-600 dark:fill-rose-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/>
            </svg>
          </div>
          <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Rekening Aktif Haji</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ number_format($rekeningAktifHaji) }}</h4>
          </div>
        </div>

        <!-- Rekening Tabungan Aktif Umroh -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-pink-100 rounded-xl dark:bg-pink-900/20">
            <svg class="fill-pink-600 dark:fill-pink-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/>
            </svg>
          </div>
          <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Rekening Aktif Umroh</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ number_format($rekeningAktifUmroh) }}</h4>
          </div>
        </div>

      </div>
    </div>


    <div class="col-span-12 mt-8">
      <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Data Keberangkatan</h2>
      <hr class="mt-4 border-gray-200 dark:border-gray-800">
    </div>

    <div class="col-span-12 mt-6">
      <h3 class="text-lg font-semibold text-gray-700 dark:text-white/80 mb-4">Tabel : Data Paket Keberangkatan Umroh</h3>
    </div>

    <div class="col-span-12">
      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-[1400px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kode Keberangkatan</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama Keberangkatan</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tanggal Keberangkatan</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Lokasi Keberangkatan</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Pesawat / Maskapai</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Jumlah Hari</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kuota Jamaah</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kuota Terisi</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Sisa Kuota</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status Paket</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($keberangkatanUmroh as $index => $departure)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $index + 1 }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">{{ $departure['code'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-800 text-theme-sm dark:text-white/90">{{ $departure['name'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $departure['date'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $departure['location'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $departure['airline'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $departure['duration'] }} hari</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $departure['quota'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $departure['filled'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-theme-sm {{ $departure['remaining'] === 0 ? 'text-error-600 dark:text-error-500' : 'text-gray-500 dark:text-gray-400' }}">{{ $departure['remaining'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $statusClass = match($departure['status']) {
                                        'Aktif' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                        'Penuh' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                                        'Menunggu' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500',
                                        default => 'bg-gray-50 text-gray-600'
                                    };
                                @endphp
                                <span class="text-theme-xs inline-block rounded-full px-3 py-1 font-medium {{ $statusClass }}">{{ $departure['status'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">Tidak ada data keberangkatan umroh mendatang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>




    <div class="col-span-12 mt-6">
      <h3 class="text-lg font-semibold text-gray-700 dark:text-white/80 mb-4">Tabel : Data Paket Keberangkatan Haji</h3>
    </div>

    <div class="col-span-12">
      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-[1400px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kode Keberangkatan</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama Keberangkatan</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tanggal Keberangkatan</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Lokasi Keberangkatan</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Pesawat / Maskapai</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Jumlah Hari</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kuota Jamaah</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kuota Terisi</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Sisa Kuota</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status Paket</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($keberangkatanHaji as $index => $departure)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $index + 1 }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">{{ $departure['code'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-800 text-theme-sm dark:text-white/90">{{ $departure['name'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $departure['date'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $departure['location'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $departure['airline'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $departure['duration'] }} hari</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $departure['quota'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $departure['filled'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-theme-sm {{ $departure['remaining'] === 0 ? 'text-error-600 dark:text-error-500' : 'text-gray-500 dark:text-gray-400' }}">{{ $departure['remaining'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $statusClass = match($departure['status']) {
                                        'Aktif' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                        'Penuh' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                                        'Menunggu' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500',
                                        default => 'bg-gray-50 text-gray-600'
                                    };
                                @endphp
                                <span class="text-theme-xs inline-block rounded-full px-3 py-1 font-medium {{ $statusClass }}">{{ $departure['status'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">Tidak ada data keberangkatan haji mendatang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>


    <div class="col-span-12 mt-8">
      <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Data Inventory</h2>
      <hr class="mt-4 border-gray-200 dark:border-gray-800">
    </div>

    <div class="col-span-12 mt-6">
      <h3 class="text-lg font-semibold text-gray-700 dark:text-white/80 mb-4">Tabel : Data Paket Perlengkapan Sudah Habis</h3>
    </div>

    <div class="col-span-12">
      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-[1000px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kode Produk</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama Produk</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Harga Beli</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Standar Stok</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aktual Stok</p>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status Stok</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockProducts as $index => $product)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $index + 1 }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">{{ $product['code'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-800 text-theme-sm dark:text-white/90">{{ $product['name'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">Rp {{ number_format($product['purchasePrice'], 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $product['standardStock'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-error-600 dark:text-error-500 text-theme-sm">{{ $product['actualStock'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $stockClass = match($product['status']) {
                                        'Habis' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                                        'Menipis' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500',
                                        'Aman' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                        default => 'bg-gray-50 text-gray-600'
                                    };
                                @endphp
                                <span class="text-theme-xs inline-block rounded-full px-3 py-1 font-medium {{ $stockClass }}">{{ $product['status'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">Tidak ada data produk dengan stok menipis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>



  </div>
@endsection
