@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Pengeluaran Umum" :breadcrumbs="[
    ['label' => 'Data Pengeluaran', 'url' => route('pengeluaran-umum.index')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-800">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Informasi Pengeluaran</h3>
                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                    {{ Str::upper(str_replace('_', ' ', $pengeluaran->jenis_pengeluaran)) }}
                </span>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Kode Pengeluaran -->
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Pengeluaran</p>
                    <p class="mt-1 text-base font-medium text-gray-800 dark:text-white">{{ $pengeluaran->kode_pengeluaran }}</p>
                </div>

                <!-- Tanggal Pengeluaran -->
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pengeluaran</p>
                    <p class="mt-1 text-base font-medium text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($pengeluaran->tanggal_pengeluaran)->format('d F Y') }}</p>
                </div>

                <!-- Nama Pengeluaran -->
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Pengeluaran</p>
                    <p class="mt-1 text-base font-medium text-gray-800 dark:text-white">{{ $pengeluaran->nama_pengeluaran }}</p>
                </div>

                <!-- Jumlah Pengeluaran -->
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Pengeluaran</p>
                    <p class="mt-1 text-base font-bold text-gray-800 dark:text-white">Rp {{ number_format($pengeluaran->jumlah_pengeluaran, 0, ',', '.') }}</p>
                </div>

                <!-- Catatan Pengeluaran -->
                <div class="col-span-1 md:col-span-2">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan Pengeluaran</p>
                    <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $pengeluaran->catatan_pengeluaran ?? '-' }}</p>
                </div>

                <!-- Bukti Pengeluaran -->
                <div class="col-span-1 md:col-span-2">
                    <p class="mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">Bukti Pengeluaran</p>
                    @if($pengeluaran->bukti_pengeluaran)
                        <div class="relative h-64 w-full max-w-lg overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                            <img src="{{ asset('storage/' . $pengeluaran->bukti_pengeluaran) }}" alt="Bukti Pengeluaran" class="h-full w-full object-contain">
                        </div>
                        <div class="mt-2">
                             <a href="{{ asset('storage/' . $pengeluaran->bukti_pengeluaran) }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download / Lihat Full Size
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">Tidak ada bukti foto</p>
                    @endif
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6 dark:border-gray-800">
                <form action="{{ route('pengeluaran-umum.destroy', $pengeluaran->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-100 dark:border-red-900/30 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30">
                        Hapus
                    </button>
                </form>
                <a href="{{ route('pengeluaran-umum.edit', $pengeluaran->id) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">
                    Edit Data
                </a>
                 <a href="{{ route('pengeluaran-umum.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
