@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Buat Surat Rekomendasi" :breadcrumbs="[
    ['label' => 'Surat Rekomendasi', 'url' => route('surat-rekomendasi.index')],
    ['label' => 'Buat Baru', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Form Surat Rekomendasi</h3>
            
            <form action="{{ route('surat-rekomendasi.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Jamaah (Select2 style logic can be added later, currently standard select) -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Data Jamaah</label>
                        <select name="jamaah_id" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                            <option value="">-- Pilih Jamaah --</option>
                            @foreach($jamaah as $j)
                                <option value="{{ $j->id }}">{{ $j->nama_jamaah }} - {{ $j->nik_jamaah }}</option>
                            @endforeach
                        </select>
                         @error('jamaah_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Keberangkatan -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Keberangkatan Umroh</label>
                        <select name="keberangkatan_umroh_id" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                            <option value="">-- Pilih Keberangkatan --</option>
                            @foreach($keberangkatan as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_keberangkatan }} ({{ \Carbon\Carbon::parse($k->tanggal_keberangkatan)->format('d M Y') }})</option>
                            @endforeach
                        </select>
                         @error('keberangkatan_umroh_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nomor Dokumen -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Dokumen</label>
                        <input type="text" name="nomor_dokumen" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" placeholder="No. Surat" required />
                        @error('nomor_dokumen') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kantor Imigrasi -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kantor Imigrasi</label>
                        <input type="text" name="kantor_imigrasi" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" placeholder="Contoh: Batam" required />
                        @error('kantor_imigrasi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                     <!-- Nama Ayah -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Ayah Jamaah</label>
                        <input type="text" name="nama_ayah" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                        @error('nama_ayah') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                     <!-- Nama Kakek -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Kakek Jamaah</label>
                        <input type="text" name="nama_kakek" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                        @error('nama_kakek') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                     <!-- Catatan -->
                     <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Surat Rekomendasi</label>
                        <textarea name="catatan" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                    </div>

                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('surat-rekomendasi.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Simpan Surat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
