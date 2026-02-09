@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Data Layanan" :breadcrumbs="[
    ['label' => 'Data Layanan', 'url' => route('data-layanan')],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
            <form action="{{ route('data-layanan.update', $layanan->id) }}" method="POST" enctype="multipart/form-data" x-data="{
                kurs: '{{ old('kurs', $layanan->kurs ?? 'IDR') }}',
                harga_modal: '{{ old('harga_modal', $layanan->kurs !== 'IDR' ? $layanan->harga_modal_asing : $layanan->harga_modal) }}',
                harga_jual: '{{ old('harga_jual', $layanan->kurs !== 'IDR' ? $layanan->harga_jual_asing : $layanan->harga_jual) }}',
                get currencySymbol() {
                    return this.kurs === 'IDR' ? 'Rp' : (this.kurs === 'MYR' ? 'RM' : this.kurs);
                },
                get exchangeRate() {
                    if (this.kurs === 'USD') return {{ $kursUsd ?? 0 }};
                    if (this.kurs === 'SAR') return {{ $kursSar ?? 0 }};
                    if (this.kurs === 'MYR') return {{ $kursMyr ?? 0 }};
                    return 1;
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
                }
            }">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    
                    <!-- Kode Layanan (Readonly) -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Layanan</label>
                        <input type="text" name="kode_layanan" value="{{ $layanan->kode_layanan }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" />
                    </div>

                    <!-- Jenis Layanan -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Layanan</label>
                        <select name="jenis_layanan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
                            <option value="">Pilih Jenis Layanan</option>
                            @foreach(['Pesawat', 'Hotel', 'Visa', 'Transport', 'Handling', 'Tour', 'Layanan', 'Lainnya'] as $jenis)
                                <option value="{{ $jenis }}" {{ old('jenis_layanan', $layanan->jenis_layanan) == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                            @endforeach
                        </select>
                        @error('jenis_layanan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nama Layanan -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Layanan</label>
                        <input type="text" name="nama_layanan" value="{{ old('nama_layanan', $layanan->nama_layanan) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Nama Layanan" required />
                        @error('nama_layanan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Satuan Unit -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Satuan Unit</label>
                        <select name="satuan_unit" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
                            <option value="">Pilih Satuan Unit</option>
                            @foreach(['Pcs', 'Set', 'Pack', 'Dus', 'Lot', 'Pax', 'Room', 'Seat'] as $satuan)
                                <option value="{{ $satuan }}" {{ old('satuan_unit', $layanan->satuan_unit) == $satuan ? 'selected' : '' }}>{{ $satuan }}</option>
                            @endforeach
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

                    <!-- Harga Modal -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Modal</label>
                        <div class="relative">
                            <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium" x-text="currencySymbol">Rp</span>
                            <input type="number" name="harga_modal" x-model="harga_modal" min="0" step="0.01" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" required />
                        </div>
                        <div x-show="convertedModal" class="mt-1 flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400">
                            <span class="opacity-70">Estimasi:</span>
                            <span x-text="formatRupiah(convertedModal)"></span>
                        </div>
                        @error('harga_modal') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

                        <!-- Kurs Info Compact -->
                        <div class="mt-2 flex items-center gap-4 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10 p-2 rounded-lg border border-blue-100 dark:border-blue-900/30 w-fit" x-show="['USD', 'SAR', 'MYR'].includes(kurs)" x-cloak>
                            <div class="flex items-center gap-1.5" x-show="kurs === 'USD'">
                                <span class="opacity-70">Kurs USD Hari Ini:</span>
                                <span>Rp {{ number_format($kursUsd ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5" x-show="kurs === 'SAR'">
                                <span class="opacity-70">Kurs SAR Hari Ini:</span>
                                <span>Rp {{ number_format($kursSar ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5" x-show="kurs === 'MYR'">
                                <span class="opacity-70">Kurs RM Hari Ini:</span>
                                <span>Rp {{ number_format($kursMyr ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Harga Jual -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Jual</label>
                        <div class="relative">
                            <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium" x-text="currencySymbol">Rp</span>
                            <input type="number" name="harga_jual" x-model="harga_jual" min="0" step="0.01" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" required />
                        </div>
                        <div x-show="convertedJual" class="mt-1 flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400">
                            <span class="opacity-70">Estimasi:</span>
                            <span x-text="formatRupiah(convertedJual)"></span>
                        </div>
                        @error('harga_jual') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

                        <!-- Kurs Info Compact -->
                        <div class="mt-2 flex items-center gap-4 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10 p-2 rounded-lg border border-blue-100 dark:border-blue-900/30 w-fit" x-show="['USD', 'SAR', 'MYR'].includes(kurs)" x-cloak>
                            <div class="flex items-center gap-1.5" x-show="kurs === 'USD'">
                                <span class="opacity-70">Kurs USD Hari Ini:</span>
                                <span>Rp {{ number_format($kursUsd ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5" x-show="kurs === 'SAR'">
                                <span class="opacity-70">Kurs SAR Hari Ini:</span>
                                <span>Rp {{ number_format($kursSar ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5" x-show="kurs === 'MYR'">
                                <span class="opacity-70">Kurs RM Hari Ini:</span>
                                <span>Rp {{ number_format($kursMyr ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Layanan -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Layanan</label>
                        <select name="status_layanan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
                            <option value="Active" {{ old('status_layanan', $layanan->status_layanan) == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Non Active" {{ old('status_layanan', $layanan->status_layanan) == 'Non Active' ? 'selected' : '' }}>Non Active</option>
                        </select>
                        @error('status_layanan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Foto Layanan -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Layanan</label>
                        @if($layanan->foto_layanan)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $layanan->foto_layanan) }}" alt="Foto Layanan" class="w-20 h-20 rounded-lg object-cover">
                            </div>
                        @endif
                        <input type="file" name="foto_layanan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" accept="image/*" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Biarkan kosong jika tidak ingin mengubah foto</p>
                         @error('foto_layanan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan Layanan -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Layanan</label>
                        <textarea name="catatan_layanan" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="Masukkan Catatan (Opsional)">{{ old('catatan_layanan', $layanan->catatan_layanan) }}</textarea>
                        @error('catatan_layanan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('data-layanan') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
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
