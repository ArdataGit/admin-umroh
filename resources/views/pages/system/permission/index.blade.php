@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Permission Management" :breadcrumbs="[['label' => 'System', 'url' => '#'], ['label' => 'Permission', 'url' => '#']]" />

    <div class="flex flex-col items-center justify-center rounded-2xl border border-gray-200 bg-white p-12 dark:border-gray-800 dark:bg-white/[0.03] lg:p-20">
        <div class="mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-500/10">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2V6M12 18V22M6 12H2M22 12H18M19.07 4.93L16.24 7.76M7.76 16.24L4.93 19.07M19.07 19.07L16.24 16.24M7.76 7.76L4.93 4.93" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h2 class="mb-2 text-2xl font-bold text-gray-800 dark:text-white/90">Masih Dalam Pengembangan</h2>
        <p class="text-center text-gray-500 dark:text-gray-400 max-w-md">
            Halaman Manajemen Permission saat ini sedang dalam tahap pengembangan. Silakan kembali lagi nanti untuk fitur lengkapnya.
        </p>
        <div class="mt-8">
            <a href="{{ route('dashboard.index') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-6 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
@endsection
