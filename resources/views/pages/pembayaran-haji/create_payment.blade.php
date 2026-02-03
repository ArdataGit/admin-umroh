@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Pembayaran Haji" :breadcrumbs="[
    ['label' => 'Pembayaran Haji', 'url' => route('pembayaran-haji.index')],
    ['label' => 'History', 'url' => route('pembayaran-haji.history', $customerHaji->id)],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="col-span-12">
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        
        <form action="{{ route('pembayaran-haji.store-payment', $customerHaji->id) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Info (Read Only) -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900 dark:text-white border-b pb-2">Informasi Tagihan</h4>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Jamaah</label>
                        <input type="text" value="{{ $customerHaji->jamaah->nama_jamaah }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Total Tagihan</label>
                        <input type="text" value="Rp {{ number_format($customerHaji->total_tagihan, 0, ',', '.') }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                    </div>
                     <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Sisa Tagihan Saat Ini</label>
                        <div class="relative">
                            <input type="text" value="Rp {{ number_format($customerHaji->sisa_tagihan, 0, ',', '.') }}" readonly class="w-full rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-sm text-red-600 font-bold" />
                        </div>
                    </div>
                </div>

                <!-- Form Inputs -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900 dark:text-white border-b pb-2">Input Pembayaran Baru</h4>
                    
                    <div x-data="{ amount: {{ $customerHaji->sisa_tagihan }} }">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Pembayaran (IDR)</label>
                        <input type="number" name="jumlah_pembayaran" x-model="amount" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                        <p class="text-xs text-gray-500 mt-1">Otomatis terisi sisa tagihan: Rp <span x-text="Number(amount).toLocaleString('id-ID')"></span></p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="transfer">Transfer Bank</option>
                            <option value="cash">Cash / Tunai</option>
                            <option value="debit">Debit Card</option>
                            <option value="qris">QRIS</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Pembayaran</label>
                        <input type="date" name="tanggal_pembayaran" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Referensi / Bukti</label>
                        <input type="text" name="kode_referensi" placeholder="Contoh: No. Ref Bank" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan</label>
                        <textarea name="catatan" rows="2" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <a href="{{ route('pembayaran-haji.history', $customerHaji->id) }}" class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                            Batal
                        </a>
                        <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition">
                            Simpan Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection
