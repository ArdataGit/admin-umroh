@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Jamaah Umroh" :breadcrumbs="[
    ['label' => 'Bonus Agent', 'url' => route('bonus-agent.index')],
    ['label' => $agent->nama_agent, 'url' => route('bonus-agent.show', $agent->id)],
    ['label' => 'Jamaah Umroh', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 space-y-6">
        
        <!-- Header Card -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $agent->nama_agent }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kode Agent: {{ $agent->kode_agent }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Jamaah Umroh</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $jamaahList->count() }} Pax</p>
                </div>
            </div>
        </div>

        <!-- Jamaah List Table -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Jamaah Umroh</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">No</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Kode Jamaah</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Nama Jamaah</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Kontak Jamaah</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Program Keberangkatan</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Tagihan</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Status</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Nama Agent</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Bonus Agent</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($jamaahList as $index => $jamaah)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $jamaah->jamaah->kode_jamaah ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $jamaah->jamaah->nama_jamaah ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $jamaah->jamaah->kontak_jamaah ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $jamaah->jamaah->alamat_jamaah ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $jamaah->keberangkatanUmroh->paketUmroh->nama_paket ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-red-600">
                                    Rp {{ number_format($jamaah->sisa_tagihan, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($jamaah->sisa_tagihan == 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $agent->nama_agent }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-blue-600">
                                    Rp {{ number_format($jamaah->bonus_agent, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada jamaah umroh
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
