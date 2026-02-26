@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="History Action" :breadcrumbs="[
    ['label' => 'History Action', 'url' => '#']
]" />

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Daftar History Action</h3>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-800">
        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-4">No</th>
                    <th scope="col" class="px-6 py-4">Waktu</th>
                    <th scope="col" class="px-6 py-4">User</th>
                    <th scope="col" class="px-6 py-4">Menu</th>
                    <th scope="col" class="px-6 py-4">Action</th>
                    <th scope="col" class="px-6 py-4">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse($historyActions as $index => $history)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $history->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                {{ $history->user->name ?? 'System/Deleted User' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800 dark:text-gray-200">
                            {{ $history->menu }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $actionColors = [
                                    'create' => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                    'update' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'delete' => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    'login' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
                                ];
                                $actionKey = strtolower($history->action);
                                $colorClass = $actionColors[$actionKey] ?? 'bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-400';
                            @endphp
                            <span class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-xs font-medium uppercase {{ $colorClass }}">
                                {{ $history->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4" title="{{ $history->keterangan }}">
                            {{ $history->keterangan ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <span>Belum ada data history action</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
