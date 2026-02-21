@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Ticket" :breadcrumbs="[
    ['label' => 'Data Ticket', 'url' => route('data-ticket')],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <form action="{{ route('data-ticket.store') }}" method="POST" enctype="multipart/form-data" x-data="{
            kurs: 'IDR',
            harga_modal: '',
            harga_jual: '',
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
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Informasi Ticket</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Ticket (Opsional)</label>
                    <input type="file" name="foto_tiket" accept="image/*" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maks: 2MB</p>
                    @error('foto_tiket') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Ticket</label>
                    <input type="text" name="kode_tiket" value="{{ $kodeTiket }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Ticket</label>
                    <input type="text" name="nama_tiket" value="{{ old('nama_tiket') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Tiket</label>
                    <select name="jenis_tiket" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="Ekonomi">Ekonomi</option>
                        <option value="Bisnis">Bisnis</option>
                        <option value="ECO">ECO</option>
                        <option value="BUS">BUS</option>
                        <option value="INF">INF</option>
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Satuan Unit</label>
                    <select name="satuan_unit" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="Pax">Pax</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Ticket</label>
                    <select name="status_tiket" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="active">Active</option>
                        <option value="non-active">Non-Active</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Detail Penerbangan</h3>
             <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Maskapai (System)</label>
                    <select name="maskapai_id" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        @foreach($maskapais as $maskapai)
                            <option value="{{ $maskapai->id }}">{{ $maskapai->nama_maskapai }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Maskapai (Manual)</label>
                    <input type="text" name="kode_maskapai" value="{{ old('kode_maskapai') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Rute Tiket</label>
                    <input type="text" name="rute_tiket" value="{{ old('rute_tiket') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode PNR</label>
                    <input type="text" name="kode_pnr" value="{{ old('kode_pnr') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Tiket (Pax)</label>
                    <input type="number" name="jumlah_tiket" value="{{ old('jumlah_tiket') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Keberangkatan</label>
                    <input type="date" name="tanggal_keberangkatan" value="{{ old('tanggal_keberangkatan') }}" min="1900-01-01" max="9999-12-31" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Kepulangan</label>
                    <input type="date" name="tanggal_kepulangan" value="{{ old('tanggal_kepulangan') }}" min="1900-01-01" max="9999-12-31" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Hari</label>
                    <input type="number" name="jumlah_hari" value="{{ old('jumlah_hari') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Harga</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Kurs -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Mata Uang</label>
                        <select name="kurs" x-model="kurs" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
                            <option value="IDR">IDR (Rupiah)</option>
                            <option value="USD">USD (Dollar AS)</option>
                            <option value="SAR">SAR (Riyal)</option>
                            <option value="MYR">RM (Ringgit)</option>
                        </select>
                    </div>

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
            </div>
        </div>

         <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Informasi Tambahan</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Tiket 1</label>
                    <input type="text" name="kode_tiket_1" value="{{ old('kode_tiket_1') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Tiket 2</label>
                    <input type="text" name="kode_tiket_2" value="{{ old('kode_tiket_2') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Tiket 3</label>
                    <input type="text" name="kode_tiket_3" value="{{ old('kode_tiket_3') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Tiket 4</label>
                    <input type="text" name="kode_tiket_4" value="{{ old('kode_tiket_4') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                </div>
                 <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Tiket</label>
                    <textarea name="catatan_tiket" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">{{ old('catatan_tiket') }}</textarea>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('data-ticket') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Simpan Ticket</button>
        </div>
        </form>
    </div>
</div>
@endsection
