@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Data Produk" :breadcrumbs="[
    ['label' => 'Data Produk', 'url' => route('data-produk')],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <form action="{{ route('data-produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" x-data="{
                kurs: '{{ old('kurs', $produk->kurs) }}',
                harga_beli: '{{ old('kurs', $produk->kurs) === 'IDR' ? old('harga_beli', $produk->harga_beli) : old('harga_beli', $produk->harga_beli_asing) }}',
                harga_jual: '{{ old('kurs', $produk->kurs) === 'IDR' ? old('harga_jual', $produk->harga_jual) : old('harga_jual', $produk->harga_jual_asing) }}',
                custom_kurs: null,
                kursUsd: {{ $kursUsd ?? 0 }},
                kursSar: {{ $kursSar ?? 0 }},
                kursMyr: {{ $kursMyr ?? 0 }},
                updateCustomKurs() {
                    if (this.kurs === 'USD') this.custom_kurs = this.kursUsd;
                    else if (this.kurs === 'SAR') this.custom_kurs = this.kursSar;
                    else if (this.kurs === 'MYR') this.custom_kurs = this.kursMyr;
                    else this.custom_kurs = null;
                },
                init() {
                    this.updateCustomKurs();
                    this.$watch('kurs', (newKurs, oldKurs) => {
                        this.updateCustomKurs();
                        // Reset pricing if switching to IDR
                        if (newKurs === 'IDR') {
                            // Logic depends on how you want to handle existing values
                        }
                    });
                },
                get currencySymbol() {
                    return this.kurs === 'IDR' ? 'Rp' : (this.kurs === 'MYR' ? 'RM' : this.kurs);
                },
                get exchangeRate() {
                    return parseFloat(this.custom_kurs) || 1;
                },
                get convertedBeli() {
                    if (this.kurs === 'IDR' || !this.harga_beli) return null;
                    return this.harga_beli * this.exchangeRate;
                },
                get convertedJual() {
                    if (this.kurs === 'IDR' || !this.harga_jual) return null;
                    return this.harga_jual * this.exchangeRate;
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
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    
                    <!-- Kode Produk (Readonly) -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Produk</label>
                        <input type="text" name="kode_produk" value="{{ $produk->kode_produk }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" />
                    </div>

                    <!-- Nama Produk -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Produk</label>
                        <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Nama Produk" required />
                        @error('nama_produk') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Standar Stok -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Standar Stok</label>
                        <input type="number" name="standar_stok" value="{{ old('standar_stok', $produk->standar_stok) }}" min="0" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                        @error('standar_stok') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Aktual Stok -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Aktual Stok</label>
                        <input type="number" name="aktual_stok" value="{{ old('aktual_stok', $produk->aktual_stok) }}" min="0" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                        @error('aktual_stok') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Satuan Unit -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Satuan Unit</label>
                        <select name="satuan_unit" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" required>
                            <option value="">Pilih Satuan</option>
                            @foreach(['Pcs', 'Set', 'Pack', 'Dus', 'Lot', 'Pax', 'Room', 'Seat'] as $unit)
                                <option value="{{ $unit }}" {{ old('satuan_unit', $produk->satuan_unit) == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                            @endforeach
                        </select>
                         @error('satuan_unit') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Mata Uang -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Mata Uang</label>
                        <select name="kurs" x-model="kurs" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" required>
                            <option value="IDR">IDR (Rupiah)</option>
                            <option value="USD">USD (Dollar AS)</option>
                            <option value="SAR">SAR (Riyal)</option>
                            <option value="MYR">RM (Ringgit)</option>
                        </select>
                    </div>

                    <!-- Custom Kurs -->
                    <div class="col-span-1 md:col-span-2" x-show="['USD', 'SAR', 'MYR'].includes(kurs)" x-cloak>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kurs <span x-text="kurs"></span> Hari Ini</label>
                        <div class="relative">
                            <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium">Rp</span>
                            <input type="text" name="custom_kurs" :value="formatNumber(custom_kurs)" @input="$el.value = $el.value.replace(/\D/g, ''); custom_kurs = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(custom_kurs)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" />
                        </div>
                    </div>

                    <!-- Harga Beli -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Beli</label>
                         <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium" x-text="currencySymbol">Rp</span>
                            <input type="text" name="harga_beli" :value="formatNumber(harga_beli)" @input="$el.value = $el.value.replace(/\D/g, ''); harga_beli = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(harga_beli)" class="w-full rounded-lg border border-gray-300 bg-transparent pl-12 px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                        </div>
                        <div x-show="convertedBeli" class="mt-1 flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400">
                            <span class="opacity-70">Estimasi:</span>
                            <span x-text="formatRupiah(convertedBeli)"></span>
                        </div>
                        @error('harga_beli') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Harga Jual -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Jual</label>
                         <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium" x-text="currencySymbol">Rp</span>
                            <input type="text" name="harga_jual" :value="formatNumber(harga_jual)" @input="$el.value = $el.value.replace(/\D/g, ''); harga_jual = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(harga_jual)" class="w-full rounded-lg border border-gray-300 bg-transparent pl-12 px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                        </div>
                        <div x-show="convertedJual" class="mt-1 flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400">
                            <span class="opacity-70">Estimasi:</span>
                            <span x-text="formatRupiah(convertedJual)"></span>
                        </div>
                        @error('harga_jual') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Foto Produk -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Produk</label>
                        <input type="file" name="foto_produk" accept="image/*" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Max: 2MB.</p>
                        @if($produk->foto_produk)
                             <div class="mt-2">
                                <p class="text-xs text-gray-500 mb-1">Foto saat ini:</p>
                                <img src="{{ asset('storage/' . $produk->foto_produk) }}" alt="Foto Produk" class="h-20 w-20 object-cover rounded-md border border-gray-200 dark:border-gray-700">
                            </div>
                        @endif
                        @error('foto_produk') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan Produk -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Produk</label>
                        <textarea name="catatan_produk" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Catatan (Opsional)">{{ old('catatan_produk', $produk->catatan_produk) }}</textarea>
                        @error('catatan_produk') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('data-produk') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
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
