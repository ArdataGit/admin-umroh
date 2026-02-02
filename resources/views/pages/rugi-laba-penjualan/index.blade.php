@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Laporan Rugi Laba Penjualan" :breadcrumbs="[
    ['label' => 'Rugi Laba Penjualan', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 space-y-4">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Penjualan (Omzet)</div>
                <div class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total HPP (Modal)</div>
                <div class="mt-2 text-2xl font-bold text-red-500">Rp {{ number_format($totalCOGS, 0, ',', '.') }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Laba Kotor</div>
                <div class="mt-2 text-2xl font-bold {{ $totalProfit >= 0 ? 'text-green-500' : 'text-red-500' }}">Rp {{ number_format($totalProfit, 0, ',', '.') }}</div>
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
                                    <td class="px-4 py-3 text-gray-500 text-theme-sm dark:text-gray-400" x-text="formatDate(item.date)"></td>
                                    <td class="px-4 py-3 text-gray-800 font-medium text-theme-sm dark:text-white/90" x-text="item.no_transaksi"></td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                            :class="{
                                                'bg-blue-100 text-blue-800': item.type === 'Tiket',
                                                'bg-purple-100 text-purple-800': item.type === 'Layanan',
                                                'bg-orange-100 text-orange-800': item.type === 'Produk'
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
