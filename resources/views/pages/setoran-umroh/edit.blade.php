@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Setoran Umroh" :breadcrumbs="[
    ['label' => 'Data Tabungan Umroh', 'url' => route('tabungan-umroh')],
    ['label' => 'Detail Setoran', 'url' => route('setoran-umroh.index', $tabungan->id)],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <form action="{{ route('setoran-umroh.update', $transaksi->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Informasi Transaksi</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Transaksi</label>
                    <input type="text" name="kode_transaksi" value="{{ $transaksi->kode_transaksi }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Jamaah</label>
                    <input type="text" value="{{ $tabungan->jamaah->kode_jamaah }} - {{ $tabungan->jamaah->nama_lengkap }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Detail Setoran</h3>
             <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Setoran (Nominal)</label>
                    <input type="number" name="nominal" min="1" value="{{ old('nominal', $transaksi->nominal) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        @foreach(['Cash', 'Transfer', 'Debit', 'QRIS', 'Other'] as $method)
                            <option value="{{ $method }}" {{ $transaksi->metode_pembayaran == $method ? 'selected' : '' }}>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Setoran</label>
                    <select name="status_setoran" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="checked" {{ $transaksi->status_setoran == 'checked' ? 'selected' : '' }}>Checked</option>
                        <option value="completed" {{ $transaksi->status_setoran == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Setoran</label>
                    <input type="date" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', $transaksi->tanggal_transaksi) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Referensi (Mutasi)</label>
                    <input type="text" name="kode_referensi" value="{{ old('kode_referensi', $transaksi->kode_referensi) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" placeholder="Opsional" />
                </div>
                 <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Setoran</label>
                    <textarea name="keterangan" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" placeholder="Opsional">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                </div>
                 <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Bukti Setoran</label>
                    @if($transaksi->bukti_transaksi)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $transaksi->bukti_transaksi) }}" target="_blank" class="text-blue-500 text-sm hover:underline">Lihat Bukti Saat Ini</a>
                        </div>
                    @endif
                    <input type="file" name="bukti_transaksi" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                    <p class="mt-1 text-xs text-gray-500">Upload baru untuk mengganti. Max size: 2MB. Format: JPG, PNG.</p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('setoran-umroh.index', $tabungan->id) }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Simpan Perubahan</button>
        </div>
        </form>
    </div>
</div>
@endsection
