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
            
            <form action="{{ route('pembayaran-tiket.store-payment', $transaksi->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6" x-data="{
                kurs: '{{ old('kurs', 'IDR') }}',
                jumlah: '{{ old('jumlah_pembayaran') }}',
                get currencySymbol() {
                    return this.kurs === 'IDR' ? 'Rp' : (this.kurs === 'MYR' ? 'RM' : this.kurs);
                },
                get exchangeRate() {
                    if (this.kurs === 'USD') return {{ $kursUsd ?? 0 }};
                    if (this.kurs === 'SAR') return {{ $kursSar ?? 0 }};
                    if (this.kurs === 'MYR') return {{ $kursMyr ?? 0 }};
                    return 1;
                },
                get convertedAmount() {
                    if (this.kurs === 'IDR' || !this.jumlah) return null;
                    return this.jumlah * this.exchangeRate;
                },
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                },
                formatNumber(num) {
                    if (!num && num !== 0) return '';
                    return new Intl.NumberFormat('id-ID').format(Math.round(num));
                }
            }">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tanggal Pembayaran -->
                     <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Tanggal Pembayaran</label>
                        <input type="date" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}" 
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white" required>
                        @error('tanggal_pembayaran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Mata Uang -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Mata Uang</label>
                        <select name="kurs" x-model="kurs" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white" required>
                            <option value="IDR">IDR (Rupiah)</option>
                            <option value="USD">USD (Dollar AS)</option>
                            <option value="SAR">SAR (Riyal)</option>
                            <option value="MYR">RM (Ringgit)</option>
                        </select>
                        @error('kurs') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jumlah Pembayaran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Jumlah Pembayaran</label>
                        <div class="relative">
                            <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium" x-text="currencySymbol">
                                Rp
                            </span>
                            <input type="hidden" name="jumlah_pembayaran" :value="jumlah">
                            <input type="text" :value="formatNumber(jumlah)" @input="$el.value = $el.value.replace(/\D/g, ''); jumlah = $el.value === '' ? '' : parseInt($el.value); $el.value = formatNumber(jumlah)" placeholder="Masukkan jumlah" required
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-12 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white">
                        </div>
                        @error('jumlah_pembayaran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                        <!-- Kurs Info Compact -->
                        <div class="mt-3 flex items-center gap-4 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10 p-2 rounded-lg border border-blue-100 dark:border-blue-900/30"
                            x-show="kurs !== 'IDR' && kurs !== ''"
                            x-transition>
                            <div class="flex items-center gap-1.5" x-show="kurs === 'USD'">
                                <span class="opacity-70">USD:</span>
                                <span>Rp {{ number_format($kursUsd ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5" x-show="kurs === 'SAR'">
                                <span class="opacity-70">SAR:</span>
                                <span>Rp {{ number_format($kursSar ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5" x-show="kurs === 'MYR'">
                                <span class="opacity-70">RM:</span>
                                <span>Rp {{ number_format($kursMyr ?? 0, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="h-3 w-px bg-blue-200 dark:bg-blue-800"></div>
                            
                            <div x-show="convertedAmount" class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                                <span class="opacity-70">Estimasi:</span>
                                <span x-text="formatRupiah(convertedAmount)"></span>
                            </div>
                        </div>
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
                     <div class="col-span-1 md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Catatan (Optional)</label>
                        <textarea name="catatan" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white">{{ old('catatan') }}</textarea>
                    </div>

                    <!-- Bukti Pembayaran -->
                     <div class="col-span-1 md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Bukti Pembayaran (Optional)</label>
                        <input type="file" name="bukti_pembayaran" accept="image/*,.pdf"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500">Maks. 2MB (JPG, PNG, PDF)</p>
                        @error('bukti_pembayaran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
