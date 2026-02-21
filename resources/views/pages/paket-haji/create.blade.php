@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Paket Haji" :breadcrumbs="[
    ['label' => 'Data Paket', 'url' => route('paket-haji')],
    ['label' => 'Tambah', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <form action="{{ route('paket-haji.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-gray-800 dark:text-red-400" role="alert">
                <div class="flex items-center mb-2">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="font-medium">Terdapat kesalahan pada input:</span>
                </div>
                <ul class="mt-1.5 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Informasi Dasar</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Paket</label>
                    <input type="text" name="kode_paket" value="{{ $kodePaket }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Paket</label>
                    <input type="text" name="nama_paket" value="{{ old('nama_paket') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Keberangkatan</label>
                    <input type="text" id="tanggal_keberangkatan" name="tanggal_keberangkatan" value="{{ old('tanggal_keberangkatan') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" placeholder="dd/mm/yy" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Hari</label>
                    <input type="number" name="jumlah_hari" value="{{ old('jumlah_hari') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Paket</label>
                    <select name="status_paket" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kuota Jamaah (Pax)</label>
                    <input type="number" name="kuota_jamaah" value="{{ old('kuota_jamaah') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                </div>
                
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Maskapai</label>
                    <select name="maskapai_id" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        @foreach($maskapais as $maskapai)
                            <option value="{{ $maskapai->id }}">{{ $maskapai->nama_maskapai }}</option>
                        @endforeach
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Rute Penerbangan</label>
                    <select name="rute_penerbangan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="direct">Direct</option>
                        <option value="transit">Transit</option>
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Lokasi Keberangkatan</label>
                    <select name="lokasi_keberangkatan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        @foreach($kotas as $kota)
                            <option value="{{ $kota->nama_kota }}" {{ old('lokasi_keberangkatan') == $kota->nama_kota ? 'selected' : '' }}>{{ $kota->nama_kota }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Variant 1 -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Varian Paket 1</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Paket (Varian 1)</label>
                    <input type="text" name="jenis_paket_1" value="{{ old('jenis_paket_1') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" placeholder="Contoh: Quad, Triple, etc." required />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hotel Mekkah</label>
                    <select name="hotel_mekkah_1" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        @foreach($hotelsMekkah as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
                        @endforeach
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hotel Madinah</label>
                     <select name="hotel_madinah_1" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        @foreach($hotelsMadinah as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
                        @endforeach
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hotel Transit</label>
                    <select name="hotel_transit_1" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="">Tidak Ada</option>
                        @foreach($hotelsTransit as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                     <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga HPP</label>
                    <input type="number" name="harga_hpp_1" value="{{ old('harga_hpp_1') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                    @error('harga_hpp_1') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                     <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Quad</label>
                    <input type="number" name="harga_quad_1" value="{{ old('harga_quad_1') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                    @error('harga_quad_1') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                     <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Triple</label>
                    <input type="number" name="harga_triple_1" value="{{ old('harga_triple_1') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                    @error('harga_triple_1') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                     <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Double</label>
                    <input type="number" name="harga_double_1" value="{{ old('harga_double_1') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" required />
                    @error('harga_double_1') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Variant 2 -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Varian Paket 2 (Optional)</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Paket (Varian 2)</label>
                    <input type="text" name="jenis_paket_2" value="{{ old('jenis_paket_2') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hotel Mekkah</label>
                    <select name="hotel_mekkah_2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                         <option value="">Pilih Hotel</option>
                        @foreach($hotelsMekkah as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
                        @endforeach
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hotel Madinah</label>
                     <select name="hotel_madinah_2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                         <option value="">Pilih Hotel</option>
                        @foreach($hotelsMadinah as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
                        @endforeach
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hotel Transit</label>
                    <select name="hotel_transit_2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        <option value="">Tidak Ada</option>
                        @foreach($hotelsTransit as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                     <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga HPP</label>
                    <input type="number" name="harga_hpp_2" value="{{ old('harga_hpp_2') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                    @error('harga_hpp_2') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                     <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Quad</label>
                    <input type="number" name="harga_quad_2" value="{{ old('harga_quad_2') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                    @error('harga_quad_2') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                     <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Triple</label>
                    <input type="number" name="harga_triple_2" value="{{ old('harga_triple_2') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                    @error('harga_triple_2') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                     <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Double</label>
                    <input type="number" name="harga_double_2" value="{{ old('harga_double_2') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm" />
                    @error('harga_double_2') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Details -->
         <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Detail Paket</h3>
            <div class="grid grid-cols-1 gap-6">
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Termasuk Paket (Include)</label>
                    <textarea name="termasuk_paket" rows="4" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">{{ old('termasuk_paket') }}</textarea>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tidak Termasuk Paket (Exclude)</label>
                    <textarea name="tidak_termasuk_paket" rows="4" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">{{ old('tidak_termasuk_paket') }}</textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Syarat & Ketentuan</label>
                    <textarea name="syarat_ketentuan" rows="4" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">{{ old('syarat_ketentuan') }}</textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Paket</label>
                    <textarea name="catatan_paket" rows="2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">{{ old('catatan_paket') }}</textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Brosur</label>
                    <input type="file" name="foto_brosur" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm" accept="image/*" />
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('paket-haji') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600">Simpan Paket</button>
        </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#tanggal_keberangkatan", {
            altInput: true,
            altFormat: "d/m/y",
            dateFormat: "Y-m-d",
            allowInput: true
        });
    });
</script>
@endpush
