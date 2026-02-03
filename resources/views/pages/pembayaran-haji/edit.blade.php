@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Pembayaran Haji" :breadcrumbs="[
    ['label' => 'Pembayaran Haji', 'url' => route('pembayaran-haji.index')],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="col-span-12">
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        
        <form action="{{ route('pembayaran-haji.update', $pembayaran->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Info (Read Only) -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900 dark:text-white border-b pb-2">Informasi Transaksi</h4>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Transaksi</label>
                        <input type="text" value="{{ $pembayaran->kode_transaksi }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Jamaah</label>
                        <input type="text" value="{{ $pembayaran->customerHaji->jamaah->nama_jamaah }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                    </div>
                </div>

                <!-- Form Inputs -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900 dark:text-white border-b pb-2">Edit Data Pembayaran</h4>
                    
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Pembayaran (IDR)</label>
                        <input type="number" name="jumlah_pembayaran" value="{{ old('jumlah_pembayaran', $pembayaran->jumlah_pembayaran) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="transfer" {{ $pembayaran->metode_pembayaran == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="cash" {{ $pembayaran->metode_pembayaran == 'cash' ? 'selected' : '' }}>Cash / Tunai</option>
                            <option value="debit" {{ $pembayaran->metode_pembayaran == 'debit' ? 'selected' : '' }}>Debit Card</option>
                            <option value="qris" {{ $pembayaran->metode_pembayaran == 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="other" {{ $pembayaran->metode_pembayaran == 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Pembayaran</label>
                        <select name="status_pembayaran" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="pending" {{ $pembayaran->status_pembayaran == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $pembayaran->status_pembayaran == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="checked" {{ $pembayaran->status_pembayaran == 'checked' ? 'selected' : '' }}>Checked / Verified</option>
                            <option value="failed" {{ $pembayaran->status_pembayaran == 'failed' ? 'selected' : '' }}>Failed / Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Pembayaran</label>
                        <input type="date" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', $pembayaran->tanggal_pembayaran) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Referensi / Bukti</label>
                        <input type="text" name="kode_referensi" value="{{ old('kode_referensi', $pembayaran->kode_referensi) }}" placeholder="Contoh: No. Ref Bank" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan</label>
                        <textarea name="catatan" rows="2" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('catatan', $pembayaran->catatan) }}</textarea>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <a href="{{ route('pembayaran-haji.index') }}" class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                            Batal
                        </a>
                        <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection
