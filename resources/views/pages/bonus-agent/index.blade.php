@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Bonus Agent" :breadcrumbs="[
    ['label' => 'Bonus Agent', 'url' => '#']
]" />

@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/20 dark:text-green-400">
    <div class="flex items-center gap-2">
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@if(session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-red-900/20 dark:text-red-400">
    <div class="flex items-center gap-2">
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
</div>
@endif

<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12" x-data='{
        agents: @json($agents),
        currentPage: 1,
        itemsPerPage: 10,
        sortField: "",
        sortDirection: "asc",
        searchQuery: "",
        
        get filteredAgents() {
            if (!this.searchQuery) return this.agents;
            const query = this.searchQuery.toLowerCase();
            return this.agents.filter(agent => {
                return agent.nama_agent.toLowerCase().includes(query) ||
                       agent.nik_agent.toLowerCase().includes(query) ||
                       agent.kontak_agent.toLowerCase().includes(query);
            });
        },
        get sortedAgents() {
            if (!this.sortField) return this.filteredAgents;
            return [...this.filteredAgents].sort((a, b) => {
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
            return Math.ceil(this.sortedAgents.length / this.itemsPerPage);
        },
        get paginatedAgents() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.sortedAgents.slice(start, end);
        },
        get displayPages() {
            // ... Logic copied from Maskapai (simplified for brevity) ...
            const pages = [];
            const maxVisible = 5;
            if (this.totalPages <= maxVisible) {
                for (let i = 1; i <= this.totalPages; i++) pages.push(i);
            } else {
                pages.push(1);
                pages.push("...");
                pages.push(this.totalPages);
            }
             return pages;
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
        formatPrice(price) {
            return "Rp " + Number(price).toLocaleString("id-ID");
        }
    }'>

        <div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600 dark:text-gray-400">Show</label>
                    <select x-model="itemsPerPage" @change="currentPage = 1" class="h-10 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <label class="text-sm text-gray-600 dark:text-gray-400">entries</label>
                </div>
            </div>
             <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="flex gap-3">
                    <a href="{{ route('bonus-agent.print') }}" target="_blank" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print
                    </a>
                    <a href="{{ route('bonus-agent.export') }}" target="_blank" class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-green-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </a>
                </div>
                <form>
                    <div class="relative">
                        <button type="button" class="absolute -translate-y-1/2 left-4 top-1/2">
                            <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                            </svg>
                        </button>
                        <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Search Agent..." class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-blue-800 xl:w-[300px]"/>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="w-full min-w-[1200px]">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</p>
                            </th>
                            <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('nama_agent')">
                                <div class="flex items-center gap-1">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama Agent</p>
                                    <svg class="w-4 h-4" :class="sortField === 'nama_agent' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('nik_agent')">
                                <div class="flex items-center gap-1">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">NIK Agent</p>
                                      <svg class="w-4 h-4" :class="sortField === 'nik_agent' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                </div>
                            </th>
                             <th class="px-4 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kontak Agent</p>
                            </th>
                             <th class="px-4 py-3 text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('umroh_count')">
                                <div class="flex items-center justify-center gap-1">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Jamaah Umroh</p>
                                     <svg class="w-4 h-4" :class="sortField === 'umroh_count' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                </div>
                            </th>
                             <th class="px-4 py-3 text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('haji_count')">
                                <div class="flex items-center justify-center gap-1">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Jamaah Haji</p>
                                     <svg class="w-4 h-4" :class="sortField === 'haji_count' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                </div>
                            </th>
                            <th class="px-4 py-3 text-right cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('total_bonus')">
                                <div class="flex items-center justify-end gap-1">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Total Bonus</p>
                                     <svg class="w-4 h-4" :class="sortField === 'total_bonus' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                </div>
                            </th>
                            <th class="px-4 py-3 text-right">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Sudah Pembayaran</p>
                            </th>
                            <th class="px-4 py-3 text-right cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800" @click="sortBy('sisa_bonus')">
                                <div class="flex items-center justify-end gap-1">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Sisa Bonus</p>
                                     <svg class="w-4 h-4" :class="sortField === 'sisa_bonus' && sortDirection === 'asc' ? 'text-blue-500' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                </div>
                            </th>
                            <th class="px-4 py-3 text-center">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Action</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                         <template x-for="(agent, index) in paginatedAgents" :key="agent.id">
                            <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="((currentPage - 1) * itemsPerPage) + index + 1"></p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90" x-text="agent.nama_agent"></p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="agent.nik_agent"></p>
                                </td>
                                <td class="px-4 py-4">
                                     <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="agent.kontak_agent"></p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                     <a :href="`/bonus-agent/${agent.id}/jamaah-umroh`" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800 cursor-pointer transition-colors" x-text="agent.umroh_count + ' Pax'"></a>
                                </td>
                                <td class="px-4 py-4 text-center">
                                     <a :href="`/bonus-agent/${agent.id}/jamaah-haji`" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300 hover:bg-purple-200 dark:hover:bg-purple-800 cursor-pointer transition-colors" x-text="agent.haji_count + ' Pax'"></a>
                                </td>
                                <td class="px-4 py-4 text-right">
                                     <p class="font-medium text-blue-600 text-theme-sm" x-text="formatPrice(agent.total_bonus)"></p>
                                </td>
                                <td class="px-4 py-4 text-right">
                                     <p class="font-medium text-green-600 text-theme-sm" x-text="formatPrice(agent.sudah_dibayar)"></p>
                                </td>
                                <td class="px-4 py-4 text-right">
                                     <p class="font-bold text-theme-sm" :class="agent.sisa_bonus > 0 ? 'text-red-600' : 'text-gray-500'" x-text="formatPrice(agent.sisa_bonus)"></p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($canCreate)
                                        <template x-if="agent.sisa_bonus > 0">
                                            <a :href="`/bonus-agent/${agent.id}`" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500" title="Add Payment">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </a>
                                        </template>
                                        @endif
                                        
                                        <a :href="`/payment-agent/${agent.id}`" class="text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-500" title="Show Payment">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                         </template>
                         <tr x-show="filteredAgents.length === 0">
                            <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data yang ditemukan.
                            </td>
                         </tr>
                    </tbody>
                </table>
            </div>
             <!-- Pagination Code (Simplified from Maskapai) -->
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
