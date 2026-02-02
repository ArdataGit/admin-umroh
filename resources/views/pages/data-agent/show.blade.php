@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Data Agent" :breadcrumbs="[
    ['label' => 'Data Agent', 'url' => route('data-agent')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Informasi Agent</h3>
                <div class="flex gap-2">
                    <a href="{{ route('data-agent.edit', $agent->id) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                        Edit
                    </a>
                    <a href="{{ route('data-agent') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                        Kembali
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kode Agent</label>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $agent->kode_agent }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">NIK Agent</label>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $agent->nik_agent }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Agent</label>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $agent->nama_agent }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kontak Agent</label>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $agent->kontak_agent }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email Agent</label>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $agent->email_agent }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status Agent</label>
                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $agent->status_agent === 'Active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                            {{ $agent->status_agent }}
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                     <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kabupaten/Kota</label>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $agent->kabupaten_kota }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin</label>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $agent->jenis_kelamin }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tempat, Tanggal Lahir</label>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $agent->tempat_lahir }}, {{ $agent->tanggal_lahir }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Komisi Paket Umroh</label>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">Rp {{ number_format($agent->komisi_paket_umroh, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Komisi Paket Haji</label>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">Rp {{ number_format($agent->komisi_paket_haji, 0, ',', '.') }}</p>
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Agent</label>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $agent->alamat_agent }}</p>
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Catatan Agent</label>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-white/90">{{ $agent->catatan_agent ?? '-' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
