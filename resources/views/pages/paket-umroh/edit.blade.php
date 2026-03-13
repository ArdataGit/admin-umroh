@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Paket Umroh" :breadcrumbs="[
    ['label' => 'Data Paket', 'url' => route('paket-umroh')],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
        <form action="{{ route('paket-umroh.update', $paketUmroh->id) }}" method="POST" enctype="multipart/form-data" 
            x-data="{ 
                formatNumber(num) {
                    if (!num && num !== 0) return '';
                    return new Intl.NumberFormat('id-ID').format(Math.round(num));
                },
                maskapai_price: {{ $paketUmroh->maskapai->harga_tiket ?? 0 }},
                service_prices: {
                    @foreach($paketUmroh->layanans as $layanan)
                        {{ $layanan->id }}: {{ $layanan->harga_jual }},
                    @endforeach
                },
                v1: {
                    mekkah_p: {{ $paketUmroh->hotelMekkah1->harga_hotel ?? 0 }},
                    mekkah_m: {{ $paketUmroh->hotelMekkah1->biaya_makan ?? 0 }},
                    mekkah_d: {{ $paketUmroh->hari_mekkah_1 ?? 0 }},
                    madinah_p: {{ $paketUmroh->hotelMadinah1->harga_hotel ?? 0 }},
                    madinah_m: {{ $paketUmroh->hotelMadinah1->biaya_makan ?? 0 }},
                    madinah_d: {{ $paketUmroh->hari_madinah_1 ?? 0 }},
                    transit_p: {{ $paketUmroh->hotelTransit1->harga_hotel ?? 0 }},
                    transit_m: {{ $paketUmroh->hotelTransit1->biaya_makan ?? 0 }},
                    transit_d: {{ $paketUmroh->hari_transit_1 ?? 0 }},
                    hpp_q: {{ $paketUmroh->hpp_quad1 ?? 0 }},
                    hpp_t: {{ $paketUmroh->hpp_triple1 ?? 0 }},
                    hpp_d: {{ $paketUmroh->hpp_double1 ?? 0 }},
                    quad_jual: {{ $paketUmroh->harga_quad_1 ?? 0 }},
                    triple_jual: {{ $paketUmroh->harga_triple_1 ?? 0 }},
                    double_jual: {{ $paketUmroh->harga_double_1 ?? 0 }}
                },
                v2: {
                    mekkah_p: {{ $paketUmroh->hotelMekkah2->harga_hotel ?? 0 }},
                    mekkah_m: {{ $paketUmroh->hotelMekkah2->biaya_makan ?? 0 }},
                    mekkah_d: {{ $paketUmroh->hari_mekkah_2 ?? 0 }},
                    madinah_p: {{ $paketUmroh->hotelMadinah2->harga_hotel ?? 0 }},
                    madinah_m: {{ $paketUmroh->hotelMadinah2->biaya_makan ?? 0 }},
                    madinah_d: {{ $paketUmroh->hari_madinah_2 ?? 0 }},
                    transit_p: {{ $paketUmroh->hotelTransit2->harga_hotel ?? 0 }},
                    transit_m: {{ $paketUmroh->hotelTransit2->biaya_makan ?? 0 }},
                    transit_d: {{ $paketUmroh->hari_transit_2 ?? 0 }},
                    hpp_q: {{ $paketUmroh->hpp_quad2 ?? 0 }},
                    hpp_t: {{ $paketUmroh->hpp_triple2 ?? 0 }},
                    hpp_d: {{ $paketUmroh->hpp_double2 ?? 0 }},
                    quad_jual: {{ $paketUmroh->harga_quad_2 ?? 0 }},
                    triple_jual: {{ $paketUmroh->harga_triple_2 ?? 0 }},
                    double_jual: {{ $paketUmroh->harga_double_2 ?? 0 }}
                },
                get serviceTotal() {
                    return Object.values(this.service_prices).reduce((a, b) => a + b, 0);
                },
                calculateHPP(variant) {
                    const ctx = variant === 1 ? this.v1 : this.v2;
                    const mekkah_p = parseFloat(ctx.mekkah_p) || 0;
                    const mekkah_m = parseFloat(ctx.mekkah_m) || 0;
                    const mekkah_d = parseFloat(ctx.mekkah_d) || 0;
                    
                    const madinah_p = parseFloat(ctx.madinah_p) || 0;
                    const madinah_m = parseFloat(ctx.madinah_m) || 0;
                    const madinah_d = parseFloat(ctx.madinah_d) || 0;
                    
                    const transit_p = parseFloat(ctx.transit_p) || 0;
                    const transit_m = parseFloat(ctx.transit_m) || 0;
                    const transit_d = parseFloat(ctx.transit_d) || 0;

                    const hotelTotal = (mekkah_p * mekkah_d) + (madinah_p * madinah_d) + (transit_p * transit_d);
                    const mealTotal = (mekkah_m * mekkah_d) + (madinah_m * madinah_d) + (transit_m * transit_d);
                    const base = (parseFloat(this.maskapai_price) || 0) + this.serviceTotal;
                    
                    ctx.hpp_q = base + (hotelTotal / 4) + mealTotal;
                    ctx.hpp_t = base + (hotelTotal / 3) + mealTotal;
                    ctx.hpp_d = base + (hotelTotal / 2) + mealTotal;
                }
            }" x-init="calculateHPP(1); calculateHPP(2)">
        @csrf
        @method('PUT')

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
                    <input type="text" name="kode_paket" value="{{ $paketUmroh->kode_paket }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Paket</label>
                    <input type="text" name="nama_paket" value="{{ old('nama_paket', $paketUmroh->nama_paket) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Keberangkatan</label>
                    <input type="date" name="tanggal_keberangkatan" value="{{ old('tanggal_keberangkatan', $paketUmroh->tanggal_keberangkatan) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Hari</label>
                    <input type="number" name="jumlah_hari" value="{{ old('jumlah_hari', $paketUmroh->jumlah_hari) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Paket</label>
                    <select name="status_paket" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                        <option value="active" {{ $paketUmroh->status_paket == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ $paketUmroh->status_paket == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Kuota Jamaah (Pax)</label>
                    <input type="number" name="kuota_jamaah" value="{{ old('kuota_jamaah', $paketUmroh->kuota_jamaah) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                </div>
                
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Maskapai</label>
                    <select name="maskapai_id" @change="maskapai_price = $el.options[$el.selectedIndex].dataset.price || 0; calculateHPP(1); calculateHPP(2)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                        @foreach($maskapais as $maskapai)
                            <option value="{{ $maskapai->id }}" data-price="{{ $maskapai->harga_tiket }}" {{ $paketUmroh->maskapai_id == $maskapai->id ? 'selected' : '' }}>{{ $maskapai->nama_maskapai }}</option>
                        @endforeach
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Rute Penerbangan</label>
                    <select name="rute_penerbangan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                        <option value="direct" {{ $paketUmroh->rute_penerbangan == 'direct' ? 'selected' : '' }}>Direct</option>
                        <option value="transit" {{ $paketUmroh->rute_penerbangan == 'transit' ? 'selected' : '' }}>Transit</option>
                    </select>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Lokasi Keberangkatan</label>
                    <select name="lokasi_keberangkatan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                        @foreach($kotas as $kota)
                            <option value="{{ $kota->nama_kota }}" {{ (old('lokasi_keberangkatan', $paketUmroh->lokasi_keberangkatan) == $kota->nama_kota) ? 'selected' : '' }}>{{ $kota->nama_kota }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Edit Layanan -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Layanan Tambahan</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $selectedLayanans = old('layanan_ids', $paketUmroh->layanans->pluck('id')->toArray());
                @endphp
                @foreach($layanans as $layanan)
                <div class="flex items-start gap-3 rounded-lg border border-gray-100 p-4 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-white/[0.03]">
                    <div class="flex h-5 items-center">
                        <input type="checkbox" name="layanan_ids[]" value="{{ $layanan->id }}" id="layanan_{{ $layanan->id }}" 
                            @change="if($el.checked) service_prices[{{ $layanan->id }}] = {{ $layanan->harga_jual }}; else delete service_prices[{{ $layanan->id }}]; calculateHPP(1); calculateHPP(2)"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900" {{ in_array($layanan->id, $selectedLayanans) ? 'checked' : '' }}>
                    </div>
                    <label for="layanan_{{ $layanan->id }}" class="flex flex-col gap-0.5 cursor-pointer">
                        <span class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $layanan->nama_layanan }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $layanan->jenis_layanan }} - Rp {{ number_format($layanan->harga_jual, 0, ',', '.') }}</span>
                    </label>
                </div>
                @endforeach
            </div>
            @if($layanans->isEmpty())
                <p class="text-sm text-gray-500 italic">Tidak ada layanan aktif tersedia.</p>
            @endif
        </div>

        <!-- Variant 1 -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Varian Paket 1</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Paket (Varian 1)</label>
                    <input type="text" name="jenis_paket_1" value="{{ old('jenis_paket_1', $paketUmroh->jenis_paket_1) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hotel Mekkah</label>
                        <select name="hotel_mekkah_1" @change="v1.mekkah_p = $el.options[$el.selectedIndex].dataset.price || 0; v1.mekkah_m = $el.options[$el.selectedIndex].dataset.meal || 0; calculateHPP(1)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            @foreach($hotelsMekkah as $hotel)
                                <option value="{{ $hotel->id }}" data-price="{{ $hotel->harga_hotel }}" data-meal="{{ $hotel->biaya_makan }}" {{ $paketUmroh->hotel_mekkah_1 == $hotel->id ? 'selected' : '' }}>{{ $hotel->nama_hotel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hari Mekkah</label>
                        <input type="number" name="hari_mekkah_1" x-model="v1.mekkah_d" @input="calculateHPP(1)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hotel Madinah</label>
                        <select name="hotel_madinah_1" @change="v1.madinah_p = $el.options[$el.selectedIndex].dataset.price || 0; v1.madinah_m = $el.options[$el.selectedIndex].dataset.meal || 0; calculateHPP(1)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
                            @foreach($hotelsMadinah as $hotel)
                                <option value="{{ $hotel->id }}" data-price="{{ $hotel->harga_hotel }}" data-meal="{{ $hotel->biaya_makan }}" {{ $paketUmroh->hotel_madinah_1 == $hotel->id ? 'selected' : '' }}>{{ $hotel->nama_hotel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hari Madinah</label>
                        <input type="number" name="hari_madinah_1" x-model="v1.madinah_d" @input="calculateHPP(1)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hotel Transit</label>
                        <select name="hotel_transit_1" @change="v1.transit_p = $el.options[$el.selectedIndex].dataset.price || 0; v1.transit_m = $el.options[$el.selectedIndex].dataset.meal || 0; calculateHPP(1)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            <option value="">Tidak Ada</option>
                            @foreach($hotelsTransit as $hotel)
                                <option value="{{ $hotel->id }}" data-price="{{ $hotel->harga_hotel }}" data-meal="{{ $hotel->biaya_makan }}" {{ $paketUmroh->hotel_transit_1 == $hotel->id ? 'selected' : '' }}>{{ $hotel->nama_hotel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hari Transit</label>
                        <input type="number" name="hari_transit_1" x-model="v1.transit_d" @input="calculateHPP(1)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>
                </div>
                <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="hidden" name="harga_hpp_1" value="0">

                    <div class="hidden">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">HPP Quad</label>
                        <input type="hidden" name="hpp_quad1" :value="v1.hpp_q">
                        <input type="text" :value="formatNumber(v1.hpp_q)" class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" readonly />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Quad (Jual)</label>
                        <input type="hidden" name="harga_quad_1" :value="v1.quad_jual">
                        <input type="text" :value="formatNumber(v1.quad_jual)" @input="$el.value = $el.value.replace(/\D/g, ''); v1.quad_jual = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(v1.quad_jual)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                        @error('harga_quad_1') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="hidden">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">HPP Triple</label>
                        <input type="hidden" name="hpp_triple1" :value="v1.hpp_t">
                        <input type="text" :value="formatNumber(v1.hpp_t)" class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" readonly />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Triple (Jual)</label>
                        <input type="hidden" name="harga_triple_1" :value="v1.triple_jual">
                        <input type="text" :value="formatNumber(v1.triple_jual)" @input="$el.value = $el.value.replace(/\D/g, ''); v1.triple_jual = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(v1.triple_jual)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                        @error('harga_triple_1') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="hidden">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">HPP Double</label>
                        <input type="hidden" name="hpp_double1" :value="v1.hpp_d">
                        <input type="text" :value="formatNumber(v1.hpp_d)" class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" readonly />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Double (Jual)</label>
                        <input type="hidden" name="harga_double_1" :value="v1.double_jual">
                        <input type="text" :value="formatNumber(v1.double_jual)" @input="$el.value = $el.value.replace(/\D/g, ''); v1.double_jual = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(v1.double_jual)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required />
                        @error('harga_double_1') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Variant 2 -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Varian Paket 2 (Optional)</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Paket (Varian 2)</label>
                    <input type="text" name="jenis_paket_2" value="{{ old('jenis_paket_2', $paketUmroh->jenis_paket_2) }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hotel Mekkah</label>
                        <select name="hotel_mekkah_2" @change="v2.mekkah_p = $el.options[$el.selectedIndex].dataset.price || 0; v2.mekkah_m = $el.options[$el.selectedIndex].dataset.meal || 0; calculateHPP(2)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                             <option value="">Pilih Hotel</option>
                            @foreach($hotelsMekkah as $hotel)
                                <option value="{{ $hotel->id }}" data-price="{{ $hotel->harga_hotel }}" data-meal="{{ $hotel->biaya_makan }}" {{ $paketUmroh->hotel_mekkah_2 == $hotel->id ? 'selected' : '' }}>{{ $hotel->nama_hotel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hari Mekkah</label>
                        <input type="number" name="hari_mekkah_2" x-model="v2.mekkah_d" @input="calculateHPP(2)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hotel Madinah</label>
                         <select name="hotel_madinah_2" @change="v2.madinah_p = $el.options[$el.selectedIndex].dataset.price || 0; v2.madinah_m = $el.options[$el.selectedIndex].dataset.meal || 0; calculateHPP(2)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                             <option value="">Pilih Hotel</option>
                            @foreach($hotelsMadinah as $hotel)
                                <option value="{{ $hotel->id }}" data-price="{{ $hotel->harga_hotel }}" data-meal="{{ $hotel->biaya_makan }}" {{ $paketUmroh->hotel_madinah_2 == $hotel->id ? 'selected' : '' }}>{{ $hotel->nama_hotel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hari Madinah</label>
                        <input type="number" name="hari_madinah_2" x-model="v2.madinah_d" @input="calculateHPP(2)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hotel Transit</label>
                        <select name="hotel_transit_2" @change="v2.transit_p = $el.options[$el.selectedIndex].dataset.price || 0; v2.transit_m = $el.options[$el.selectedIndex].dataset.meal || 0; calculateHPP(2)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            <option value="">Tidak Ada</option>
                            @foreach($hotelsTransit as $hotel)
                                <option value="{{ $hotel->id }}" data-price="{{ $hotel->harga_hotel }}" data-meal="{{ $hotel->biaya_makan }}" {{ $paketUmroh->hotel_transit_2 == $hotel->id ? 'selected' : '' }}>{{ $hotel->nama_hotel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Hari Transit</label>
                        <input type="number" name="hari_transit_2" x-model="v2.transit_d" @input="calculateHPP(2)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>
                </div>
                <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="hidden" name="harga_hpp_2" value="0">

                    <div class="hidden">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">HPP Quad</label>
                        <input type="hidden" name="hpp_quad2" :value="v2.hpp_q">
                        <input type="text" :value="formatNumber(v2.hpp_q)" class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" readonly />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Quad (Jual)</label>
                        <input type="hidden" name="harga_quad_2" :value="v2.quad_jual">
                        <input type="text" :value="formatNumber(v2.quad_jual)" @input="$el.value = $el.value.replace(/\D/g, ''); v2.quad_jual = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(v2.quad_jual)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <div class="hidden">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">HPP Triple</label>
                        <input type="hidden" name="hpp_triple2" :value="v2.hpp_t">
                        <input type="text" :value="formatNumber(v2.hpp_t)" class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" readonly />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Triple (Jual)</label>
                        <input type="hidden" name="harga_triple_2" :value="v2.triple_jual">
                        <input type="text" :value="formatNumber(v2.triple_jual)" @input="$el.value = $el.value.replace(/\D/g, ''); v2.triple_jual = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(v2.triple_jual)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <div class="hidden">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">HPP Double</label>
                        <input type="hidden" name="hpp_double2" :value="v2.hpp_d">
                        <input type="text" :value="formatNumber(v2.hpp_d)" class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" readonly />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Double (Jual)</label>
                        <input type="hidden" name="harga_double_2" :value="v2.double_jual">
                        <input type="text" :value="formatNumber(v2.double_jual)" @input="$el.value = $el.value.replace(/\D/g, ''); v2.double_jual = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(v2.double_jual)" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
         <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 mb-6">
            <h3 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white">Detail Paket</h3>
            <div class="grid grid-cols-1 gap-6">
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Termasuk Paket (Include)</label>
                    <textarea name="termasuk_paket" rows="4" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('termasuk_paket', $paketUmroh->termasuk_paket) }}</textarea>
                </div>
                 <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Tidak Termasuk Paket (Exclude)</label>
                    <textarea name="tidak_termasuk_paket" rows="4" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('tidak_termasuk_paket', $paketUmroh->tidak_termasuk_paket) }}</textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Syarat & Ketentuan</label>
                    <textarea name="syarat_ketentuan" rows="4" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('syarat_ketentuan', $paketUmroh->syarat_ketentuan) }}</textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Paket</label>
                    <textarea name="catatan_paket" rows="2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('catatan_paket', $paketUmroh->catatan_paket) }}</textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Brosur</label>
                    @if($paketUmroh->foto_brosur)
                         <img src="{{ asset('storage/' . $paketUmroh->foto_brosur) }}" class="mb-2 h-20 rounded-md object-cover">
                    @endif
                    <input type="file" name="foto_brosur" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" accept="image/*" />
                </div>
            </div>
        </div>


        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('paket-umroh') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">Batal</a>
            <button type="submit" class="rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600 focus:ring-4 focus:ring-blue-500/20">Simpan Perubahan</button>
        </div>
        </form>
    </div>
</div>
@endsection
