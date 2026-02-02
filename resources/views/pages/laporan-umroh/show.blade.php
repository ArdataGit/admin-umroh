@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Manifest Keberangkatan" :breadcrumbs="[
    ['label' => 'Laporan Umroh', 'url' => route('laporan-umroh.index')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <!-- Keberangkatan Info Card -->
        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
             <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                <div>
                     <p class="text-sm text-gray-500 dark:text-gray-400">Nama Paket</p>
                     <p class="font-semibold text-gray-800 dark:text-white">{{ $keberangkatan->paketUmroh->nama_paket ?? '-' }}</p>
                </div>
                <div>
                     <p class="text-sm text-gray-500 dark:text-gray-400">Nama Keberangkatan</p>
                     <p class="font-semibold text-gray-800 dark:text-white">{{ $keberangkatan->nama_keberangkatan }}</p>
                </div>
                <div>
                     <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Berangkat</p>
                     <p class="font-semibold text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($keberangkatan->tanggal_keberangkatan)->format('d F Y') }}</p>
                </div>
                 <div>
                     <p class="text-sm text-gray-500 dark:text-gray-400">Total Jamaah</p>
                     <p class="font-semibold text-gray-800 dark:text-white">{{ $keberangkatan->customerUmrohs->count() }} Orang</p>
                </div>
             </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="w-full min-w-[1000px]">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Foto</p>
                            </th>
                            <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama Jamaah</p>
                            </th>
                            <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tgl Daftar</p>
                            </th>
                            <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No. Paspor</p>
                            </th>
                            <th class="px-4 py-3 text-center">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">L/P</p>
                            </th>
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tmp Lahir</p>
                            </th>
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tgl Lahir</p>
                            </th>
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tempat Keluar Paspor</p>
                            </th>
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Paspor Berlaku s/d</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keberangkatan->customerUmrohs as $customer)
                            <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-4">
                                     <div class="h-10 w-10 overflow-hidden rounded-full border border-gray-200 dark:border-gray-700">
                                        @if($customer->jamaah->foto_jamaah)
                                            <img src="{{ Storage::url($customer->jamaah->foto_jamaah) }}" alt="Foto" class="h-full w-full object-cover"/>
                                        @else
                                             <div class="flex h-full w-full items-center justify-center bg-gray-100 text-xs text-gray-500 dark:bg-gray-800">
                                                N/A
                                             </div>
                                        @endif
                                     </div>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">{{ $customer->jamaah->nama_jamaah }}</p>
                                     <p class="text-xs text-gray-500">{{ $customer->jamaah->nik_jamaah }}</p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $customer->created_at->format('d M Y') }}</p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="font-medium text-blue-600 text-theme-sm">{{ $customer->jamaah->nomor_paspor ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                     <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $customer->jamaah->jenis_kelamin == 'L' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-pink-50 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400' }}">
                                        {{ $customer->jamaah->jenis_kelamin }}
                                     </span>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $customer->jamaah->tempat_lahir }}</p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ \Carbon\Carbon::parse($customer->jamaah->tanggal_lahir)->format('d M Y') }}</p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $customer->jamaah->kantor_imigrasi ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $customer->jamaah->tgl_paspor_expired ? \Carbon\Carbon::parse($customer->jamaah->tgl_paspor_expired)->format('d M Y') : '-' }}
                                     </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada jamaah terdaftar pada keberangkatan ini.
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
