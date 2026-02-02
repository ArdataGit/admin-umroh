@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <x-common.page-breadcrumb pageTitle="Data Pendaftaran Haji" :breadcrumbs="[
        ['label' => 'Pendaftaran Haji', 'url' => '#']
    ]" />

    <div>
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
        </div>
        @endif

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 border-b border-gray-200 pb-4 dark:border-gray-700 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">List Pendaftaran Haji</h3>
                <a href="{{ route('pendaftaran-haji.create') }}" class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600">
                    + Tambah Pendaftaran
                </a>
            </div>
            
            <div class="overflow-x-auto mt-4">
                 <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Tanggal Registrasi</th>
                            <th class="px-6 py-3">Kode Registrasi</th>
                            <th class="px-6 py-3">NIK Jamaah</th>
                            <th class="px-6 py-3">Nama Jamaah</th>
                            <th class="px-6 py-3">Jenis Kelamin</th>
                            <th class="px-6 py-3">Tanggal Lahir</th>
                            <th class="px-6 py-3">Kota / Kabupaten</th>
                            <th class="px-6 py-3">Nama Keberangkatan</th>
                            <th class="px-6 py-3 text-center">Jumlah Jamaah (Pax)</th>
                            <th class="px-6 py-3">Nama Agent</th>
                            <th class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftarans as $index => $item)
                        <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 font-medium text-blue-600">{{ $item->jamaah->kode_jamaah }}</td>
                            <td class="px-6 py-4">{{ $item->jamaah->nik_jamaah }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">{{ $item->jamaah->nama_jamaah }}</td>
                            <td class="px-6 py-4">{{ $item->jamaah->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td class="px-6 py-4">{{ $item->jamaah->tanggal_lahir ? \Carbon\Carbon::parse($item->jamaah->tanggal_lahir)->format('d/m/Y') : '-' }}</td>
                            <td class="px-6 py-4">{{ $item->jamaah->kabupaten_kota }}</td>
                            <td class="px-6 py-4">
                                <span class="block font-medium">{{ $item->keberangkatanHaji->nama_keberangkatan }}</span>
                                <span class="text-xs text-gray-400">{{ $item->keberangkatanHaji->kode_keberangkatan }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">{{ $item->jumlah_jamaah }}</td>
                            <td class="px-6 py-4">{{ $item->agent->nama_agent ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="#" class="text-gray-500 hover:text-gray-700" title="Lihat">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                            <td colspan="12" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data pendaftaran haji.
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
