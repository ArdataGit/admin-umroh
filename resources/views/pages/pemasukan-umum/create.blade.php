@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Pemasukan Umum" :breadcrumbs="[
    ['label' => 'Pemasukan Umum', 'url' => route('pemasukan-umum.index')],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data="{
        jenisPemasukan: ['lainya']
    }">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Form Pemasukan Umum</h3>
            
            <form action="{{ route('pemasukan-umum.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                     <!-- Kode Pemasukan (Readonly) -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Pemasukan</label>
                        <input type="text" name="kode_pemasukan" value="{{ $kodePemasukan }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 cursor-not-allowed focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" />
                        @error('kode_pemasukan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tanggal Pemasukan -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Pemasukan</label>
                        <input type="date" name="tanggal_pemasukan" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                        @error('tanggal_pemasukan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Jenis Pemasukan -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Pemasukan</label>
                        <select name="jenis_pemasukan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                             <template x-for="item in jenisPemasukan">
                                <option :value="item" x-text="item.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())"></option>
                            </template>
                        </select>
                         @error('jenis_pemasukan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                     <!-- Nama Pemasukan -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Pemasukan</label>
                        <input type="text" name="nama_pemasukan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" placeholder="Contoh: Hibah, Bonus, dll" required />
                        @error('nama_pemasukan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                     <!-- Jumlah Pemasukan -->
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Pemasukan</label>
                        <div class="relative">
                            <span class="absolute left-4 top-2.5 text-gray-500">Rp</span>
                            <input type="number" name="jumlah_pemasukan" class="w-full pl-10 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                        </div>
                        @error('jumlah_pemasukan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                     <!-- Bukti Pemasukan -->
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Bukti Pemasukan (Foto)</label>
                        <input type="file" name="bukti_pemasukan" accept="image/*" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                         <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Max: 2MB.</p>
                        @error('bukti_pemasukan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                     <!-- Catatan -->
                     <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Pemasukan</label>
                        <textarea name="catatan_pemasukan" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                    </div>

                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('pemasukan-umum.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Simpan Pemasukan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
