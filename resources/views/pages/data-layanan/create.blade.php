@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Data Layanan" :breadcrumbs="[
    ['label' => 'Data Layanan', 'url' => route('data-layanan')],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <form action="{{ route('data-layanan.store') }}" method="POST" enctype="multipart/form-data" x-data="{
                kurs: 'IDR',
                harga_modal: '',
                harga_jual: '',
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
                    this.$watch('kurs', () => this.updateCustomKurs());
                },
                get currencySymbol() {
                    return this.kurs === 'IDR' ? 'Rp' : (this.kurs === 'MYR' ? 'RM' : this.kurs);
                },
                get exchangeRate() {
                    return parseFloat(this.custom_kurs) || 1;
                },
                get convertedModal() {
                    if (this.kurs === 'IDR' || !this.harga_modal) return null;
                    return this.harga_modal * this.exchangeRate;
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
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    
                    <!-- Kode Layanan (Readonly) -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Layanan</label>
                        <input type="text" name="kode_layanan" value="{{ $kodeLayanan }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" />
                    </div>

                    <!-- Jenis Layanan -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Layanan</label>
                        <select name="jenis_layanan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
                            <option value="">Pilih Jenis Layanan</option>
                            <option value="Pesawat" {{ old('jenis_layanan') == 'Pesawat' ? 'selected' : '' }}>Pesawat</option>
                            <option value="Hotel" {{ old('jenis_layanan') == 'Hotel' ? 'selected' : '' }}>Hotel</option>
                             <option value="Visa" {{ old('jenis_layanan') == 'Visa' ? 'selected' : '' }}>Visa</option>
                            <option value="Transport" {{ old('jenis_layanan') == 'Transport' ? 'selected' : '' }}>Transport</option>
                            <option value="Handling" {{ old('jenis_layanan') == 'Handling' ? 'selected' : '' }}>Handling</option>
                            <option value="Tour" {{ old('jenis_layanan') == 'Tour' ? 'selected' : '' }}>Tour</option>
                            <option value="Layanan" {{ old('jenis_layanan') == 'Layanan' ? 'selected' : '' }}>Layanan</option>
                            <option value="Lainnya" {{ old('jenis_layanan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis_layanan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nama Layanan -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Layanan</label>
                        <input type="text" name="nama_layanan" value="{{ old('nama_layanan') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Nama Layanan" required />
                        @error('nama_layanan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Satuan Unit -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Satuan Unit</label>
                        <select name="satuan_unit" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
                            <option value="">Pilih Satuan Unit</option>
                            <option value="Pcs" {{ old('satuan_unit') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                            <option value="Set" {{ old('satuan_unit') == 'Set' ? 'selected' : '' }}>Set</option>
                            <option value="Pack" {{ old('satuan_unit') == 'Pack' ? 'selected' : '' }}>Pack</option>
                            <option value="Dus" {{ old('satuan_unit') == 'Dus' ? 'selected' : '' }}>Dus</option>
                            <option value="Lot" {{ old('satuan_unit') == 'Lot' ? 'selected' : '' }}>Lot</option>
                            <option value="Pax" {{ old('satuan_unit') == 'Pax' ? 'selected' : '' }}>Pax</option>
                            <option value="Room" {{ old('satuan_unit') == 'Room' ? 'selected' : '' }}>Room</option>
                            <option value="Seat" {{ old('satuan_unit') == 'Seat' ? 'selected' : '' }}>Seat</option>
                        </select>
                         @error('satuan_unit') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kurs -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Mata Uang</label>
                        <select name="kurs" x-model="kurs" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
                            <option value="IDR">IDR (Rupiah)</option>
                            <option value="USD">USD (Dollar AS)</option>
                            <option value="SAR">SAR (Riyal)</option>
                            <option value="MYR">RM (Ringgit)</option>
                        </select>
                    </div>

                    <!-- Custom Kurs -->
                    <div x-show="['USD', 'SAR', 'MYR'].includes(kurs)" x-cloak>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kurs <span x-text="kurs"></span> Hari Ini</label>
                        <div class="relative">
                            <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium">Rp</span>
                            <input type="text" name="custom_kurs" :value="formatNumber(custom_kurs)" @input="$el.value = $el.value.replace(/\D/g, ''); custom_kurs = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(custom_kurs)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" />
                        </div>
                    </div>

                    <!-- Harga Modal -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Modal</label>
                        <div class="relative">
                            <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium" x-text="currencySymbol">Rp</span>
                            <input type="text" name="harga_modal" :value="formatNumber(harga_modal)" @input="$el.value = $el.value.replace(/\D/g, ''); harga_modal = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(harga_modal)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" required />
                        </div>
                        <div x-show="convertedModal" class="mt-1 flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400">
                            <span class="opacity-70">Estimasi:</span>
                            <span x-text="formatRupiah(convertedModal)"></span>
                        </div>
                        @error('harga_modal') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Harga Jual -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Jual</label>
                        <div class="relative">
                            <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium" x-text="currencySymbol">Rp</span>
                            <input type="text" name="harga_jual" :value="formatNumber(harga_jual)" @input="$el.value = $el.value.replace(/\D/g, ''); harga_jual = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(harga_jual)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" required />
                        </div>
                        <div x-show="convertedJual" class="mt-1 flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400">
                            <span class="opacity-70">Estimasi:</span>
                            <span x-text="formatRupiah(convertedJual)"></span>
                        </div>
                        @error('harga_jual') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status Layanan -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Layanan</label>
                        <select name="status_layanan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
                            <option value="Active" {{ old('status_layanan') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Non Active" {{ old('status_layanan') == 'Non Active' ? 'selected' : '' }}>Non Active</option>
                        </select>
                        @error('status_layanan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Foto Layanan -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Layanan</label>
                        <input type="file" name="foto_layanan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" accept="image/*" />
                         @error('foto_layanan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan Layanan -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Layanan</label>
                        <textarea name="catatan_layanan" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Catatan (Opsional)">{{ old('catatan_layanan') }}</textarea>
                        @error('catatan_layanan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('data-layanan') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
