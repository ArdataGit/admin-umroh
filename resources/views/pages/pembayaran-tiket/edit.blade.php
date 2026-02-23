@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Pembayaran Tiket" :breadcrumbs="[
    ['label' => 'Pembayaran Tiket', 'url' => route('pembayaran-tiket.index')],
    ['label' => 'Detail', 'url' => route('pembayaran-tiket.detail', $pembayaran->id)],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Edit Pembayaran - {{ $pembayaran->kode_transaksi }}</h3>
                <p class="text-sm text-gray-500">Transaksi Tiket: {{ $transaksi->kode_transaksi }} | Total Tagihan: Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</p>
            </div>
            
            <form action="{{ route('pembayaran-tiket.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6" x-data="{
                kurs: '{{ old('kurs', $pembayaran->kurs ?? 'IDR') }}',
                jumlah: {{ old('jumlah_pembayaran', $pembayaran->kurs !== 'IDR' ? ($pembayaran->kurs_asing ?? 0) : ($pembayaran->jumlah_pembayaran ?? 0)) }},
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
                formatNumber(num) {
                    if (!num && num !== 0) return '';
                    return new Intl.NumberFormat('id-ID').format(Math.round(num));
                },
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                }
            }">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tanggal Pembayaran -->
                     <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Tanggal Pembayaran</label>
                        <input type="date" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', date('Y-m-d', strtotime($pembayaran->tanggal_pembayaran))) }}" 
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
                            <option value="cash" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="transfer" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="debit" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'debit' ? 'selected' : '' }}>Kartu Debit</option>
                            <option value="qris" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="other" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('metode_pembayaran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kode Referensi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Kode Referensi / Bukti (Optional)</label>
                        <input type="text" name="kode_referensi" value="{{ old('kode_referensi', $pembayaran->kode_referensi) }}" placeholder="Contoh: NO-RESI-123"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white">
                    </div>

                     <!-- Catatan -->
                     <div class="col-span-1 md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Catatan (Optional)</label>
                        <textarea name="catatan" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white">{{ old('catatan', $pembayaran->catatan) }}</textarea>
                    </div>

                    <!-- Bukti Pembayaran -->
                     <div class="col-span-1 md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Bukti Pembayaran (Optional)</label>
                        @if($pembayaran->bukti_pembayaran)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    Lihat Bukti Saat Ini
                                </a>
                            </div>
                        @endif
                        <input type="file" name="bukti_pembayaran" accept="image/*,.pdf"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500">Maks. 2MB (JPG, PNG, PDF). Biarkan kosong jika tidak ingin mengubah.</p>
                        @error('bukti_pembayaran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-6 dark:border-gray-800">
                    <a href="{{ route('pembayaran-tiket.detail', $pembayaran->id) }}" class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800">Batal</a>
                    <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700">Perbarui Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
