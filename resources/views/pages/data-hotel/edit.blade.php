@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Data Hotel" :breadcrumbs="[
    ['label' => 'Data Hotel', 'url' => route('data-hotel')]
]" />
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        <div class="col-span-12">
            <x-common.component-card title="Form Edit Hotel">
                <form action="{{ route('data-hotel.update', $hotel->id) }}" method="POST" class="space-y-6" x-data="{
                    kurs: '{{ old('kurs', $hotel->kurs ?? 'IDR') }}',
                    harga: '{{ old('harga_hotel', $hotel->kurs !== 'IDR' ? $hotel->kurs_asing : $hotel->harga_hotel) }}',
                    biayaMakan: '{{ old('biaya_makan', ($hotel->kurs_biaya_makan ?? 'IDR') !== 'IDR' ? ($hotel->biaya_makan_asing ?? 0) : $hotel->biaya_makan) }}',
                    kursMakan: '{{ old('kurs_biaya_makan', $hotel->kurs_biaya_makan ?? 'IDR') }}',
                    custom_kurs: {{ old('custom_kurs', ($hotel->kurs != 'IDR' && $hotel->kurs_asing > 0 && $hotel->harga_hotel > 0) ? ($hotel->harga_hotel / $hotel->kurs_asing) : 'null') }},
                    custom_kurs_makan: {{ old('custom_kurs_biaya_makan', ($hotel->kurs_biaya_makan != 'IDR' && $hotel->biaya_makan_asing > 0 && $hotel->biaya_makan > 0) ? ($hotel->biaya_makan / $hotel->biaya_makan_asing) : 'null') }},
                    hasBiayaMakan: {{ ($hotel->biaya_makan > 0) ? 'true' : 'false' }},
                    kursUsd: {{ $kursUsd ?? 0 }},
                    kursSar: {{ $kursSar ?? 0 }},
                    kursMyr: {{ $kursMyr ?? 0 }},
                    updateCustomKurs() {
                        if (this.custom_kurs === null || this.custom_kurs === undefined) {
                            if (this.kurs === 'USD') this.custom_kurs = this.kursUsd;
                            else if (this.kurs === 'SAR') this.custom_kurs = this.kursSar;
                            else if (this.kurs === 'MYR') this.custom_kurs = this.kursMyr;
                            else this.custom_kurs = null;
                        }
                    },
                    updateCustomKursMakan() {
                        if (this.custom_kurs_makan === null || this.custom_kurs_makan === undefined) {
                            if (this.kursMakan === 'USD') this.custom_kurs_makan = this.kursUsd;
                            else if (this.kursMakan === 'SAR') this.custom_kurs_makan = this.kursSar;
                            else if (this.kursMakan === 'MYR') this.custom_kurs_makan = this.kursMyr;
                            else this.custom_kurs_makan = null;
                        }
                    },
                    init() {
                        this.updateCustomKurs();
                        this.updateCustomKursMakan();
                        this.$watch('kurs', (newValue) => {
                            this.custom_kurs = null;
                            this.updateCustomKurs();
                        });
                        this.$watch('kursMakan', (newValue) => {
                            this.custom_kurs_makan = null;
                            this.updateCustomKursMakan();
                        });
                    },
                    get currencySymbol() {
                        return this.kurs === 'IDR' ? 'Rp' : (this.kurs === 'MYR' ? 'RM' : this.kurs);
                    },
                    get currencySymbolMakan() {
                        return this.kursMakan === 'IDR' ? 'Rp' : (this.kursMakan === 'MYR' ? 'RM' : this.kursMakan);
                    },
                    get exchangeRate() {
                        return parseFloat(this.custom_kurs) || 1;
                    },
                    get exchangeRateMakan() {
                        return parseFloat(this.custom_kurs_makan) || 1;
                    },
                    get convertedPrice() {
                        if (this.kurs === 'IDR' || !this.harga) return null;
                        return this.harga * this.exchangeRate;
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
                        <!-- Kode Hotel (Readonly) -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kode Hotel <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode_hotel" value="{{ $hotel->kode_hotel }}" readonly
                                class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kode hotel tidak dapat diubah</p>
                        </div>

                        <!-- Nama Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nama Hotel <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_hotel" value="{{ old('nama_hotel', $hotel->nama_hotel) }}" placeholder="Masukkan nama hotel" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nama_hotel') border-red-500 @enderror" />
                            @error('nama_hotel')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lokasi Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Lokasi Hotel <span class="text-red-500">*</span>
                            </label>
                            <div x-data="{ isOptionSelected: true }" class="relative z-20 bg-transparent">
                                <select name="lokasi_hotel" required
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('lokasi_hotel') border-red-500 @enderror"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
                                    <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Pilih Lokasi
                                    </option>
                                    <option value="Mekkah" {{ old('lokasi_hotel', $hotel->lokasi_hotel) == 'Mekkah' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Mekkah
                                    </option>
                                    <option value="Madinah" {{ old('lokasi_hotel', $hotel->lokasi_hotel) == 'Madinah' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Madinah
                                    </option>
                                    <option value="Jeddah" {{ old('lokasi_hotel', $hotel->lokasi_hotel) == 'Jeddah' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Jeddah
                                    </option>
                                    <option value="Transit" {{ old('lokasi_hotel', $hotel->lokasi_hotel) == 'Transit' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Transit
                                    </option>
                                </select>
                                <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                            @error('lokasi_hotel')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kontak Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kontak Hotel <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="kontak_hotel" value="{{ old('kontak_hotel', $hotel->kontak_hotel) }}" placeholder="+966 12 345 6789" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('kontak_hotel') border-red-500 @enderror" />
                            @error('kontak_hotel')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Email Hotel <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email_hotel" value="{{ old('email_hotel', $hotel->email_hotel) }}" placeholder="info@hotel.com" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('email_hotel') border-red-500 @enderror" />
                            @error('email_hotel')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rating Hotel -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Rating Hotel <span class="text-red-500">*</span>
                            </label>
                            <div x-data="{ isOptionSelected: true }" class="relative z-20 bg-transparent">
                                <select name="rating_hotel" required
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('rating_hotel') border-red-500 @enderror"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
                                    <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Pilih Rating
                                    </option>
                                    <option value="1" {{ old('rating_hotel', $hotel->rating_hotel) == 1 ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        ⭐ Bintang 1
                                    </option>
                                    <option value="2" {{ old('rating_hotel', $hotel->rating_hotel) == 2 ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        ⭐⭐ Bintang 2
                                    </option>
                                    <option value="3" {{ old('rating_hotel', $hotel->rating_hotel) == 3 ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        ⭐⭐⭐ Bintang 3
                                    </option>
                                    <option value="4" {{ old('rating_hotel', $hotel->rating_hotel) == 4 ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        ⭐⭐⭐⭐ Bintang 4
                                    </option>
                                    <option value="5" {{ old('rating_hotel', $hotel->rating_hotel) == 5 ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        ⭐⭐⭐⭐⭐ Bintang 5
                                    </option>
                                </select>
                                <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                            @error('rating_hotel')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hotel Cost Details (Currency, Price & Meal) -->
                        <div class="col-span-full space-y-6">
                            <!-- Currency & Exchange Rate -->
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 max-w-2xl">
                                <!-- Mata Uang -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Mata Uang <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative z-20 bg-transparent">
                                        <select name="kurs" x-model="kurs" required
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                            <option value="IDR">IDR (Rupiah)</option>
                                            <option value="USD">USD (Dollar AS)</option>
                                            <option value="SAR">SAR (Riyal)</option>
                                            <option value="MYR">RM (Ringgit)</option>
                                        </select>
                                        <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                            <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <!-- Custom Kurs -->
                                <div x-show="['USD', 'SAR', 'MYR'].includes(kurs)" x-cloak x-transition>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Kurs <span x-text="kurs"></span> Hari Ini <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium">Rp</span>
                                        <input type="text" name="custom_kurs" :value="formatNumber(custom_kurs)" @input="$el.value = $el.value.replace(/\D/g, ''); custom_kurs = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(custom_kurs)" 
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" />
                                    </div>
                                </div>
                            </div>

                            <!-- Harga Hotel -->
                            <div class="max-w-md">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Harga Hotel (per malam) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative" x-data="{ 
                                    displayHarga: '',
                                    formatDisplay() {
                                        let val = this.harga.toString().split('.')[0].replace(/\D/g, '');
                                        this.displayHarga = val ? new Intl.NumberFormat('id-ID').format(val) : '';
                                    },
                                    updateRaw(val) {
                                        this.harga = val.replace(/\D/g, '');
                                        this.formatDisplay();
                                    }
                                }" x-init="formatDisplay()">
                                    <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium" x-text="currencySymbol">
                                        Rp
                                    </span>
                                    <input type="hidden" name="harga_hotel" x-model="harga">
                                    <input type="text" x-model="displayHarga" @input="updateRaw($event.target.value)" placeholder="2.500.000" required
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('harga_hotel') border-red-500 @enderror" />
                                </div>
                                @error('harga_hotel')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
    
                                <!-- Kurs Info Compact -->
                                <div class="mt-3 flex flex-wrap items-center gap-4 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10 p-2 rounded-lg border border-blue-100 dark:border-blue-900/30"
                                    x-show="convertedPrice"
                                    x-transition>
                                    
                                    <div class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                                        <span class="opacity-70">Estimasi:</span>
                                        <span x-text="formatRupiah(convertedPrice)"></span>
                                    </div>
                                </div>
                            </div>
    
                            <!-- Biaya Makan Section -->
                            <div class="space-y-4 border-t border-gray-100 pt-6 dark:border-gray-800">
                                <div class="flex items-center gap-2">
                                    <label class="relative inline-flex cursor-pointer items-center">
                                        <input type="checkbox" x-model="hasBiayaMakan" class="peer sr-only">
                                        <div class="peer h-6 w-11 rounded-full bg-gray-200 after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:border-gray-600 dark:bg-gray-700"></div>
                                    </label>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-400">Include Biaya Makan?</span>
                                </div>
    
                                <div x-show="hasBiayaMakan" x-cloak x-transition class="mt-4 space-y-4">
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 max-w-2xl">
                                        <!-- Currency Selector for Biaya Makan -->
                                        <div>
                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                Mata Uang (Makan)
                                            </label>
                                            <select name="kurs_biaya_makan" x-model="kursMakan" 
                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                <option value="IDR">IDR - Rupiah</option>
                                                <option value="USD">USD - Dollar</option>
                                                <option value="SAR">SAR - Rial</option>
                                                <option value="MYR">MYR - Ringgit</option>
                                            </select>
                                        </div>
    
                                        <!-- Custom Kurs for Biaya Makan -->
                                        <div x-show="kursMakan !== 'IDR'" x-transition>
                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                Kurs <span x-text="kursMakan"></span> Hari Ini <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium">
                                                    Rp
                                                </span>
                                                <input type="text" name="custom_kurs_biaya_makan" :value="formatNumber(custom_kurs_makan)" @input="$el.value = $el.value.replace(/\D/g, ''); custom_kurs_makan = $el.value === '' ? 0 : parseInt($el.value); $el.value = formatNumber(custom_kurs_makan)" 
                                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="0" />
                                            </div>
                                        </div>
                                    </div>
    
                                    <!-- Biaya Makan Input -->
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 max-w-2xl">
                                        <div>
                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                Biaya Makan (per hari)
                                            </label>
                                            <div class="relative" x-data="{ 
                                                displayBiayaMakanLocal: '',
                                                formatDisplayLocal() {
                                                    let val = this.biayaMakan.toString().replace(/\D/g, '');
                                                    this.displayBiayaMakanLocal = val ? new Intl.NumberFormat('id-ID').format(val) : '';
                                                },
                                                updateRawLocal(val) {
                                                    this.biayaMakan = val.replace(/\D/g, '');
                                                    this.formatDisplayLocal();
                                                }
                                            }" x-init="formatDisplayLocal(); $watch('biayaMakan', () => formatDisplayLocal())">
                                                <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium" x-text="currencySymbolMakan">
                                                    Rp
                                                </span>
                                                <input type="hidden" name="biaya_makan" x-model="biayaMakan">
                                                <input type="text" x-model="displayBiayaMakanLocal" @input="updateRawLocal($event.target.value)" placeholder="0" 
                                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('biaya_makan') border-red-500 @enderror" />
                                            </div>
                                            @error('biaya_makan')
                                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                            @enderror
    
                                            <!-- Kurs Info Compact for Biaya Makan -->
                                            <div class="mt-3 flex flex-wrap items-center gap-4 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10 p-2 rounded-lg border border-blue-100 dark:border-blue-900/30"
                                                x-show="kursMakan !== 'IDR' && kursMakan !== '' && biayaMakan > 0"
                                                x-transition>
                                                
                                                <div class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                                                    <span class="opacity-70">Estimasi:</span>
                                                    <span x-text="formatRupiah(biayaMakan * exchangeRateMakan)"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Catatan Hotel -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Catatan Hotel
                        </label>
                        <textarea name="catatan_hotel" placeholder="Masukkan catatan atau keterangan tambahan tentang hotel..." rows="4"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('catatan_hotel') border-red-500 @enderror">{{ old('catatan_hotel', $hotel->catatan_hotel) }}</textarea>
                        @error('catatan_hotel')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
                        <a href="{{ route('data-hotel') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.8334 10H4.16675M4.16675 10L10.0001 15.8333M4.16675 10L10.0001 4.16667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-blue-600">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Update Data Hotel
                        </button>
                    </div>
                </form>
            </x-common.component-card>
        </div>

    </div>
@endsection
