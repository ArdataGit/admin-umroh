@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Pembayaran Tiket" :breadcrumbs="[
    ['label' => 'Pembayaran Tiket', 'url' => route('pembayaran-tiket.index')],
    ['label' => 'History', 'url' => route('pembayaran-tiket.show', $transaksi->id)],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Form Pembayaran - {{ $transaksi->pelanggan->nama_pelanggan ?? 'Umum' }}</h3>
                <p class="text-sm text-gray-500">Kode Transaksi: {{ $transaksi->kode_transaksi }} | Total Tagihan: Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</p>
            </div>
            
            <form action="{{ route('pembayaran-tiket.store-payment', $transaksi->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tanggal Pembayaran -->
                     <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Tanggal Pembayaran</label>
                        <input type="date" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}" 
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white" required>
                        @error('tanggal_pembayaran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jumlah Pembayaran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Jumlah Pembayaran</label>
                        <input type="number" name="jumlah_pembayaran" value="{{ old('jumlah_pembayaran') }}" min="1"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white" required>
                        @error('jumlah_pembayaran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white" required>
                            <option value="">Pilih Metode</option>
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="debit">Kartu Debit</option>
                            <option value="qris">QRIS</option>
                            <option value="other">Lainnya</option>
                        </select>
                        @error('metode_pembayaran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kode Referensi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Kode Referensi / Bukti (Optional)</label>
                        <input type="text" name="kode_referensi" value="{{ old('kode_referensi') }}" placeholder="Contoh: NO-RESI-123"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white">
                    </div>

                     <!-- Catatan -->
                     <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Catatan (Optional)</label>
                        <textarea name="catatan" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-6 dark:border-gray-800">
                    <a href="{{ route('pembayaran-tiket.show', $transaksi->id) }}" class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800">Batal</a>
                    <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700">Simpan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
