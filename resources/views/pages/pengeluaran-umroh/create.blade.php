@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Pengeluaran Umroh" :breadcrumbs="[
    ['label' => 'Pengeluaran Umroh', 'url' => route('pengeluaran-umroh.index')],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data="{
        jenisPengeluaran: ['visa_umroh', 'siskopatuh', 'tiket_pesawat', 'bayar_hotel', 'transportasi', 'pembimbing', 'kereta_cepat', 'logistik', 'handling', 'lainya']
    }">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Form Pengeluaran Umroh</h3>
            
            <form action="{{ route('pengeluaran-umroh.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Keberangkatan (Dropdown) -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Keberangkatan Umroh</label>
                        <select name="keberangkatan_umroh_id" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                            <option value="">-- Pilih Keberangkatan --</option>
                            @foreach($keberangkatans as $k)
                                <option value="{{ $k->id }}">{{ $k->kode_keberangkatan }} - {{ $k->nama_keberangkatan }} ({{ $k->paketUmroh->nama_paket }})</option>
                            @endforeach
                        </select>
                         @error('keberangkatan_umroh_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                     <!-- Kode Pengeluaran (Readonly) -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Pengeluaran</label>
                        <input type="text" name="kode_pengeluaran" value="{{ $kodePengeluaran }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 cursor-not-allowed focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" />
                        @error('kode_pengeluaran') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tanggal Pengeluaran -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Pengeluaran</label>
                        <input type="date" name="tanggal_pengeluaran" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                        @error('tanggal_pengeluaran') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Jenis Pengeluaran -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Pengeluaran</label>
                        <select name="jenis_pengeluaran" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                            <option value="">-- Pilih Jenis --</option>
                             <template x-for="item in jenisPengeluaran">
                                <option :value="item" x-text="item.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())"></option>
                            </template>
                        </select>
                         @error('jenis_pengeluaran') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                     <!-- Nama Pengeluaran -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Pengeluaran</label>
                        <input type="text" name="nama_pengeluaran" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" placeholder="Contoh: Pembayaran Visa Batch 1" required />
                        @error('nama_pengeluaran') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                     <!-- Jumlah Pengeluaran -->
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Pengeluaran</label>
                        <div class="relative">
                            <span class="absolute left-4 top-2.5 text-gray-500">Rp</span>
                            <input type="number" name="jumlah_pengeluaran" class="w-full pl-10 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                        </div>
                        @error('jumlah_pengeluaran') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                     <!-- Bukti Pengeluaran -->
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Bukti Pengeluaran (Foto)</label>
                        <input type="file" name="bukti_pengeluaran" accept="image/*" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                         <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Max: 2MB.</p>
                        @error('bukti_pengeluaran') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                     <!-- Catatan -->
                     <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Pengeluaran</label>
                        <textarea name="catatan_pengeluaran" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                    </div>

                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('pengeluaran-umroh.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Simpan Pengeluaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
