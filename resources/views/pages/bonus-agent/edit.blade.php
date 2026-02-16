@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Pembayaran Bonus" :breadcrumbs="[
    ['label' => 'Bonus Agent', 'url' => route('bonus-agent.index')],
    ['label' => 'Riwayat Pembayaran', 'url' => route('payment-agent.show', $agent->id)],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 space-y-6">
        
        <!-- Edit Form -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Edit Pembayaran Bonus</h3>
            
            <form action="{{ route('bonus-agent.update', $payout->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Kode Transaksi (Read Only) -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Transaksi</label>
                        <input type="text" value="{{ $payout->kode_transaksi }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>

                    <!-- Nama Agent (Read Only) -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Agent</label>
                        <input type="text" value="{{ $agent->kode_agent }} | {{ $agent->nama_agent }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>

                    <!-- Jumlah Pembayaran -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Pembayaran <span class="text-red-500">*</span></label>
                        <div class="relative">
                             <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                             <input type="number" name="jumlah_bayar" value="{{ old('jumlah_bayar', $payout->jumlah_bayar) }}" class="w-full rounded-lg border border-gray-300 bg-white pl-9 pr-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-blue-800" required>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Metode Pembayaran <span class="text-red-500">*</span></label>
                        <select name="metode_pembayaran" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-blue-800" required>
                            <option value="">Pilih Metode</option>
                            <option value="Cash" {{ old('metode_pembayaran', $payout->metode_pembayaran) == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Transfer" {{ old('metode_pembayaran', $payout->metode_pembayaran) == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="Debit" {{ old('metode_pembayaran', $payout->metode_pembayaran) == 'Debit' ? 'selected' : '' }}>Debit</option>
                            <option value="QRIS" {{ old('metode_pembayaran', $payout->metode_pembayaran) == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                            <option value="Other" {{ old('metode_pembayaran', $payout->metode_pembayaran) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Tanggal Pembayaran -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Pembayaran <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_bayar" value="{{ old('tanggal_bayar', $payout->tanggal_bayar) }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-blue-800" required>
                    </div>

                    <!-- Kode Referensi Mutasi -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Referensi Mutasi</label>
                        <input type="text" name="kode_referensi_mutasi" value="{{ old('kode_referensi_mutasi', $payout->kode_referensi_mutasi) }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-blue-800" placeholder="Contoh: REF123456">
                    </div>

                    <!-- Status Pembayaran -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Pembayaran <span class="text-red-500">*</span></label>
                        <select name="status_pembayaran" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-blue-800" required>
                            <option value="">Pilih Status</option>
                            <option value="Checked" {{ old('status_pembayaran', $payout->status_pembayaran) == 'Checked' ? 'selected' : '' }}>Checked</option>
                            <option value="Confirmed" {{ old('status_pembayaran', $payout->status_pembayaran) == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                        </select>
                    </div>

                    <!-- Catatan Pembayaran -->
                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Pembayaran</label>
                        <textarea name="catatan" rows="2" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-blue-800" placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan', $payout->catatan) }}</textarea>
                    </div>

                    <!-- Bukti Pembayaran Saat Ini -->
                    @if($payout->bukti_pembayaran)
                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Bukti Pembayaran Saat Ini</label>
                        <a href="{{ asset('storage/' . $payout->bukti_pembayaran) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            Lihat Bukti Pembayaran
                        </a>
                    </div>
                    @endif

                    <!-- Upload Bukti Pembayaran Baru -->
                     <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Upload Bukti Pembayaran Baru (Opsional)</label>
                        <input type="file" name="bukti_pembayaran" accept="image/*" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:file:bg-blue-900/20 dark:file:text-blue-400">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, JPEG, PNG. Maks: 2MB</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('payment-agent.show', $agent->id) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                        Batal
                    </a>
                     <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Pembayaran
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
