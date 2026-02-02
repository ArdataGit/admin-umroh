@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Data Pembayaran Layanan" :breadcrumbs="[
    ['label' => 'Pembayaran Layanan', 'url' => '#']
]" />

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data='{
        items: @json($pembayarans),
        currentPage: 1,
        itemsPerPage: 10,
        sortField: "",
        sortDirection: "asc",
        searchQuery: "",
        
        get filteredItems() {
            if (!this.searchQuery) return this.items;
            const query = this.searchQuery.toLowerCase();
            return this.items.filter(item => {
                return (item.kode_transaksi && item.kode_transaksi.toLowerCase().includes(query)) ||
                       (item.transaksi_layanan && item.transaksi_layanan.kode_transaksi && item.transaksi_layanan.kode_transaksi.toLowerCase().includes(query)) ||
                       (item.transaksi_layanan && item.transaksi_layanan.pelanggan && item.transaksi_layanan.pelanggan.nama_pelanggan && item.transaksi_layanan.pelanggan.nama_pelanggan.toLowerCase().includes(query));
            });
        },
        get sortedItems() {
            if (!this.sortField) return this.filteredItems;
            return [...this.filteredItems].sort((a, b) => {
                let aVal = a[this.sortField];
                let bVal = b[this.sortField];
                
                if (typeof aVal === "string") {
                    aVal = aVal.toLowerCase();
                    bVal = bVal.toLowerCase();
                }
                if (aVal < bVal) return this.sortDirection === "asc" ? -1 : 1;
                if (aVal > bVal) return this.sortDirection === "asc" ? 1 : -1;
                return 0;
            });
        },
        get totalPages() {
            return Math.ceil(this.sortedItems.length / this.itemsPerPage);
        },
        get paginatedItems() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.sortedItems.slice(start, end);
        },
        sortBy(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
            } else {
                this.sortField = field;
                this.sortDirection = "asc";
            }
            this.currentPage = 1;
        },
        formatDate(dateString) {
             const options = { day: "2-digit", month: "long", year: "numeric" };
             return new Date(dateString).toLocaleDateString("id-ID", options);
        },
        formatCurrency(value) {
            return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(value);
        }
    }'>

        <div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
             <div class="flex flex-col gap-3 sm:flex-row sm:items-center ml-auto">
                <form>
                    <div class="relative">
                        <button type="button" class="absolute -translate-y-1/2 left-4 top-1/2">
                            <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                            </svg>
                        </button>
                        <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Search Pembayaran..." class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-blue-800 xl:w-[300px]"/>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="w-full min-w-[1000px]">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</p>
                            </th>
                            <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tanggal Pembayaran</p>
                            </th>
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kode Transaksi</p>
                            </th>
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kode Trx. Layanan</p>
                            </th>
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama Mitra</p>
                            </th>
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Jumlah Pembayaran</p>
                            </th>
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Metode Pembayaran</p>
                            </th>
                             <th class="px-4 py-3 text-center">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status Pembayaran</p>
                            </th>
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kode Referensi</p>
                            </th>
                            <th class="px-4 py-3 text-center">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Action</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                          <template x-for="(item, index) in paginatedItems" :key="item.id">
                            <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="((currentPage - 1) * itemsPerPage) + index + 1"></p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="formatDate(item.tanggal_pembayaran)"></p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90" x-text="item.kode_transaksi"></p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="item.transaksi_layanan?.kode_transaksi || '-'"></p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="item.transaksi_layanan?.pelanggan?.nama_pelanggan || '-'"></p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90" x-text="formatCurrency(item.jumlah_pembayaran)"></p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400 capitalize" x-text="item.metode_pembayaran"></p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                     <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="{
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': item.status_pembayaran === 'paid',
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': item.status_pembayaran === 'pending',
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': item.status_pembayaran === 'failed'
                                        }"
                                        x-text="item.status_pembayaran">
                                     </span>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="item.kode_referensi || '-'"></p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                         </template>
                         <tr x-show="filteredItems.length === 0">
                            <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data pembayaran layanan.
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
@endsection
