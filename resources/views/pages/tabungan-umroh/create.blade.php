@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Tabungan Umroh" :breadcrumbs="[
    ['label' => 'Data Tabungan Umroh', 'url' => route('tabungan-umroh')],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <form action="{{ route('tabungan-umroh.store') }}" method="POST">
        @csrf
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Informasi Tabungan</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Tabungan</label>
                    <input type="text" name="kode_tabungan" value="{{ $kodeTabungan }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>
                <div>
                     <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Jamaah</label>
                    <select name="jamaah_id" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="">Pilih Jamaah</option>
                        @foreach($jamaahs as $jamaah)
                            <option value="{{ $jamaah->id }}">{{ $jamaah->nama_lengkap }} ({{ $jamaah->nomor_paspor_1 ?? 'No Passport' }})</option>
                        @endforeach
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Pendaftaran</label>
                    <input type="date" name="tanggal_pendaftaran" value="{{ old('tanggal_pendaftaran') }}" min="1900-01-01" max="9999-12-31" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Tabungan</label>
                    <select name="status_tabungan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="active">Active</option>
                        <option value="non-active">Non-Active</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Informasi Bank & Pembayaran</h3>
             <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Bank Tabungan</label>
                    <select name="bank_tabungan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="Bank Travel">Bank Travel</option>
                        <option value="Bank BSI">Bank BSI</option>
                        <option value="Bank Muamalat">Bank Muamalat</option>
                        <option value="Bank BRI">Bank BRI</option>
                        <option value="Bank BNI">Bank BNI</option>
                        <option value="Bank BCA">Bank BCA</option>
                        <option value="Bank Mandiri">Bank Mandiri</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Rekening</label>
                    <input type="text" name="rekening_tabungan" value="{{ old('rekening_tabungan') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Setoran Tabungan (IDR)</label>
                    <input type="number" name="setoran_tabungan" value="{{ old('setoran_tabungan') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Metode Pembayaran</label>
                     <select name="metode_pembayaran" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="Cash">Cash</option>
                        <option value="Transfer">Transfer</option>
                        <option value="Debit">Debit</option>
                        <option value="QRIS">QRIS</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                 <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Pembayaran</label>
                    <textarea name="catatan_pembayaran" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">{{ old('catatan_pembayaran') }}</textarea>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('tabungan-umroh') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Simpan Tabungan</button>
        </div>
        </form>
    </div>
</div>
@endsection
