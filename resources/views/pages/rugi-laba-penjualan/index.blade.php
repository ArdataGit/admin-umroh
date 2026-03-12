@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Laporan Rugi Laba Penjualan" :breadcrumbs="[
    ['label' => 'Rugi Laba Penjualan', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 space-y-4">
        
        <!-- Header & Filters -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Rugi Laba Penjualan</h2>
            
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('rugi-laba-penjualan.index', ['period' => 'today']) }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $currentPeriod == 'today' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                   Hari-ini
                </a>
                <a href="{{ route('rugi-laba-penjualan.index', ['period' => 'month']) }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $currentPeriod == 'month' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                   Bulan-ini
                </a>
                <a href="{{ route('rugi-laba-penjualan.index', ['period' => 'year']) }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $currentPeriod == 'year' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                   Tahun-ini
                </a>
                
                <!-- Custom Date Trigger (using Alpine for modal or simple redirect - keeping simple for now) -->
                <div x-data="{ open: {{ $currentPeriod == 'custom' ? 'true' : 'false' }}, start: '{{ $startDate }}', end: '{{ $endDate }}' }" class="relative">
                    <button @click="open = !open" 
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $currentPeriod == 'custom' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                        Kustom
                    </button>
                    
                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-72 rounded-xl border border-gray-200 bg-white p-4 shadow-xl z-50 dark:border-gray-800 dark:bg-gray-900"
                         style="display: none;">
                        <form action="{{ route('rugi-laba-penjualan.index') }}" method="GET" class="space-y-3">
                            <input type="hidden" name="period" value="custom">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Mulai Tanggal</label>
                                <input type="date" name="start_date" x-model="start" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm dark:bg-gray-800 dark:border-gray-700">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                                <input type="date" name="end_date" x-model="end" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm dark:bg-gray-800 dark:border-gray-700">
                            </div>
                            <button type="submit" class="w-full rounded-lg bg-blue-600 py-2 text-sm text-white hover:bg-blue-700">Terapkan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Table Card -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-gray-900" x-data="{ showRevenueDetail: false, showCOGSDetail: false, showGeneralIncomeDetail: false, showGeneralExpenseDetail: false }">
            <div class="p-6">                <!-- Period Label -->
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Periode</p>
                    <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $periodLabel }}</p>
                </div>

                <!-- Main Summary Table -->
                <div class="overflow-hidden rounded-lg border border-gray-100 dark:border-gray-800">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-500 dark:text-gray-400">Keterangan</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold text-gray-500 dark:text-gray-400">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <!-- Pendapatan (Revenue) -->
                            <tr class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50" @click="showRevenueDetail = !showRevenueDetail">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 transition-transform duration-200" :class="showRevenueDetail ? 'rotate-90' : ''">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-700 dark:text-gray-200">Pendapatan (revenue)</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-lg font-bold text-gray-800 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                                </td>
                            </tr>

                            <!-- Revenue Breakdown (Hidden by default) -->
                            <tr x-show="showRevenueDetail" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="bg-gray-50/50 dark:bg-gray-800/30"
                                style="display: none;"
                            >
                                <td colspan="2" class="px-0 py-0">
                                    <div class="border-l-4 border-blue-500 ml-6 my-2">
                                        <table class="w-full">
                                            <tbody class="divide-y divide-gray-100/50 dark:divide-gray-800/50">
                                                @foreach($revenueSubTotals as $type => $amount)
                                                @if($amount > 0)
                                                <tr class="hover:bg-white/50 dark:hover:bg-gray-900/30 transition-colors">
                                                    <td class="px-8 py-2.5">
                                                        <div class="flex items-center gap-3">
                                                            <div class="h-1.5 w-1.5 rounded-full shadow-sm
                                                                {{ str_contains($type, 'Tiket') ? 'bg-blue-400' : '' }}
                                                                {{ str_contains($type, 'Layanan') ? 'bg-purple-400' : '' }}
                                                                {{ str_contains($type, 'Produk') ? 'bg-orange-400' : '' }}
                                                                {{ str_contains($type, 'Umroh') ? 'bg-green-400' : '' }}
                                                                {{ str_contains($type, 'Haji') ? 'bg-emerald-400' : '' }}
                                                            "></div>
                                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $type }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-2.5 text-right">
                                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ number_format($amount, 0, ',', '.') }}</span>
                                                    </td>
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Harga Pokok Penjualan (HPP) -->
                            <tr class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50" @click="showCOGSDetail = !showCOGSDetail">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 transition-transform duration-200" :class="showCOGSDetail ? 'rotate-90' : ''">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-700 dark:text-gray-200">harga pokok penjualan</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-lg font-bold text-red-500">Rp {{ number_format($totalCOGS, 0, ',', '.') }}</span>
                                </td>
                            </tr>

                            <!-- COGS Breakdown (Hidden by default) -->
                            <tr x-show="showCOGSDetail" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="bg-gray-50/50 dark:bg-gray-800/30"
                                style="display: none;"
                            >
                                <td colspan="2" class="px-0 py-0">
                                    <div class="border-l-4 border-red-500 ml-6 my-2">
                                        <table class="w-full">
                                            <tbody class="divide-y divide-gray-100/50 dark:divide-gray-800/50">
                                                @foreach($cogsSubTotals as $type => $amount)
                                                <tr class="hover:bg-white/50 dark:hover:bg-gray-900/30 transition-colors">
                                                    <td class="px-8 py-2.5">
                                                        <div class="flex items-center gap-3">
                                                            <div class="h-1.5 w-1.5 rounded-full shadow-sm
                                                                {{ str_contains($type, 'Tiket') ? 'bg-blue-400' : '' }}
                                                                {{ str_contains($type, 'Layanan') ? 'bg-purple-400' : '' }}
                                                                {{ str_contains($type, 'Produk') ? 'bg-orange-400' : '' }}
                                                                {{ str_contains($type, 'Umroh') ? 'bg-green-400' : '' }}
                                                                {{ str_contains($type, 'Haji') ? 'bg-emerald-400' : '' }}
                                                            "></div>
                                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $type }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-2.5 text-right">
                                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ number_format($amount, 0, ',', '.') }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>

                            <!-- Laba Kotor -->
                            <tr class="bg-gray-50/30 dark:bg-gray-800/20">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-700 dark:text-gray-200">Laba-Kotor (gross profit)</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($grossProfit, 0, ',', '.') }}</span>
                                </td>
                            </tr>

                            <!-- Pemasukan Umum -->
                            <tr class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50" @click="showGeneralIncomeDetail = !showGeneralIncomeDetail">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 transition-transform duration-200" :class="showGeneralIncomeDetail ? 'rotate-90' : ''">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-700 dark:text-gray-200">Pemasukan Umum</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-lg font-bold text-green-600">Rp {{ number_format($totalPemasukanUmum, 0, ',', '.') }}</span>
                                </td>
                            </tr>

                            <!-- General Income Breakdown -->
                            <tr x-show="showGeneralIncomeDetail" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="bg-gray-50/50 dark:bg-gray-800/30"
                                style="display: none;"
                            >
                                <td colspan="2" class="px-0 py-0">
                                    <div class="border-l-4 border-green-500 ml-6 my-2">
                                        <table class="w-full">
                                            <tbody class="divide-y divide-gray-100/50 dark:divide-gray-800/50">
                                                @foreach($generalIncomeSubTotals as $type => $amount)
                                                <tr class="hover:bg-white/50 dark:hover:bg-gray-900/30 transition-colors">
                                                    <td class="px-8 py-2.5">
                                                        <div class="flex items-center gap-3">
                                                            <div class="h-1.5 w-1.5 rounded-full shadow-sm bg-indigo-400"></div>
                                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $type }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-2.5 text-right">
                                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ number_format($amount, 0, ',', '.') }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>

                            <!-- Pengeluaran Umum -->
                            <tr class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50" @click="showGeneralExpenseDetail = !showGeneralExpenseDetail">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-pink-100 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400 transition-transform duration-200" :class="showGeneralExpenseDetail ? 'rotate-90' : ''">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-700 dark:text-gray-200">Pengeluaran Umum</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-lg font-bold text-pink-600">Rp {{ number_format($totalPengeluaranUmum, 0, ',', '.') }}</span>
                                </td>
                            </tr>

                            <!-- General Expense Breakdown -->
                            <tr x-show="showGeneralExpenseDetail" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="bg-gray-50/50 dark:bg-gray-800/30"
                                style="display: none;"
                            >
                                <td colspan="2" class="px-0 py-0">
                                    <div class="border-l-4 border-pink-500 ml-6 my-2">
                                        <table class="w-full">
                                            <tbody class="divide-y divide-gray-100/50 dark:divide-gray-800/50">
                                                @foreach($generalExpenseSubTotals as $type => $amount)
                                                <tr class="hover:bg-white/50 dark:hover:bg-gray-900/30 transition-colors">
                                                    <td class="px-8 py-2.5">
                                                        <div class="flex items-center gap-3">
                                                            <div class="h-1.5 w-1.5 rounded-full shadow-sm bg-pink-400"></div>
                                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $type }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-2.5 text-right">
                                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ number_format($amount, 0, ',', '.') }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>

                            <!-- Laba Bersih Sebelum Pajak -->
                            <tr class="bg-gray-100 dark:bg-gray-800">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $netProfitBeforeTax >= 0 ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' }}">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-gray-800 dark:text-white">Laba Bersih Sebelum Pajak</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-xl font-bold {{ $netProfitBeforeTax >= 0 ? 'text-green-600' : 'text-red-600' }}">Rp {{ number_format($netProfitBeforeTax, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="col-span-12" x-data='{
            items: @json($reportData),
            currentPage: 1,
            itemsPerPage: 20,
            sortField: "date",
            sortDirection: "desc", 
            searchQuery: "",
            
            get filteredItems() {
                if (!this.searchQuery) return this.items;
                const query = this.searchQuery.toLowerCase();
                return this.items.filter(item => {
                    return (item.no_transaksi && item.no_transaksi.toLowerCase().includes(query)) ||
                           (item.item_name && item.item_name.toLowerCase().includes(query)) ||
                           (item.type && item.type.toLowerCase().includes(query));
                });
            },
            get paginatedItems() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredItems.slice(start, end);
            },
            get totalPages() {
                 return Math.ceil(this.filteredItems.length / this.itemsPerPage);
            },
            formatDate(dateString) {
                 if(!dateString) return "-";
                 const options = { day: "2-digit", month: "short", year: "numeric" };
                 return new Date(dateString).toLocaleDateString("id-ID", options);
            },
            formatCurrency(value) {
                return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(value);
            }
        }'>

            <div class="flex flex-col gap-2 px-6 py-4 bg-white border border-gray-200 rounded-t-xl dark:bg-gray-900 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-white">Detail Penjualan per Item</h3>
                </div>
                 <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative">
                        <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari Item/Trx..." class="h-[40px] w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white xl:w-[250px]"/>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden border border-t-0 border-gray-200 rounded-b-xl bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="max-w-full overflow-x-auto custom-scrollbar">
                    <table class="w-full min-w-[1000px]">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 dark:bg-gray-800 dark:border-gray-800">
                                <th class="px-4 py-3 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tanggal</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400">No. Transaksi</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tipe</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400">Item</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400">Qty</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400">Penjualan</th>
                                <th class="px-4 py-3 text-right font-medium text-red-600 text-theme-xs dark:text-red-400">HPP (Modal)</th>
                                <th class="px-4 py-3 text-right font-medium text-green-600 text-theme-xs dark:text-green-400">Laba Kotor</th>
                            </tr>
                        </thead>
                        <tbody>
                              <template x-for="(item, index) in paginatedItems" :key="index">
                                <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-4 py-3 text-gray-500 text-theme-sm dark:text-gray-400" x-text="((currentPage - 1) * itemsPerPage) + index + 1"></td>
                                    <td class="px-4 py-3 text-gray-500 text-theme-sm dark:text-gray-400" x-text="formatDate(item.date)"></td>
                                    <td class="px-4 py-3 text-gray-800 font-medium text-theme-sm dark:text-white/90" x-text="item.no_transaksi"></td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                            :class="{
                                                'bg-blue-100 text-blue-800': item.type === 'Tiket',
                                                'bg-purple-100 text-purple-800': item.type === 'Layanan',
                                                'bg-orange-100 text-orange-800': item.type === 'Produk',
                                                'bg-green-100 text-green-800': item.type === 'Umroh',
                                                'bg-emerald-100 text-emerald-800': item.type === 'Haji',
                                                'bg-pink-100 text-pink-800': item.type === 'Pengeluaran Umum',
                                                'bg-indigo-100 text-indigo-800': item.type === 'Pemasukan Umum'
                                            }" 
                                            x-text="item.type">
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 text-theme-sm dark:text-gray-400" x-text="item.item_name"></td>
                                    <td class="px-4 py-3 text-center text-gray-500 text-theme-sm dark:text-gray-400" x-text="item.quantity"></td>
                                    <td class="px-4 py-3 text-right text-gray-800 font-medium text-theme-sm dark:text-gray-300" x-text="formatCurrency(item.revenue)"></td>
                                    <td class="px-4 py-3 text-right text-red-500 font-medium text-theme-sm dark:text-red-400" x-text="formatCurrency(item.cogs)"></td>
                                    <td class="px-4 py-3 text-right font-bold text-theme-sm" 
                                        :class="item.profit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                                        x-text="formatCurrency(item.profit)">
                                    </td>
                                </tr>
                             </template>
                             <tr x-show="filteredItems.length === 0">
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    Tidak ada data penjualan.
                                </td>
                             </tr>
                        </tbody>
                    </table>
                </div>
                 <!-- Pagination -->
                  <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
                    <div class="flex items-center justify-between">
                         <button @click="currentPage--" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 hover:bg-gray-50">Previous</button>
                          <span class="text-sm text-gray-700 dark:text-gray-400">
                            Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
                        </span>
                         <button @click="currentPage++" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 hover:bg-gray-50">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
