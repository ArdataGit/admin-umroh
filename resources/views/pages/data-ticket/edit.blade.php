@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Ticket" :breadcrumbs="[
    ['label' => 'Data Ticket', 'url' => route('data-ticket')],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <form action="{{ route('data-ticket.update', $ticket->id) }}" method="POST" enctype="multipart/form-data" x-data="{
            kurs: '{{ old('kurs', $ticket->kurs ?? 'IDR') }}',
            harga_modal: '{{ old('harga_modal', ($ticket->kurs != 'IDR' ? $ticket->harga_modal_asing : $ticket->harga_modal)) }}',
            harga_jual: '{{ old('harga_jual', ($ticket->kurs != 'IDR' ? $ticket->harga_jual_asing : $ticket->harga_jual)) }}',
            custom_kurs: {{ old('custom_kurs', ($ticket->kurs != 'IDR' && $ticket->harga_modal_asing > 0) ? ($ticket->harga_modal / $ticket->harga_modal_asing) : 'null') }},
            kursUsd: {{ $kursUsd ?? 0 }},
            kursSar: {{ $kursSar ?? 0 }},
            kursMyr: {{ $kursMyr ?? 0 }},
            init() {
                if (this.custom_kurs === null && ['USD', 'SAR', 'MYR'].includes(this.kurs)) {
                    if (this.kurs === 'USD') this.custom_kurs = this.kursUsd;
                    else if (this.kurs === 'SAR') this.custom_kurs = this.kursSar;
                    else if (this.kurs === 'MYR') this.custom_kurs = this.kursMyr;
                }
                this.$watch('kurs', (newValue) => {
                    if (newValue === 'USD') this.custom_kurs = this.kursUsd;
                    else if (newValue === 'SAR') this.custom_kurs = this.kursSar;
                    else if (newValue === 'MYR') this.custom_kurs = this.kursMyr;
                    else this.custom_kurs = null;
                });
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
        @method('PUT')

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Informasi Ticket</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Ticket (Opsional)</label>
                    @if($ticket->foto_tiket)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $ticket->foto_tiket) }}" alt="Current Photo" class="h-24 w-24 rounded-lg object-cover shadow-sm" />
                            <p class="mt-1 text-xs text-gray-500">Foto saat ini</p>
                        </div>
                    @endif
                    <input type="file" name="foto_tiket" accept="image/*" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maks: 2MB. Biarkan kosong jika tidak ingin mengubah foto.</p>
                    @error('foto_tiket') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Ticket</label>
                    <input type="text" name="kode_tiket" value="{{ $ticket->kode_tiket }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Ticket</label>
                    <input type="text" name="nama_tiket" value="{{ old('nama_tiket', $ticket->nama_tiket) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Tiket</label>
                    <select name="jenis_tiket" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="Ekonomi" {{ $ticket->jenis_tiket == 'Ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                        <option value="Bisnis" {{ $ticket->jenis_tiket == 'Bisnis' ? 'selected' : '' }}>Bisnis</option>
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Satuan Unit</label>
                    <select name="satuan_unit" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="Pax" {{ $ticket->satuan_unit == 'Pax' ? 'selected' : '' }}>Pax</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Ticket</label>
                    <select name="status_tiket" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="active" {{ $ticket->status_tiket == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="non-active" {{ $ticket->status_tiket == 'non-active' ? 'selected' : '' }}>Non-Active</option>
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
                            <option value="{{ $maskapai->id }}" {{ $ticket->maskapai_id == $maskapai->id ? 'selected' : '' }}>{{ $maskapai->nama_maskapai }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Maskapai (Manual)</label>
                    <input type="text" name="kode_maskapai" value="{{ old('kode_maskapai', $ticket->kode_maskapai) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Rute Tiket</label>
                    <input type="text" name="rute_tiket" value="{{ old('rute_tiket', $ticket->rute_tiket) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode PNR</label>
                    <input type="text" name="kode_pnr" value="{{ old('kode_pnr', $ticket->kode_pnr) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Tiket (Pax)</label>
                    <input type="number" name="jumlah_tiket" value="{{ old('jumlah_tiket', $ticket->jumlah_tiket) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Keberangkatan</label>
                    <input type="date" name="tanggal_keberangkatan" value="{{ old('tanggal_keberangkatan', $ticket->tanggal_keberangkatan) }}" min="1900-01-01" max="9999-12-31" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Kepulangan</label>
                    <input type="date" name="tanggal_kepulangan" value="{{ old('tanggal_kepulangan', $ticket->tanggal_kepulangan) }}" min="1900-01-01" max="9999-12-31" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Hari</label>
                    <input type="number" name="jumlah_hari" value="{{ old('jumlah_hari', $ticket->jumlah_hari) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
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

                <!-- Custom Kurs -->
                <div class="md:col-span-2" x-show="['USD', 'SAR', 'MYR'].includes(kurs)" x-cloak>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kurs <span x-text="kurs"></span> Hari Ini</label>
                    <div class="relative">
                        <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium">Rp</span>
                        <input type="text" name="custom_kurs" :value="formatNumber(custom_kurs)" @input="$el.value = $el.value.replace(/\D/g, ''); custom_kurs = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(custom_kurs)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" />
                    </div>
                </div>

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
            </div>
        </div>

         <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Informasi Tambahan</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Tiket 1</label>
                    <input type="text" name="kode_tiket_1" value="{{ old('kode_tiket_1', $ticket->kode_tiket_1) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Tiket 2</label>
                    <input type="text" name="kode_tiket_2" value="{{ old('kode_tiket_2', $ticket->kode_tiket_2) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Tiket 3</label>
                    <input type="text" name="kode_tiket_3" value="{{ old('kode_tiket_3', $ticket->kode_tiket_3) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Tiket 4</label>
                    <input type="text" name="kode_tiket_4" value="{{ old('kode_tiket_4', $ticket->kode_tiket_4) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                </div>
                 <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Tiket</label>
                    <textarea name="catatan_tiket" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">{{ old('catatan_tiket', $ticket->catatan_tiket) }}</textarea>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('data-ticket') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Simpan Perubahan</button>
        </div>
        </form>
    </div>
</div>
@endsection
