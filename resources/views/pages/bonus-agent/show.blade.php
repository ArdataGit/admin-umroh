@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Detail Bonus Agent" :breadcrumbs="[
    ['label' => 'Bonus Agent', 'url' => route('bonus-agent.index')],
    ['label' => 'Detail', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <!-- Agent Info & Summary -->
    <div class="col-span-12 space-y-6">
        
        <!-- Cards Grid -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            <!-- Agent Details -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Informasi Agent</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nama Agent</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $agent->nama_agent }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Kode Agent</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $agent->kode_agent }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Kontak</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $agent->kontak_agent }}</p>
                    </div>
                </div>
            </div>

            <!-- Performance Stats -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Performa</h3>
                <div class="space-y-3">
                     <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Jamaah Umroh</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                            {{ $agent->umroh_count }} Pax
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Jamaah Haji</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                            {{ $agent->haji_count }} Pax
                        </span>
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Bonus</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Bonus</span>
                        <span class="font-semibold text-blue-600">Rp {{ number_format($agent->total_bonus, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Sudah Dibayar</span>
                        <span class="font-semibold text-green-600">Rp {{ number_format($agent->sudah_dibayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-2 border-t border-gray-100 dark:border-gray-800 flex justify-between">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Sisa Bonus</span>
                        <span class="font-bold {{ $agent->sisa_bonus > 0 ? 'text-red-600' : 'text-gray-500' }}">Rp {{ number_format($agent->sisa_bonus, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Pembayaran Form -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900" x-data="{ 
            paymentAmount: 0,
            sisaBonus: {{ $agent->sisa_bonus }},
            formatPrice(price) {
                return new Intl.NumberFormat('id-ID').format(price);
            }
        }">
            <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Input Pembayaran Bonus</h3>
            
            <form action="{{ route('bonus-agent.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="agent_id" value="{{ $agent->id }}">

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                     <!-- Fields skipped for brevity, keeping original content -->
                    <!-- Kode Transaksi -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Transaksi</label>
                        <input type="text" value="{{ $nextTransactionCode }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>

                    <!-- Nama Agent -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Agent</label>
                        <input type="text" value="{{ $agent->kode_agent }} | {{ $agent->nama_agent }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>

                    <!-- Kontak Agent -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kontak Agent</label>
                        <input type="text" value="{{ $agent->kontak_agent }} | {{ $agent->email_agent ?? '-' }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>
                    
                    <!-- Total Bonus -->
                    <div>
                         <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Total Bonus Agent</label>
                         <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="text" value="{{ number_format($agent->total_bonus, 0, ',', '.') }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 pl-9 pr-3 py-2 text-sm text-gray-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                         </div>
                    </div>

                     <!-- Sudah Pembayaran -->
                     <div>
                         <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Sudah Pembayaran</label>
                         <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="text" value="{{ number_format($agent->sudah_dibayar, 0, ',', '.') }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 pl-9 pr-3 py-2 text-sm text-gray-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                         </div>
                    </div>

                     <!-- Sisa Bonus -->
                     <div>
                         <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Sisa Bonus Agent</label>
                         <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="text" value="{{ number_format($agent->sisa_bonus, 0, ',', '.') }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 pl-9 pr-3 py-2 text-sm font-bold text-red-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-red-400">
                         </div>
                    </div>

                    <!-- Jumlah Pembayaran -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Pembayaran <span class="text-red-500">*</span></label>
                        <div class="relative">
                             <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                             <input type="number" name="jumlah_bayar" x-model="paymentAmount" :max="sisaBonus" class="w-full rounded-lg border border-gray-300 bg-white pl-9 pr-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-blue-800" required placeholder="0">
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Metode Pembayaran <span class="text-red-500">*</span></label>
                        <select name="metode_pembayaran" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-blue-800" required>
                            <option value="">Pilih Metode</option>
                            <option value="Cash">Cash</option>
                            <option value="Transfer">Transfer</option>
                            <option value="Debit">Debit</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Tanggal Pembayaran -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Pembayaran <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-blue-800" required>
                    </div>

                    <!-- Kode Referensi Mutasi -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Referensi Mutasi</label>
                        <input type="text" name="kode_referensi_mutasi" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-blue-800" placeholder="Contoh: REF123456">
                    </div>

                    <!-- Catatan Pembayaran -->
                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Pembayaran</label>
                        <textarea name="catatan" rows="2" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-blue-800" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>

                    <!-- Upload Bukti Pembayaran -->
                     <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Upload Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:file:bg-blue-900/20 dark:file:text-blue-400">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, JPEG, PNG, PDF. Maks: 2MB</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                     <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
