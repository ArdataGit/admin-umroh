@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Pengeluaran Umum" :breadcrumbs="[
    ['label' => 'Data Pengeluaran', 'url' => route('pengeluaran-umum.index')],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <form action="{{ route('pengeluaran-umum.update', $pengeluaran->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    
                    <!-- Kode Pengeluaran (Readonly) -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Pengeluaran</label>
                        <input type="text" name="kode_pengeluaran" value="{{ $pengeluaran->kode_pengeluaran }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" />
                    </div>

                    <!-- Tanggal Pengeluaran -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Pengeluaran</label>
                        <input type="date" name="tanggal_pengeluaran" value="{{ old('tanggal_pengeluaran', $pengeluaran->tanggal_pengeluaran) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                        @error('tanggal_pengeluaran') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jenis Pengeluaran -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Pengeluaran</label>
                        <select name="jenis_pengeluaran" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
                            <option value="">Pilih Jenis Pengeluaran</option>
                            <option value="operasional_kantor" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'operasional_kantor' ? 'selected' : '' }}>Operasional Kantor</option>
                            <option value="gaji_karyawan" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'gaji_karyawan' ? 'selected' : '' }}>Gaji Karyawan</option>
                            <option value="pemasaran" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'pemasaran' ? 'selected' : '' }}>Pemasaran</option>
                            <option value="perlengkapan" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'perlengkapan' ? 'selected' : '' }}>Perlengkapan</option>
                            <option value="lainnya" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis_pengeluaran') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nama Pengeluaran -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Pengeluaran</label>
                        <input type="text" name="nama_pengeluaran" value="{{ old('nama_pengeluaran', $pengeluaran->nama_pengeluaran) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Nama Pengeluaran" required />
                        @error('nama_pengeluaran') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jumlah Pengeluaran -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Pengeluaran</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">Rp</span>
                            <input type="number" name="jumlah_pengeluaran" value="{{ old('jumlah_pengeluaran', $pengeluaran->jumlah_pengeluaran) }}" class="w-full rounded-lg border border-gray-300 bg-transparent pl-10 px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" required />
                        </div>
                        @error('jumlah_pengeluaran') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Bukti Pengeluaran -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Bukti Pengeluaran (Opsional)</label>
                        <input type="file" name="bukti_pengeluaran" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:file:bg-gray-800 dark:file:text-gray-400" accept="image/*" />
                         @if($pengeluaran->bukti_pengeluaran)
                            <div class="mt-2">
                                <p class="text-xs text-gray-500 mb-1">Bukti saat ini:</p>
                                <a href="{{ asset('storage/' . $pengeluaran->bukti_pengeluaran) }}" target="_blank" class="text-blue-500 hover:underline text-sm">Lihat Bukti</a>
                            </div>
                        @endif
                        @error('bukti_pengeluaran') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan Pengeluaran -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Pengeluaran</label>
                        <textarea name="catatan_pengeluaran" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Catatan (Opsional)">{{ old('catatan_pengeluaran', $pengeluaran->catatan_pengeluaran) }}</textarea>
                        @error('catatan_pengeluaran') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('pengeluaran-umum.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
