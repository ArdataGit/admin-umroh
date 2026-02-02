@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Data Hotel" :breadcrumbs="[
    ['label' => 'Data Hotel', 'url' => route('data-hotel')]
]" />
    <div class="grid grid-cols-12 gap-4 md:gap-6" x-data="{ showDeleteModal: false }">

        <div class="col-span-12">
            <x-common.component-card title="Detail Hotel">
                <div class="space-y-6">
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Kode Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kode Hotel
                            </label>
                            <p class="text-base font-semibold text-gray-800 dark:text-white/90">{{ $hotel->kode_hotel }}</p>
                        </div>

                        <!-- Nama Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nama Hotel
                            </label>
                            <p class="text-base font-semibold text-gray-800 dark:text-white/90">{{ $hotel->nama_hotel }}</p>
                        </div>

                        <!-- Lokasi Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Lokasi Hotel
                            </label>
                            <p class="text-base text-gray-800 dark:text-white/90">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700 dark:bg-blue-900/20 dark:text-blue-400">
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 2C7.24 2 5 4.24 5 7C5 10.5 10 16 10 16C10 16 15 10.5 15 7C15 4.24 12.76 2 10 2ZM10 9C8.9 9 8 8.1 8 7C8 5.9 8.9 5 10 5C11.1 5 12 5.9 12 7C12 8.1 11.1 9 10 9Z" fill="currentColor"/>
                                    </svg>
                                    {{ $hotel->lokasi_hotel }}
                                </span>
                            </p>
                        </div>

                        <!-- Kontak Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kontak Hotel
                            </label>
                            <p class="text-base text-gray-800 dark:text-white/90">
                                <a href="tel:{{ $hotel->kontak_hotel }}" class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2 3C2 2.44772 2.44772 2 3 2H5.15287C5.64171 2 6.0589 2.35341 6.13927 2.8356L6.87858 7.27147C6.95075 7.70451 6.73206 8.13397 6.3394 8.3303L4.79126 9.10437C5.90756 11.8783 8.12168 14.0924 10.8956 15.2087L11.6697 13.6606C11.866 13.2679 12.2955 13.0492 12.7285 13.1214L17.1644 13.8607C17.6466 13.9411 18 14.3583 18 14.8471V17C18 17.5523 17.5523 18 17 18H15C7.8203 18 2 12.1797 2 5V3Z" fill="currentColor"/>
                                    </svg>
                                    {{ $hotel->kontak_hotel }}
                                </a>
                            </p>
                        </div>

                        <!-- Email Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Email Hotel
                            </label>
                            <p class="text-base text-gray-800 dark:text-white/90">
                                <a href="mailto:{{ $hotel->email_hotel }}" class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" fill="currentColor"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" fill="currentColor"/>
                                    </svg>
                                    {{ $hotel->email_hotel }}
                                </a>
                            </p>
                        </div>

                        <!-- Rating Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Rating Hotel
                            </label>
                            <p class="text-xl text-gray-800 dark:text-white/90">
                                @for($i = 0; $i < $hotel->rating_hotel; $i++)
                                    ⭐
                                @endfor
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">({{ $hotel->rating_hotel }} Bintang)</span>
                            </p>
                        </div>

                        <!-- Harga Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Harga Hotel (per malam)
                            </label>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                Rp {{ number_format($hotel->harga_hotel, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Catatan Hotel -->
                    @if($hotel->catatan_hotel)
                    <div class="border-t border-gray-200 pt-6 dark:border-gray-800">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Catatan Hotel
                        </label>
                        <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800/50">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $hotel->catatan_hotel }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between border-t border-gray-200 pt-6 dark:border-gray-800">
                        <a href="{{ route('data-hotel') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.8334 10H4.16675M4.16675 10L10.0001 15.8333M4.16675 10L10.0001 4.16667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Kembali
                        </a>
                        <div class="flex gap-3">
                            <a href="{{ route('data-hotel.edit', $hotel->id) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.5858 3.58579C14.3668 2.80474 15.6332 2.80474 16.4142 3.58579C17.1953 4.36683 17.1953 5.63316 16.4142 6.41421L15.6213 7.20711L12.7929 4.37868L13.5858 3.58579Z" fill="currentColor"/>
                                    <path d="M11.3787 5.79289L3 14.1716V17H5.82842L14.2071 8.62132L11.3787 5.79289Z" fill="currentColor"/>
                                </svg>
                                Edit Hotel
                            </a>
                            <button @click="showDeleteModal = true" class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-red-600">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9 2C8.62123 2 8.27497 2.214 8.10557 2.55279L7.38197 4H4C3.44772 4 3 4.44772 3 5C3 5.55228 3.44772 6 4 6V16C4 17.1046 4.89543 18 6 18H14C15.1046 18 16 17.1046 16 16V6C16.5523 6 17 5.55228 17 5C17 4.44772 16.5523 4 16 4H12.618L11.8944 2.55279C11.725 2.214 11.3788 2 11 2H9ZM7 8C7 7.44772 7.44772 7 8 7C8.55228 7 9 7.44772 9 8V14C9 14.5523 8.55228 15 8 15C7.44772 15 7 14.5523 7 14V8ZM12 7C11.4477 7 11 7.44772 11 8V14C11 14.5523 11.4477 15 12 15C12.5523 15 13 14.5523 13 14V8C13 7.44772 12.5523 7 12 7Z" fill="currentColor"/>
                                </svg>
                                Hapus Hotel
                            </button>
                        </div>
                    </div>

                    <!-- Hidden Delete Form -->
                    <form id="delete-form" action="{{ route('data-hotel.destroy', $hotel->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </x-common.component-card>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" 
             x-cloak
             @keydown.escape.window="showDeleteModal = false"
             class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-900/50 dark:bg-gray-900/80"
             style="display: none;">
            <div @click.away="showDeleteModal = false" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-90"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-90"
                 class="relative w-full max-w-md mx-4 bg-white rounded-lg shadow-xl dark:bg-gray-800">
                
                <!-- Modal Header -->
                <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/20">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-4">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        Apakah Anda yakin ingin menghapus hotel 
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $hotel->nama_hotel }}</span>?
                    </p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <button @click="showDeleteModal = false" 
                            type="button"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                        Batal
                    </button>
                    <button @click="document.getElementById('delete-form').submit()" 
                            type="button"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-red-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Hotel
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection
